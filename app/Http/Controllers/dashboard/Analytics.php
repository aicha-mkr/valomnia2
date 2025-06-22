<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\AlertHistory;
use App\Models\Report;
use App\Models\TypeAlert;
use App\Models\User;
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
        return view('content.dashboard.dashboards-organisation');
    }
}