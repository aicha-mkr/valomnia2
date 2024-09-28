<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Recapitulatif;
use Illuminate\Support\Facades\Http;

class GenerateRecapitulatifMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            // Récupérer les données de l'API
            $response = Http::get('https://developers.valomnia.com/'); // Remplacez par l'URL de votre API
            $data = $response->json();

            // Calculer les récapitulatifs
            $totalClients = count($data['clients']);
            $totalOrders = count($data['orders']);
            $totalRevenue = array_sum(array_column($data['orders'], 'amount'));
            $averageSales = $totalOrders ? $totalRevenue / $totalOrders : 0;

            // Créer un nouvel enregistrement
            Recapitulatif::create([
                'user_id' => Auth::id(),
                'total_clients' => $totalClients,
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
                'average_sales' => $averageSales,
            ]);
        }

        return $next($request);
    }
}