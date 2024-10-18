<?php

namespace App\Services;
use Illuminate\Support\Facades\Mail;
use App\Mail\WeeklySummary;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ValomniaService
{
    protected $baseUrl;
    protected $bearerToken;

    public function __construct()
    {
        $this->baseUrl = config('services.valomnia.base_url');
        $this->bearerToken = config('services.valomnia.bearer_token');
    
        dump($this->bearerToken); // Check if the token is loaded correctly
    
        if (is_null($this->bearerToken)) {
            throw new \Exception('Bearer token is not set in the environment variables.');
        }
    }

    public function getOperations()
    {
        $endpoint = '/operations';
        $data = $this->getData($endpoint);
        Log::info('Operations fetched:', ['data' => $data]);

        return $data; // Return the full data structure
    }

    public function getEmployees(): array
    {
        $endpoint = '/employees';
        $data = $this->getData($endpoint);
        Log::info('Employees fetched:', ['data' => $data]);

        // Extract emails if data is structured as expected
        return isset($data['data']) ? array_column($data['data'], 'email') : [];
    }

    private function getData($endpoint)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->bearerToken, // Use the correct property
            'Accept' => 'application/json',
        ])->get($this->baseUrl . $endpoint);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('API request failed', [
            'endpoint' => $endpoint,
            'status' => $response->status(),
            'response' => $response->body(),
        ]);

        return null; // Or throw an exception based on your needs
    }
    public function calculateKPI()
    {
        $response = Http::get('https://your-api-endpoint.com/api/your-endpoint');

        if ($response->successful()) {
            $data = $response->json();
            Log::info('Fetched data:', ['data' => $data]);
            
            // Assurez-vous que les données sont présentes
            return [
                'totalRevenue' => $data['data']['totalRevenue'] ?? 0,
                'totalOrders' => $data['data']['totalOrders'] ?? 0,
                'totalEmployees' => $data['data']['totalEmployees'] ?? 0,
                'averageSales' => $data['data']['averageSales'] ?? 0,
            ];
        }

        Log::error('API request failed:', ['status' => $response->status(), 'body' => $response->body()]);
        return [
            'totalRevenue' => 0,
            'totalOrders' => 0,
            'totalEmployees' => 0,
            'averageSales' => 0,
        ];
    }
    private function fetchDataFromSource()
    {
        // Example of fetching data
        $response = Http::get('https://your-api-endpoint.com/api/your-endpoint'); // Adjust as necessary
        $data = $response->json();
    
        Log::info('Fetched data:', ['data' => $data]); // Log the fetched data
    
        return $data['data'] ?? null; // Adjust based on your API response structure
    }
    public function sendWeeklySummary($email)
    {
        $recapData = $this->calculateKPI(); // Ensure this returns valid data
        $recipientName = 'User'; // Replace with actual recipient name if available
        
        Mail::to($email)->send(new WeeklySummary($recapData, $recipientName));
    }


}