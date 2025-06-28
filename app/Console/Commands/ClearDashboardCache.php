<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class ClearDashboardCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:clear-cache {--user-id= : Clear cache for specific user} {--all : Clear cache for all users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear dashboard cache for users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user-id');
        $all = $this->option('all');

        if ($userId) {
            // Clear cache for specific user
            $cacheKey = 'dashboard_persistent_' . $userId;
            Cache::forget($cacheKey);
            $this->info("Dashboard cache cleared for user ID: {$userId}");
        } elseif ($all) {
            // Clear cache for all users
            $users = User::all();
            $clearedCount = 0;
            
            foreach ($users as $user) {
                $cacheKey = 'dashboard_persistent_' . $user->id;
                Cache::forget($cacheKey);
                $clearedCount++;
            }
            
            $this->info("Dashboard cache cleared for {$clearedCount} users");
        } else {
            // Clear all dashboard cache keys
            $pattern = 'dashboard_persistent_*';
            $keys = Cache::get($pattern) ?: [];
            
            if (is_array($keys)) {
                foreach ($keys as $key) {
                    Cache::forget($key);
                }
                $this->info("All dashboard cache keys cleared");
            } else {
                $this->warn("No dashboard cache keys found");
            }
        }

        return 0;
    }
} 