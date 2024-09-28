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

        if (is_null($this->apiKey)) {
            throw new \Exception('API key is not set in the environment variables.');
        }
    }

    public function getOperations()
    {
        $endpoint = '/operations';
        return $this->getData($endpoint);
    }

    public function getEmployees()
    {
        $endpoint = '/employees';
        return $this->getData($endpoint);
    }

    public function getSalesForWeek($startDate, $endDate)
    {
        $operations = $this->getOperations();
        
        if (!isset($operations['data'])) {
            return [];
        }

        // Filter operations for the specified week
        return array_filter($operations['data'], function ($operation) use ($startDate, $endDate) {
            $operationDate = \Carbon\Carbon::parse($operation['date']); // Adjust the key based on your API response
            return $operationDate->between($startDate, $endDate);
        });
    }

    private function getData($endpoint)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
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
        $operations = $this->getOperations();
        $employees = $this->getEmployees();

        if (!isset($operations['data']) || !isset($employees['data'])) {
            return [
                'totalRevenue' => 0,
                'totalOrders' => 0,
                'totalEmployees' => 0,
                'averageSales' => 0,
            ];
        }

        $totalRevenue = array_sum(array_column($operations['data'], 'totalDiscounted'));
        $totalOrders = count(array_column($operations['data'], 'reference'));
        $totalEmployees = count($employees['data']);
        $averageSales = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return [
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'totalEmployees' => $totalEmployees,
            'averageSales' => $averageSales,
        ];
    }
}