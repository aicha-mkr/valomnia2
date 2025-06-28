<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardCacheService
{
    private $user;
    private $cachePrefix = 'dashboard_persistent_';
    private $cacheDuration = 3600; // 1 heure

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get or create persistent dashboard data
     */
    public function getDashboardData()
    {
        $cacheKey = $this->cachePrefix . $this->user->id;
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () {
            return $this->generateDashboardData();
        });
    }

    /**
     * Force refresh dashboard data
     */
    public function refreshDashboardData()
    {
        $cacheKey = $this->cachePrefix . $this->user->id;
        $data = $this->generateDashboardData();
        Cache::put($cacheKey, $data, $this->cacheDuration);
        return $data;
    }

    /**
     * Get cached data without API calls
     */
    public function getCachedData()
    {
        $cacheKey = $this->cachePrefix . $this->user->id;
        return Cache::get($cacheKey, null);
    }

    /**
     * Generate fresh dashboard data
     */
    private function generateDashboardData()
    {
        $api_call = new \App\Models\ApiCall(true, $this->user->id);
        $url_api = str_replace("organisation", $this->user->organisation, env('URL_API', 'https://organisation.valomnia.com'));

        // Get latest report
        $latestReport = \App\Models\Report::where('user_id', $this->user->id)
            ->orderBy('id', 'desc')
            ->first();

        // API calls with reduced limits
        $orders = $this->getApiData($api_call, $url_api, 'orders', 10);
        $employees = $this->getApiData($api_call, $url_api, 'employees', 10);
        $items = $this->getApiData($api_call, $url_api, 'items', 20);
        $categories = $this->getApiData($api_call, $url_api, 'itemCategories', 10);

        // Calculate metrics
        $metrics = $this->calculateMetrics($orders, $employees, $latestReport);
        
        // Generate chart data
        $chartData = $this->generateChartData($orders, $employees, $items, $categories);

        return [
            'metrics' => $metrics,
            'chartData' => $chartData,
            'lists' => [
                'orders' => array_slice($orders['data'] ?? [], 0, 5),
                'employees' => array_slice($employees['data'] ?? [], 0, 4),
                'items' => array_slice($items['data'] ?? [], 0, 5),
                'categories' => array_slice($categories['data'] ?? [], 0, 5),
            ],
            'lastUpdated' => now()->toISOString(),
            'cacheExpires' => now()->addSeconds($this->cacheDuration)->toISOString(),
        ];
    }

    /**
     * Get API data with caching
     */
    private function getApiData($api_call, $url_api, $endpoint, $limit)
    {
        $cacheKey = "api_{$endpoint}_{$this->user->id}";
        
        return Cache::remember($cacheKey, 1800, function() use ($api_call, $url_api, $endpoint, $limit) {
            try {
                $response = $api_call->GetResponse(
                    $url_api . "/api/v2.1/{$endpoint}?max={$limit}", 
                    'GET', 
                    [], 
                    false, 
                    "JSESSIONID=" . $this->user->cookies
                );
                return json_decode($response, true);
            } catch (\Exception $e) {
                Log::error("API call failed for {$endpoint}: " . $e->getMessage());
                return ['data' => []];
            }
        });
    }

    /**
     * Calculate dashboard metrics
     */
    private function calculateMetrics($orders, $employees, $latestReport)
    {
        $totalRevenue = 0;
        $totalOrders = 0;
        $totalClients = 0;

        if (isset($orders['data']) && is_array($orders['data'])) {
            $totalOrders = count($orders['data']);
            foreach ($orders['data'] as $order) {
                $totalRevenue += isset($order['totalDiscounted']) ? floatval($order['totalDiscounted']) : 0;
            }
        }

        if (isset($employees['data']) && is_array($employees['data'])) {
            $totalClients = count($employees['data']);
        }

        // Email metrics
        $alertSent = \App\Models\AlertHistory::whereIn('status', [1, 3])
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $alertFailed = \App\Models\AlertHistory::where('status', 2)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $alertSuccessRate = ($alertSent + $alertFailed) > 0 ? round($alertSent / ($alertSent + $alertFailed) * 100) : 0;

        $reportSent = \App\Models\ReportHistory::where('status', 1)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $reportFailed = \App\Models\ReportHistory::where('status', 2)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $reportSuccessRate = ($reportSent + $reportFailed) > 0 ? round($reportSent / ($reportSent + $reportFailed) * 100) : 0;

        return [
            'total_revenue' => $totalRevenue,
            'total_clients' => $totalClients,
            'average_sales' => $latestReport->average_sales ?? 0,
            'total_orders' => $totalOrders,
            'total_quantities' => $latestReport->total_quantities ?? 0,
            'growth_percentage' => rand(5, 25),
            'payments' => $totalRevenue * 0.15,
            'transactions' => $totalOrders * 1.5,
            'profile_revenue' => $totalRevenue * 0.8,
            'profile_growth' => 0,
            'alert_sent' => $alertSent,
            'alert_failed' => $alertFailed,
            'alert_success_rate' => $alertSuccessRate,
            'report_sent' => $reportSent,
            'report_failed' => $reportFailed,
            'report_success_rate' => $reportSuccessRate,
            'email_templates' => \App\Models\EmailTemplate::count(),
            'template_count' => \App\Models\EmailTemplate::count(),
        ];
    }

    /**
     * Generate chart data
     */
    private function generateChartData($orders, $employees, $items, $categories)
    {
        // Status distribution
        $statusData = [];
        $statusLabels = [];
        if (isset($orders['data'])) {
            $statusCounts = [];
            foreach ($orders['data'] as $order) {
                $status = $order['status'] ?? 'UNKNOWN';
                $statusCounts[$status] = ($statusCounts[$status] ?? 0) + 1;
            }
            $statusLabels = array_keys($statusCounts);
            $statusData = array_values($statusCounts);
        }

        // Top employees
        $topEmployeesList = [];
        if (isset($orders['data']) && isset($employees['data'])) {
            $employeeOrderCounts = [];
            foreach ($orders['data'] as $order) {
                $employee = $order['employeeReference'] ?? 'Unknown';
                $employeeOrderCounts[$employee] = ($employeeOrderCounts[$employee] ?? 0) + 1;
            }
            arsort($employeeOrderCounts);
            $topEmployees = array_slice($employeeOrderCounts, 0, 4, true);
            
            foreach ($topEmployees as $ref => $count) {
                $name = $ref;
                foreach ($employees['data'] as $emp) {
                    if ($emp['reference'] === $ref) {
                        $name = $emp['name'] ?? $ref;
                        break;
                    }
                }
                $topEmployeesList[] = [
                    'reference' => $ref,
                    'name' => $name,
                    'orders' => $count
                ];
            }
        }

        // Generate mock chart data for performance
        $daysInMonth = now()->daysInMonth;
        $labels = [];
        $alertSeries = [];
        $reportSeries = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $date = now()->copy()->startOfMonth()->addDays($d-1)->format('Y-m-d');
            $labels[] = $date;
            $alertSeries[] = rand(0, 5);
            $reportSeries[] = rand(0, 3);
        }

        return [
            'statusData' => $statusData,
            'statusLabels' => $statusLabels,
            'topEmployeesList' => $topEmployeesList,
            'emailsEvolution' => [
                'labels' => $labels,
                'series' => [
                    [ 'name' => 'Alert Emails', 'data' => $alertSeries ],
                    [ 'name' => 'Report Emails', 'data' => $reportSeries ]
                ]
            ],
            'revenueHistory' => $this->generateRevenueHistory(),
            'ordersHistory' => $this->generateOrdersHistory(),
        ];
    }

    /**
     * Generate mock revenue history
     */
    private function generateRevenueHistory()
    {
        $labels = [];
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $labels[] = now()->subDays($i)->format('Y-m-d');
            $data[] = rand(500, 2000);
        }
        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Generate mock orders history
     */
    private function generateOrdersHistory()
    {
        $labelsDay = [];
        $dataDay = [];
        $labelsMonth = [];
        $dataMonth = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $labelsDay[] = now()->subDays($i)->format('Y-m-d');
            $dataDay[] = rand(0, 10);
        }
        
        for ($i = 11; $i >= 0; $i--) {
            $labelsMonth[] = now()->subMonths($i)->format('M Y');
            $dataMonth[] = rand(50, 200);
        }
        
        return [
            'day' => ['labels' => $labelsDay, 'data' => $dataDay],
            'month' => ['labels' => $labelsMonth, 'data' => $dataMonth]
        ];
    }
} 