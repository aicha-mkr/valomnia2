<?php

namespace App\Http\Controllers;

use App\Models\Recapitulatif;
use App\Services\ValomniaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecapitulatifController extends Controller
{
    protected $valomniaService;

    public function __construct(ValomniaService $valomniaService)
    {
        $this->valomniaService = $valomniaService;
    }

    public function generateRecapitulatifs()
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Retrieve total employees
        $totalClients = $this->valomniaService->getEmployees();

        // Retrieve operations
        $operations = $this->valomniaService->getOperations();

        // Check if operations were fetched correctly
        if (!$operations) {
            return response()->json(['error' => 'Failed to fetch data from Valomnia API'], 500);
        }

        // Initialize variables
        $totalRevenue = 0; 
        $totalOrders = count($operations); 
        $totalQuantities = 0; 

        foreach ($operations as $operation) {
            // Ensure fields exist before summing
            $totalRevenue += $operation['totalDiscounted'] ?? 0; 
            $totalQuantities += $operation['quantity'] ?? 0; 
        }

        // Calculate average sales
        $averageSales = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0; 

        // Save recap in the database
        $recap = new Recapitulatif();
        $recap->user_id = Auth::id();
        $recap->date = now();
        $recap->total_orders = $totalOrders;
        $recap->total_revenue = $totalRevenue; 
        $recap->average_sales = $averageSales;
        $recap->total_quantities = $totalQuantities; 
        $recap->total_clients = $totalClients;
        $recap->save();

        return response()->json(['message' => 'Recapitulative generated successfully']);
    }

    public function showDashboard()
    {
        // Retrieve sales data for the current week
        $currentWeekSales = $this->valomniaService->getSalesForWeek(now()->startOfWeek(), now()->endOfWeek());
        // Retrieve sales data for the last week
        $lastWeekSales = $this->valomniaService->getSalesForWeek(now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek());
    
        // Calculate average sales for both weeks, ensuring fallback to 0 if no sales exist
        $averageSalesCurrentWeek = $currentWeekSales ? collect($currentWeekSales)->avg('totalDiscounted') : 0;
        $averageSalesLastWeek = $lastWeekSales ? collect($lastWeekSales)->avg('totalDiscounted') : 0;
    
        // Retrieve the latest recap for the authenticated user
        $recap = Recapitulatif::where('user_id', Auth::id())->latest()->first();
    
        // Pass variables to the view with fallback values
        return view('content.dashboard.dashboards-analytics', [
            'totalOrders' => $recap->total_orders ?? 0,
            'totalRevenue' => $recap->total_revenue ?? 0,
            'averageSales' => $recap->average_sales ?? 0,
            'totalQuantities' => $recap->total_quantities ?? 0,
            'totalClients' => $recap->total_clients ?? 0,
            'averageSalesCurrentWeek' => $averageSalesCurrentWeek,
            'averageSalesLastWeek' => $averageSalesLastWeek, // Ensure this line is present
        ]);
    }
    }