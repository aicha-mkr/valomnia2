<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{


    public function index()
    {
        $userCount = User::count();
        $orderCount = Order::count();
        $recentOrders = Order::latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();

        $userRegistrations = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('admin.index', compact('userCount', 'orderCount', 'recentOrders', 'recentUsers', 'userRegistrations'));
    }
    public function fetchRecentOrders()
    {
        $recentOrders = Order::latest()->take(5)->get();
        return response()->json($recentOrders);
    }
}