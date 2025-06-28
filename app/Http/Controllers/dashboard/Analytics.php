<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\AlertHistory;
use App\Models\Report;
use App\Models\TypeAlert;
use App\Models\User;
use App\Models\ReportHistory;
use App\Models\EmailTemplate;
use App\Services\DashboardCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class Analytics extends Controller
{
  public function index()
  {
    // Cards
    $totalReports = Report::count();
    $totalAlerts = Alert::count();
    $totalUpcomingAlerts = Alert::where('date', '>', now())->count();
    $totalAlertType = TypeAlert::count();

    // Emails envoyés aujourd'hui
    $emailsSentToday = AlertHistory::whereDate('created_at', today())
        ->where(function($q) {
            $q->where('status', 1)  // encours
              ->orWhere('status', 3)  // completed
              ->orWhere('status', 'sent')  // sent
              ->orWhere('attempts', '>', 0);
        })
        ->count();

    $emailsSentToday += ReportHistory::whereDate('created_at', today())
        ->where('status', 'sent')
        ->count();

    // Alerts Sent Over Time Chart (2 courbes : alertes et rapports)
    $alertQuery = AlertHistory::selectRaw('DATE_FORMAT(created_at, "%d") as day, COUNT(id) as count')
        ->whereYear('created_at', date('Y'))
        ->whereMonth('created_at', date('m'))
        ->where(function($q) {
            $q->where('status', 1)  // encours
              ->orWhere('status', 3)  // completed
              ->orWhere('status', 'sent')  // sent
              ->orWhere('attempts', '>', 0);
        })
        ->groupBy('day')
        ->orderByRaw('MIN(created_at)')
        ->get();

    $reportQuery = ReportHistory::selectRaw('DATE_FORMAT(created_at, "%d") as day, COUNT(id) as count')
        ->whereYear('created_at', date('Y'))
        ->whereMonth('created_at', date('m'))
        ->where('status', 'sent')  // Seulement les rapports envoyés
        ->groupBy('day')
        ->orderByRaw('MIN(created_at)')
        ->get();

    // Calculer le vrai total des emails envoyés ce mois
    $totalEmailsSentThisMonth = AlertHistory::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', date('m'))
        ->where(function($q) {
            $q->where('status', 1)  // encours
              ->orWhere('status', 3)  // completed
              ->orWhere('status', 'sent')  // sent
              ->orWhere('attempts', '>', 0);
        })
        ->count();

    $totalEmailsSentThisMonth += ReportHistory::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', date('m'))
        ->where('status', 'sent')
        ->count();

    // Debug: Log les données récupérées
    \Log::info('Dashboard Analytics - Data retrieved', [
        'alertQuery_count' => $alertQuery->count(),
        'reportQuery_count' => $reportQuery->count(),
        'alertQuery_data' => $alertQuery->toArray(),
        'reportQuery_data' => $reportQuery->toArray(),
        'alert_statuses' => AlertHistory::select('status')->distinct()->pluck('status')->toArray(),
        'report_statuses' => ReportHistory::select('status')->distinct()->pluck('status')->toArray(),
        'total_alert_histories' => AlertHistory::count(),
        'total_report_histories' => ReportHistory::count(),
        'alert_histories_this_month' => AlertHistory::whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->count(),
        'report_histories_this_month' => ReportHistory::whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->count(),
        'total_emails_sent_this_month' => $totalEmailsSentThisMonth
    ]);

    // Vérifier si on a des données réelles, sinon générer des données de démonstration
    $hasRealData = $alertQuery->count() > 0 || $reportQuery->count() > 0;
    
    if (!$hasRealData) {
        // Générer des données de démonstration pour le mois en cours
        $alertQuery = collect();
        $reportQuery = collect();
        
        $daysInMonth = date('t'); // Nombre de jours dans le mois
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $day = sprintf('%02d', $i); // Format 01, 02, etc.
            $alertQuery->push((object)['day' => $day, 'count' => rand(0, 8)]);
            $reportQuery->push((object)['day' => $day, 'count' => rand(0, 5)]);
        }
    }

    // Générer la liste des jours du mois en cours
    $days = [];
    $daysInMonth = date('t'); // Nombre de jours dans le mois
    for ($i = 1; $i <= $daysInMonth; $i++) {
        $days[] = sprintf('%02d', $i); // Format 01, 02, etc.
    }

    // Indexer les résultats par jour
    $alertCounts = $alertQuery->pluck('count', 'day')->all();
    $reportCounts = $reportQuery->pluck('count', 'day')->all();

    $alertsSeries = [];
    $reportsSeries = [];
    foreach ($days as $day) {
        $alertsSeries[] = (int) ($alertCounts[$day] ?? 0);
        $reportsSeries[] = (int) ($reportCounts[$day] ?? 0);
    }

    $emailsEvolution = [
        'labels' => $days,
        'series' => [
            [ 'name' => 'Alert Emails', 'data' => $alertsSeries ],
            [ 'name' => 'Report Emails', 'data' => $reportsSeries ]
        ]
    ];

    // Alert Types Distribution Donut Chart
    $alertTypesResult = DB::table('alerts')
      ->join('type_alerts', 'alerts.type_id', '=', 'type_alerts.id')
      ->select('type_alerts.name', DB::raw('count(*) as total'))
      ->groupBy('type_alerts.name')
      ->get();

    $totalAlertsForPercentage = $alertTypesResult->sum('total');
    $alertTypes = [
        'labels' => $alertTypesResult->pluck('name'),
        'series' => $alertTypesResult->map(function ($item) use ($totalAlertsForPercentage) {
            return $totalAlertsForPercentage > 0 ? round(($item->total / $totalAlertsForPercentage) * 100) : 0;
        }),
    ];

    // Email Types Distribution (nouveau graphique)
    $emailTypesData = [
        'alert_emails' => AlertHistory::where(function($q) {
            $q->where('status', 1)  // encours
              ->orWhere('status', 3)  // completed
              ->orWhere('status', 'sent')  // sent
              ->orWhere('attempts', '>', 0);
        })->count(),
        'report_emails' => ReportHistory::where('status', 'sent')->count()
    ];

    $totalEmails = $emailTypesData['alert_emails'] + $emailTypesData['report_emails'];
    
    // Debug: Log les données d'emails pour comprendre d'où viennent les 100%
    \Log::info('Email Types Distribution Debug', [
        'alert_emails_count' => $emailTypesData['alert_emails'],
        'report_emails_count' => $emailTypesData['report_emails'],
        'total_emails' => $totalEmails,
        'alert_percentage' => $totalEmails > 0 ? round(($emailTypesData['alert_emails'] / $totalEmails) * 100) : 0,
        'report_percentage' => $totalEmails > 0 ? round(($emailTypesData['report_emails'] / $totalEmails) * 100) : 0,
        'alert_history_total' => AlertHistory::count(),
        'report_history_total' => ReportHistory::count(),
        'alert_statuses' => AlertHistory::select('status')->distinct()->pluck('status')->toArray(),
        'report_statuses' => ReportHistory::select('status')->distinct()->pluck('status')->toArray(),
    ]);

    $emailTypesDistribution = [
        'labels' => ['Alert Emails', 'Report Emails'],
        'series' => $totalEmails > 0 ? [
            round(($emailTypesData['alert_emails'] / $totalEmails) * 100),
            round(($emailTypesData['report_emails'] / $totalEmails) * 100)
        ] : [0, 0]
    ];

    // Emails envoyés récents (nouvelle section)
    $recentEmails = $this->getRecentEmails();

    return view('content.dashboard.dashboards-analytics', compact(
      'totalReports',
      'totalAlerts',
      'totalUpcomingAlerts',
      'totalAlertType',
      'emailsSentToday',
      'emailsEvolution',
      'alertTypes',
      'totalAlertsForPercentage',
      'totalEmailsSentThisMonth',
      'recentEmails',
      'emailTypesDistribution',
      'emailTypesData'
    ));
  }

  /**
   * Get the total number of orders from Valomnia API
   */
  public function getOrdersCount($user_id, $organisation, $cookies)
  {
    $api_call = new \App\Models\ApiCall(true, $user_id);
    $url_api = str_replace("organisation", $organisation, env('URL_API', 'https://organisation.valomnia.com'));
    $response = $api_call->GetResponse($url_api . '/api/v2.1/orders', 'GET', [], false, "JSESSIONID=" . $cookies);
    $orders = json_decode($response, true);
    if (isset($orders['data']) && is_array($orders['data'])) {
      return count($orders['data']);
    }
    return 0;
  }

    public function indexOrganisation()
    {
        // Get the current user from session
        $user = session('user');
        
        if (!$user) {
            return redirect()->route('auth-login')->with('error', 'Session expired.');
        }

        // OPTIMIZATION: Use DashboardCacheService for persistent caching
        $dashboardService = new DashboardCacheService($user);
        
        // Check if user wants to force refresh
        $forceRefresh = request()->get('refresh', false);
        
        if ($forceRefresh) {
            $dashboardData = $dashboardService->refreshDashboardData();
        } else {
            $dashboardData = $dashboardService->getDashboardData();
        }

        // Extract data from cached structure
        $metrics = $dashboardData['metrics'];
        $chartData = $dashboardData['chartData'];
        $lists = $dashboardData['lists'];

        // Prepare data for view
        $dashboardData = [
            'total_revenue' => $metrics['total_revenue'],
            'total_clients' => $metrics['total_clients'],
            'average_sales' => $metrics['average_sales'],
            'total_orders' => $metrics['total_orders'],
            'total_quantities' => $metrics['total_quantities'],
            'top_selling_items' => [],
            'growth_percentage' => $metrics['growth_percentage'],
            'payments' => $metrics['payments'],
            'transactions' => $metrics['transactions'],
            'profile_revenue' => $metrics['profile_revenue'],
            'profile_growth' => $metrics['profile_growth'],
            'order_statistics' => [
                'total_orders' => $metrics['total_orders'],
                'electronic' => round($metrics['total_orders'] * 0.4),
                'fashion' => round($metrics['total_orders'] * 0.3),
                'decor' => round($metrics['total_orders'] * 0.2),
                'sports' => round($metrics['total_orders'] * 0.1)
            ],
            'expense_overview' => [
                'total_balance' => $metrics['total_revenue'] * 0.12,
                'growth_percentage' => rand(40, 50)
            ],
            'emails_sent' => rand(50, 200),
            'email_templates' => $metrics['email_templates'],
            'email_success_rate' => rand(85, 98),
            'total_emails' => rand(60, 250),
            'alert_sent' => $metrics['alert_sent'],
            'alert_failed' => $metrics['alert_failed'],
            'alert_success_rate' => $metrics['alert_success_rate'],
            'report_sent' => $metrics['report_sent'],
            'report_failed' => $metrics['report_failed'],
            'report_success_rate' => $metrics['report_success_rate'],
            'template_count' => $metrics['template_count'],
            'activity_feed' => $this->generateActivityFeed($user),
            'lastUpdated' => $dashboardData['lastUpdated'],
            'cacheExpires' => $dashboardData['cacheExpires'],
        ];

        // Prepare lists for view
        $ordersList = [];
        foreach ($lists['orders'] as $order) {
            $ordersList[] = [
                'reference' => $order['reference'] ?? '',
                'customer' => $order['customer']['name'] ?? '',
                'total' => $order['total'] ?? '',
                'status' => $order['status'] ?? '',
            ];
        }

        $itemsList = $lists['items'];
        $topEmployeesList = $chartData['topEmployeesList'];
        $recentCategories = collect($lists['categories'])
            ->map(function($cat) {
                return [
                    'name' => $cat['name'] ?? 'Unknown',
                    'date' => isset($cat['dateCreated']) ? \Carbon\Carbon::parse($cat['dateCreated'])->format('Y-m-d') : ''
                ];
            })->values()->all();

        // Chart data
        $statusLabels = $chartData['statusLabels'];
        $statusData = $chartData['statusData'];
        $emailsEvolution = $chartData['emailsEvolution'];
        $revenueHistoryLabels = $chartData['revenueHistory']['labels'];
        $revenueHistoryData = $chartData['revenueHistory']['data'];
        $ordersHistoryLabelsDay = $chartData['ordersHistory']['day']['labels'];
        $ordersHistoryDataDay = $chartData['ordersHistory']['day']['data'];
        $ordersHistoryLabelsMonth = $chartData['ordersHistory']['month']['labels'];
        $ordersHistoryDataMonth = $chartData['ordersHistory']['month']['data'];

        // Mock data for remaining charts
        $topItemsLabels = [];
        $topItemsData = [];
        $mainLabels = ['Category 1', 'Category 2', 'Category 3'];
        $mainData = [30, 25, 20];
        $clientsHistoryLabels = [];
        $clientsHistoryData = [];
        for ($i = 29; $i >= 0; $i--) {
            $clientsHistoryLabels[] = now()->subDays($i)->format('Y-m-d');
            $clientsHistoryData[] = rand(0, 5);
        }

        $alertsCreatedEvolution = ['labels' => $emailsEvolution['labels'], 'data' => $emailsEvolution['series'][0]['data']];
        $reportsCreatedEvolution = ['labels' => $emailsEvolution['labels'], 'data' => $emailsEvolution['series'][1]['data']];

        // Add missing variables for the view
        $topProductLabels = ['Product 1', 'Product 2', 'Product 3', 'Product 4', 'Product 5'];
        $topProductData = [rand(10, 50), rand(10, 50), rand(10, 50), rand(10, 50), rand(10, 50)];
        $allAlerts = \App\Models\Alert::with('type')->get();
        $allReports = \App\Models\Report::all();
        $categories = $lists['categories'];

        // Store in session for PDF export
        session([
            'dashboardData' => $dashboardData,
            'itemsList' => $itemsList,
            'recentCategories' => $recentCategories,
            'statusLabels' => $statusLabels,
            'statusData' => $statusData,
        ]);

        return view('content.dashboard.dashboards-organisation', compact(
            'dashboardData',
            'revenueHistoryLabels',
            'revenueHistoryData',
            'topItemsLabels',
            'topItemsData',
            'ordersList',
            'clientsHistoryLabels',
            'clientsHistoryData',
            'ordersHistoryLabelsDay',
            'ordersHistoryDataDay',
            'ordersHistoryLabelsMonth',
            'ordersHistoryDataMonth',
            'statusLabels',
            'statusData',
            'topEmployeesList',
            'topProductLabels',
            'topProductData',
            'itemsList',
            'emailsEvolution',
            'allAlerts',
            'allReports',
            'alertsCreatedEvolution',
            'reportsCreatedEvolution',
            'recentCategories',
            'mainLabels',
            'mainData',
            'categories'
        ));
    }

    /**
     * Generate activity feed
     */
    private function generateActivityFeed($user)
    {
        $activityFeed = [];
        
        // Get recent reports created
        $recentReports = \App\Models\Report::where('user_id', $user->id)->latest()->take(3)->get();
        foreach ($recentReports as $report) {
            $activityFeed[] = [
                'date' => $report->created_at,
                'message' => 'New report generated with revenue of <strong>' . number_format($report->total_revenue, 2) . ' TND</strong>.',
                'type' => 'report'
            ];
        }

        // Sort and limit
        usort($activityFeed, function($a, $b) {
            return $b['date'] <=> $a['date'];
        });
        
        return array_slice($activityFeed, 0, 5);
    }

  public function downloadReport()
  {
    $user = session('user');
    // Use the same data as the dashboard view if available in session
    $dashboardData = session('dashboardData', [
        'total_revenue' => 0,
        'total_orders' => 0,
        'total_clients' => 0,
    ]);
    $itemsList = session('itemsList', []);
    $recentCategories = session('recentCategories', []);
    $statusLabels = session('statusLabels', []);
    $statusData = session('statusData', []);
    // Pass all to the PDF view
    $pdf = PDF::loadView('content.dashboard.report-pdf', compact('dashboardData', 'itemsList', 'recentCategories', 'statusLabels', 'statusData'));
    return $pdf->download('dashboard-report.pdf');
  }

  /**
   * AJAX endpoint to refresh dashboard data
   */
  public function refreshData()
  {
    $user = session('user');
    
    if (!$user) {
        return response()->json(['error' => 'Session expired'], 401);
    }

    $dashboardService = new DashboardCacheService($user);
    $data = $dashboardService->refreshDashboardData();

    return response()->json([
        'success' => true,
        'data' => $data,
        'message' => 'Dashboard data refreshed successfully'
    ]);
  }

  /**
   * AJAX endpoint to get cached data only
   */
  public function getCachedData()
  {
    $user = session('user');
    
    if (!$user) {
        return response()->json(['error' => 'Session expired'], 401);
    }

    $dashboardService = new DashboardCacheService($user);
    $data = $dashboardService->getCachedData();

    if (!$data) {
        return response()->json(['error' => 'No cached data available'], 404);
    }

    return response()->json([
        'success' => true,
        'data' => $data,
        'message' => 'Cached data retrieved successfully'
    ]);
  }

  /**
   * Get recent emails sent (alerts and reports)
   */
  private function getRecentEmails()
  {
    // Récupérer les alertes envoyées récemment
    $alertEmails = AlertHistory::with(['alert.type', 'user'])
        ->where(function($q) {
            $q->where('status', 1)  // encours
              ->orWhere('status', 3)  // completed
              ->orWhere('status', 'sent')  // sent
              ->orWhere('attempts', '>', 0);
        })
        ->latest()
        ->take(10)
        ->get()
        ->map(function($item) {
            return [
                'type' => 'Alert',
                'title' => $item->alert->title ?? 'N/A',
                'alert_type' => $item->alert->type->name ?? 'N/A',
                'recipient' => $item->user->email ?? 'N/A',
                'status' => $this->getAlertStatusText($item->status),
                'status_class' => $this->getAlertStatusClass($item->status),
                'attempts' => $item->attempts ?? 0,
                'sent_at' => $item->created_at,
                'response' => $item->response ?? null
            ];
        });

    // Récupérer les rapports envoyés récemment
    $reportEmails = ReportHistory::with(['report.user'])
        ->where('status', 'sent')
        ->latest()
        ->take(10)
        ->get()
        ->map(function($item) {
            return [
                'type' => 'Report',
                'title' => 'Report #' . $item->report_id,
                'alert_type' => 'Monthly Report',
                'recipient' => $item->report->users_email ?? 'N/A',
                'status' => 'Sent',
                'status_class' => 'success',
                'attempts' => $item->attempts ?? 1,
                'sent_at' => $item->created_at,
                'response' => null
            ];
        });

    // Combiner et trier par date
    $allEmails = $alertEmails->concat($reportEmails)
        ->sortByDesc('sent_at')
        ->take(15);

    return $allEmails;
  }

  /**
   * Get alert status text
   */
  private function getAlertStatusText($status)
  {
    switch($status) {
        case 0: return 'Pending';
        case 1: return 'In Progress';
        case 2: return 'Failed';
        case 3: return 'Completed';
        case 'sent': return 'Sent';
        default: return 'Unknown';
    }
  }

  /**
   * Get alert status class for styling
   */
  private function getAlertStatusClass($status)
  {
    switch($status) {
        case 0: return 'warning';
        case 1: return 'info';
        case 2: return 'danger';
        case 3: return 'success';
        case 'sent': return 'success';
        default: return 'secondary';
    }
  }
}