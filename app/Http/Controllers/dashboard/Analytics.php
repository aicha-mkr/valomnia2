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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Analytics extends Controller
{
  public function index()
  {
    // Cards
    $totalReports = Report::count();
    $totalAlerts = Alert::count();
    $totalUpcomingAlerts = Alert::where('date', '>', now())->count();
    $totalAlertType = TypeAlert::count();

    // Alerts Sent Over Time Chart
    $alertsSentResult = AlertHistory::selectRaw('DATE_FORMAT(created_at, "%b") as month, COUNT(id) as count')
                                    ->whereYear('created_at', date('Y'))
                                    ->groupBy('month')
                                    ->orderByRaw('MIN(created_at)')
                                    ->get();

    $alertsSent = [
      'labels' => $alertsSentResult->pluck('month'),
      'series' => $alertsSentResult->pluck('count'),
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

    // Recent Reports
    $recentReports = Report::with('user')->latest()->take(5)->get();

    return view('content.dashboard.dashboards-analytics', compact(
      'totalReports',
      'totalAlerts',
      'totalUpcomingAlerts',
      'totalAlertType',
      'alertsSent',
      'alertTypes',
      'totalAlertsForPercentage',
      'recentReports'
    ));
  }
    public function indexOrganisation()
    {
        // Get the current user from session
        $user = session('user');
        
        if (!$user) {
            return redirect()->route('auth-login')->with('error', 'Session expired.');
        }

        // Toujours charger le tout dernier rapport généré pour l'utilisateur, même s'il est vide
        $latestReport = \App\Models\Report::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->first();

        // If no report with revenue found, get the most recent report with any data
        if (!$latestReport) {
            $latestReport = \App\Models\Report::where('user_id', $user->id)
                ->where(function($query) {
                    $query->whereNotNull('total_revenue')
                          ->orWhereNotNull('total_orders')
                          ->orWhereNotNull('total_clients');
                })
                ->orderBy('date', 'desc')
                ->first();
        }

        // Debug log
        if ($latestReport) {
            \Log::info('Dashboard - Latest report selected', [
                'report_id' => $latestReport->id,
                'user_id' => $latestReport->user_id,
                'date' => $latestReport->date,
                'total_revenue' => $latestReport->total_revenue,
                'total_orders' => $latestReport->total_orders,
                'total_clients' => $latestReport->total_clients,
                'average_sales' => $latestReport->average_sales
            ]);
        } else {
            \Log::warning('Dashboard - No report found for user', ['user_id' => $user->id]);
        }

        // Fetch the previous report for growth calculation
        $previousReport = \App\Models\Report::where('user_id', $user->id)
            ->where('id', '!=', $latestReport ? $latestReport->id : 0)
            ->whereNotNull('total_revenue')
            ->where('total_revenue', '>', 0)
            ->orderBy('date', 'desc')
            ->first();

        // Calculate real profile growth
        $profileGrowth = 0;
        if ($latestReport && $previousReport && $previousReport->total_revenue > 0) {
            $profileGrowth = round((($latestReport->total_revenue - $previousReport->total_revenue) / $previousReport->total_revenue) * 100, 1);
        }

        // Fetch last 12 reports for the revenue chart
        $revenueHistory = \App\Models\Report::where('user_id', $user->id)
            ->orderBy('date', 'asc')
            ->take(12)
            ->get(['date', 'total_revenue']);
        
        if ($revenueHistory->isEmpty()) {
            // If no data, generate placeholder for the last 7 days
            $revenueHistoryLabels = [];
            $revenueHistoryData = [];
            for ($i = 6; $i >= 0; $i--) {
                $revenueHistoryLabels[] = now()->subDays($i)->format('Y-m-d');
                // Add some variation to the placeholder data
                $revenueHistoryData[] = rand(500, 2000) - ($i * rand(50, 100));
            }
        } else {
            $revenueHistoryLabels = $revenueHistory->map(function($r) { return $r->date ? $r->date->format('Y-m-d') : ''; })->toArray();
            $revenueHistoryData = $revenueHistory->pluck('total_revenue')->toArray();
        }

        // If no report exists, create default values
        if (!$latestReport) {
            $dashboardData = [
                'total_revenue' => 0,
                'total_clients' => 0,
                'average_sales' => 0,
                'total_orders' => 0,
                'total_quantities' => 0,
                'top_selling_items' => [],
                'growth_percentage' => 0,
                'payments' => 0,
                'transactions' => 0,
                'profile_revenue' => 0,
                'profile_growth' => 0,
                'order_statistics' => [
                    'total_orders' => 0,
                    'electronic' => 0,
                    'fashion' => 0,
                    'decor' => 0,
                    'sports' => 0
                ],
                'expense_overview' => [
                    'total_balance' => 0,
                    'growth_percentage' => 0
                ],
                'emails_sent' => 0,
                'email_templates' => \App\Models\EmailTemplate::count(),
                'email_success_rate' => 0,
                'total_emails' => 0,
                'alert_sent' => 0,
                'alert_failed' => 0,
                'alert_success_rate' => 0,
                'report_sent' => 0,
                'report_failed' => 0,
                'report_success_rate' => 0,
                'template_count' => \App\Models\EmailTemplate::count(),
                'activity_feed' => [],
            ];
            
            // Initialize empty arrays for top items when no report exists
            $topItemsLabels = [];
            $topItemsData = [];
        } else {
            // Parse top selling items if it's JSON
            $topSellingItems = [];
            if ($latestReport->top_selling_items) {
                $items = json_decode($latestReport->top_selling_items, true);
                if (is_array($items)) {
                    $topSellingItems = $items;
                }
            }

            // Calculate growth percentage (mock calculation for demo)
            $growthPercentage = rand(5, 25); // In real app, calculate from historical data

            // Calculate additional metrics based on the report data
            $payments = $latestReport->total_revenue * 0.15; // 15% of total revenue
            $transactions = $latestReport->total_orders * 1.5; // 1.5x orders
            $profileRevenue = $latestReport->total_revenue * 0.8; // 80% of total revenue

            // Email-related metrics (mock data for now - replace with real email data)
            $emailsSent = rand(50, 200); // Mock: emails sent this period
            $emailTemplates = \App\Models\EmailTemplate::count(); // Real: count of email templates
            $emailSuccessRate = rand(85, 98); // Mock: email success rate
            $totalEmails = $emailsSent + rand(10, 50); // Mock: total emails (sent + failed)

            // Order statistics breakdown (mock data based on total orders)
            $totalOrders = $latestReport->total_orders ?? 0;
            $orderStatistics = [
                'total_orders' => $totalOrders,
                'electronic' => round($totalOrders * 0.4),
                'fashion' => round($totalOrders * 0.3),
                'decor' => round($totalOrders * 0.2),
                'sports' => round($totalOrders * 0.1)
            ];

            // Expense overview
            $expenseOverview = [
                'total_balance' => $latestReport->total_revenue * 0.12, // 12% of revenue
                'growth_percentage' => rand(40, 50)
            ];

            // Email Alerts
            $alertSent = AlertHistory::whereIn('status', [1, 3])->count();
            $alertFailed = AlertHistory::where('status', 2)->count();
            $alertSuccessRate = ($alertSent + $alertFailed) > 0 ? round($alertSent / ($alertSent + $alertFailed) * 100) : 0;

            // Email Reports
            $reportSent = ReportHistory::where('status', 1)->count();
            $reportFailed = ReportHistory::where('status', 2)->count();
            $reportSuccessRate = ($reportSent + $reportFailed) > 0 ? round($reportSent / ($reportSent + $reportFailed) * 100) : 0;

            // Templates
            $templateCount = EmailTemplate::count();

            // Prepare data for Top Items chart
            $topItemsLabels = [];
            $topItemsData = [];
            if(!empty($topSellingItems)){
                // Sort by revenue desc to be sure
                usort($topSellingItems, function($a, $b) {
                    return $b['revenue'] <=> $a['revenue'];
                });
                foreach($topSellingItems as $item){
                    $topItemsLabels[] = $item['name'] ?? 'Unknown';
                    $topItemsData[] = $item['revenue'] ?? 0;
                }
            }
            
            // --- Build Real Activity Feed ---
            $activityFeed = [];

            // Get recent reports created
            $recentReports = \App\Models\Report::where('user_id', $user->id)->latest()->take(5)->get();
            foreach ($recentReports as $report) {
                $activityFeed[] = [
                    'date' => $report->created_at,
                    'message' => 'New report generated with revenue of <strong>' . number_format($report->total_revenue, 2) . ' TND</strong>.',
                    'type' => 'report'
                ];
            }

            // Get recent alert histories
            $recentAlerts = \App\Models\AlertHistory::with('alert')->latest()->take(5)->get();
            foreach ($recentAlerts as $history) {
                $status = $history->status == 1 ? 'successfully sent' : 'failed to send';
                $activityFeed[] = [
                    'date' => $history->created_at,
                    'message' => 'Email alert "'.($history->alert->name ?? 'N/A').'" was <strong>'.$status.'</strong>.',
                    'type' => 'alert'
                ];
            }
            
            // Get recent report histories
            $recentReportHistories = \App\Models\ReportHistory::where('user_id', $user->id)->latest()->take(5)->get();
            foreach ($recentReportHistories as $history) {
                 $status = $history->status == 1 ? 'successfully sent' : 'failed to send';
                 $activityFeed[] = [
                    'date' => $history->created_at,
                    'message' => 'A report was <strong>'.$status.'</strong> to <strong>'.$history->email_to.'</strong>.',
                    'type' => 'report_history'
                ];
            }

            // Sort all activities by date and take the latest 5
            usort($activityFeed, function($a, $b) {
                return $b['date'] <=> $a['date'];
            });
            $activityFeed = array_slice($activityFeed, 0, 5);

            $dashboardData = [
                'total_revenue' => $latestReport->total_revenue ?? 0,
                'total_clients' => $latestReport->total_clients ?? 0,
                'average_sales' => $latestReport->average_sales ?? 0,
                'total_orders' => $latestReport->total_orders ?? 0,
                'total_quantities' => $latestReport->total_quantities ?? 0,
                'top_selling_items' => $topSellingItems,
                'growth_percentage' => $growthPercentage,
                'payments' => $payments,
                'transactions' => $transactions,
                'profile_revenue' => $profileRevenue,
                'profile_growth' => $profileGrowth,
                'order_statistics' => $orderStatistics,
                'expense_overview' => $expenseOverview,
                'emails_sent' => $emailsSent,
                'email_templates' => $emailTemplates,
                'email_success_rate' => $emailSuccessRate,
                'total_emails' => $totalEmails,
                'alert_sent' => $alertSent,
                'alert_failed' => $alertFailed,
                'alert_success_rate' => $alertSuccessRate,
                'report_sent' => $reportSent,
                'report_failed' => $reportFailed,
                'report_success_rate' => $reportSuccessRate,
                'template_count' => $templateCount,
                'activity_feed' => $activityFeed,
            ];
        }

        return view('content.dashboard.dashboards-organisation', compact('dashboardData', 'revenueHistoryLabels', 'revenueHistoryData', 'topItemsLabels', 'topItemsData'));
    }
}