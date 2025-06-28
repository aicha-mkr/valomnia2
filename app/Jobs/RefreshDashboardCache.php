<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\DashboardCacheService;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class RefreshDashboardCache implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct($userId = null)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            if ($this->userId) {
                // Refresh cache for specific user
                $user = User::find($this->userId);
                if ($user) {
                    $dashboardService = new DashboardCacheService($user);
                    $dashboardService->refreshDashboardData();
                    Log::info("Dashboard cache refreshed for user: {$user->id}");
                }
            } else {
                // Refresh cache for all active users
                $users = User::where('active', true)->get();
                foreach ($users as $user) {
                    try {
                        $dashboardService = new DashboardCacheService($user);
                        $dashboardService->refreshDashboardData();
                        Log::info("Dashboard cache refreshed for user: {$user->id}");
                    } catch (\Exception $e) {
                        Log::error("Failed to refresh dashboard cache for user {$user->id}: " . $e->getMessage());
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("Dashboard cache refresh job failed: " . $e->getMessage());
            throw $e;
        }
    }
} 