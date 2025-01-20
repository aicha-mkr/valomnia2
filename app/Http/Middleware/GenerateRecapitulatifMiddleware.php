<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Recapitulatif;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GenerateRecapitulatifMiddleware
{
    public function handle($request, Closure $next)
    {
        // Log that the middleware is executed
        Log::info('GenerateRecapitulatifMiddleware executed');

        if (Auth::check()) {
            // Log authenticated user
            Log::info('User is authenticated: ' . Auth::id());

            // Récupérer les données de l'API
            $response = Http::get('https://developers.valomnia.com/'); // Replace with your API URL

            if ($response->successful()) {
                $data = $response->json();

                // Log the API response
                Log::info('API Response: ', $data);

                // Calculate recapitulatif data
                $totalClients = count($data['clients'] ?? []);
                $totalOrders = count($data['orders'] ?? []);
                $totalRevenue = array_sum(array_column($data['orders'] ?? [], 'amount'));
                $averageSales = $totalOrders ? $totalRevenue / $totalOrders : 0;

                // Create a new record
                Recapitulatif::create([
                    'user_id' => Auth::id(),
                    'total_clients' => $totalClients,
                    'total_orders' => $totalOrders,
                    'total_revenue' => $totalRevenue,
                    'average_sales' => $averageSales,
                ]);

                // Log success
                Log::info('Recapitulatif created successfully for user: ' . Auth::id());
            } else {
                // Log API error
                Log::error('Failed to fetch data from API', ['response' => $response->body()]);
                return response()->json(['error' => 'Failed to fetch data from API'], 500);
            }
        } else {
            Log::warning('User is not authenticated.');
        }

        return $next($request);
    }
}