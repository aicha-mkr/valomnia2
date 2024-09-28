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
        // Assurez-vous que l'utilisateur est authentifié
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Récupérer le nombre d'utilisateurs
        $totalClients = $this->valomniaService->getTotalEmployees();

        // Récupérer les opérations (commandes, pré-commandes, etc.)
        $operations = $this->valomniaService->getOperations();

        // Vérifiez si les opérations sont renvoyées correctement
        if (!$operations) {
            return response()->json(['error' => 'Failed to fetch data from Valomnia API'], 500);
        }

        // Initialiser les variables
        $totalRevenue = 0; 
        $totalOrders = count($operations); 
        $totalQuantities = 0; 

        foreach ($operations as $operation) {
            // Vérifiez que les champs existent avant de faire la somme
            $totalRevenue += $operation['totalDiscounted'] ?? 0; 
            $totalQuantities += $operation['quantity'] ?? 0; 
        }

        // Calculer la moyenne des ventes
        $averageSales = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0; 

        // Enregistrez les récapitulatifs dans la base de données
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
        // Récupérer le dernier récapitulatif pour l'utilisateur authentifié
        $recap = Recapitulatif::where('user_id', Auth::id())->latest()->first();

        // Vérifiez si le récapitulatif existe et passez les variables à la vue
        return view('content.dashboard.dashboards-analytics', [
            'totalOrders' => $recap->total_orders ?? 0,
            'totalRevenue' => $recap->total_revenue ?? 0,
            'averageSales' => $recap->average_sales ?? 0,
            'totalQuantities' => $recap->total_quantities ?? 0, 
            'totalClients' => $recap->total_clients ?? 0,
        ]);
    }
}