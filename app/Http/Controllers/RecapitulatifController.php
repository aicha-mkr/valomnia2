<?php

namespace App\Http\Controllers;

use App\Models\Recapitulatif;
use App\Services\ValomniaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Import Log for logging

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

        // Retrieve total clients
        $totalClients = $this->valomniaService->getEmployees();
        Log::info('Total clients retrieved:', ['total_clients' => $totalClients]);

        // Retrieve operations
        $operations = $this->valomniaService->getOperations();
        Log::info('Operations retrieved:', ['operations' => $operations]);

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
        $recap = Recapitulatif::create([
            'user_id' => Auth::id(),
            'date' => now(),
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue, 
            'average_sales' => $averageSales,
            'total_quantities' => $totalQuantities, 
            'total_clients' => $totalClients,
        ]);
        
        Log::info('Recapitulatif created:', ['recap' => $recap]);

        return response()->json(['message' => 'Recapitulative generated successfully']);
    }

    public function showDashboard()
    {
     
        // Retrieve the latest recap for the authenticated user
        $recap = Recapitulatif::where('user_id', Auth::id())->latest()->first();
    
        // Pass variables to the view with fallback values
        return view('content.dashboard.dashboards-analytics', [
            'totalOrders' => $recap->total_orders ?? 0,
            'totalRevenue' => $recap->total_revenue ?? 0,
            'averageSales' => $recap->average_sales ?? 0,
            'totalQuantities' => $recap->total_quantities ?? 0,
            'totalClients' => $recap->total_clients ?? 0,
      
        ]);
    }
}