<?php
namespace App\Services;
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

    
}