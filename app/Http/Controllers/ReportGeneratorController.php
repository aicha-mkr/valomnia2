<?php

namespace App\Http\Controllers;
use App\Models\ReportType;
use App\Models\Warehouse;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReportGeneratorController extends Controller
{
  protected $apiBaseUrl;
  protected $apiKey;

  public function __construct()
  {
    // Load config from .env
    $this->apiBaseUrl = config('services.valomnia.base_url');
    $this->apiKey = config('services.valomnia.api_key');
  }

  /**
   * Show date range form to generate report.
   */

  public function generateForm()
  {
    // Fetch active report types
    $reportTypes = ReportType::where('status', 1)->get(['id', 'name']);

    // Fetch all warehouses (or filter if needed)
    $warehouses = Warehouse::all(['id', 'name']); // or ->pluck('name', 'id');

    return view('content.organisation.reports.generate', compact('reportTypes', 'warehouses'));
  }

  /**
   * Fetch data from Valomnia API and show report.
   */
  public function generateReport(Request $request)
  {
    try {
      // Validate the form inputs
      $validated = $request->validate([
        'startDate' => 'required|date',
        'endDate' => 'required|date|after_or_equal:start_date',
      ]);

      // Extract validated data
      $startDate = $validated['startDate'];
      $endDate = $validated['endDate'];

      // Call Valomnia API to get operations
      $operations = $this->fetchOperations($startDate, $endDate);

      // Calculate KPIs
      $totalRevenue = array_sum(array_column($operations, 'totalDiscounted'));
      $totalOrders = count(array_unique(array_column($operations, 'reference')));
      $averageSales = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

      // Total quantities sold
      $totalQuantities = 0;
      foreach ($operations as $op) {
        if (!empty($op['orderLines'])) {
          foreach ($op['orderLines'] as $line) {
            $totalQuantities += $line['quantity'] ?? 0;
          }
        }
      }

      // Number of unique clients
      $uniqueClients = collect($operations)->pluck('customerReference')->unique()->filter();
      $totalClients = $uniqueClients->count();

      // Top-selling items
      $items = [];
      foreach ($operations as $op) {
        if (!empty($op['orderLines'])) {
          foreach ($op['orderLines'] as $line) {
            $sku = $line['itemUnitId'] ?? 'inconnu';
            $qty = $line['quantity'] ?? 0;

            if (isset($items[$sku])) {
              $items[$sku] += $qty;
            } else {
              $items[$sku] = $qty;
            }
          }
        }
      }

      arsort($items); // Sort descending
      $topSellingItems = array_slice($items, 0, 5, true); // Top 5

      // Pass data to Blade view
      return view('content.organisation.reports.report', compact(
        'totalRevenue',
        'totalOrders',
        'averageSales',
        'totalQuantities',
        'totalClients',
        'topSellingItems',
        'startDate',
        'endDate'
      ));

    } catch (\Exception $ex) {
      return back()->with('error', 'Failed to generate report: ' . $ex->getMessage());
    }
  }

  /**
   * Fetch operations from Valomnia API.
   */
  private function fetchOperations($startDate, $endDate)
  {
    $response = Http::withToken($this->apiKey)
      ->get("{$this->apiBaseUrl}/operations", [
        'from' => $startDate,
        'to' => $endDate
      ]);

    if ($response->successful()) {
      return $response->json() ?? [];
    }

    throw new \Exception("API request failed: " . $response->body());
  }
}
