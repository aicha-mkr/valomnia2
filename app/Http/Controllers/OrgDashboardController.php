<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;


class OrgDashboardController extends Controller
{
    public function indexOrg()
    {
        $userCount = User::where('organization_id', auth()->user()->organization_id)->count();
        $activityCount = Activity::where('organization_id', auth()->user()->organization_id)->count();
        $recentActivities = Activity::where('organization_id', auth()->user()->organization_id)->latest()->take(5)->get();
        $recentUsers = User::where('organization_id', auth()->user()->organization_id)->latest()->take(5)->get();

        $userRegistrations = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('organization_id', auth()->user()->organization_id)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('org-dashboard.index', compact('userCount', 'activityCount', 'recentActivities', 'recentUsers', 'userRegistrations'));
    }

    public function fetchRecentActivities()
    {
        $recentActivities = Activity::where('organization_id', auth()->user()->organization_id)->latest()->take(5)->get();
        return response()->json($recentActivities);
    }
}