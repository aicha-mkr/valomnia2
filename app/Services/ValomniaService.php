<?php
namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\WeeklySummary;
use Illuminate\Support\Facades\Auth;
use App\Models\Recapitulatif;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ValomniaService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = env('VALOMNIA_BASE_URL', 'https://developers.valomnia.com/');
        $this->apiKey = env('VALOMNIA_API_KEY');
    }

    // Fetch operations by ID
    public function getOperations($id)
    {
        $endpoint = "api/VERSION/orders/{$id}"; // Ensure VERSION is properly specified
        return $this->getData($endpoint);
    }
    
    // Fetch employee data by ID
    public function getEmployees($id)
    {
        $endpoint = "api/VERSION/employees/{$id}"; // Ensure VERSION is properly specified
        return $this->getData($endpoint);
    }

    // Generic method to get data from the API
    private function getData($endpoint) {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'API-Key' => $this->apiKey
        ])->withCookies(request()->cookies->all(), 'your-domain.com') // Replace with your actual domain
          ->get($this->baseUrl . $endpoint);
    
        if ($response->successful()) {
            $data = $response->json();
            Log::info('API response for endpoint: ' . $endpoint, $data);
            return $data;
        } else {
            Log::error('API request failed for endpoint: ' . $endpoint, [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;
        }
    }

    // Calculate KPIs based on specific operation ID
    public function calculateKPI($operationId)
    {
        $operation = $this->getOperations($operationId);
        $employees = $this->getEmployees(Auth::id()); // Get current user's employee data

        if (!$operation || !$employees) {
            return [
                'totalRevenue' => 0,
                'totalOrders' => 0,
                'totalEmployees' => 0,
                'averageSales' => 0,
            ];
        }

        // Calculate KPIs
        $totalRevenue = $operation['totalDiscounted'] ?? 0;
        $totalOrders = 1; // Since we are fetching a single operation
        $totalEmployees = count($employees); // Count of employees related to the operation
        $averageSales = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return [
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'totalEmployees' => $totalEmployees,
            'averageSales' => $averageSales,
        ];
    }

    // Store recap data into the database
    public function storeRecapData($recapData)
    {
        Log::info('Storing recap data:', $recapData);

        Recapitulatif::updateOrCreate(
            ['user_id' => Auth::id(), 'date' => now()->toDateString()],
            [
                'total_orders' => $recapData['totalOrders'],
                'total_revenue' => $recapData['totalRevenue'],
                'average_sales' => $recapData['averageSales'],
                'total_quantities' => $recapData['totalQuantities'] ?? 0,
                'total_clients' => $recapData['totalClients'] ?? 0,
            ]
        );
    }
    
}