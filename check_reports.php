<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Report;
use App\Models\User;
use App\Models\ReportHistory;

echo "Reports in database:\n";
echo "==================\n";

$reports = Report::with('user')->get();
foreach ($reports as $report) {
    echo "ID: {$report->id} - User: {$report->user->name} ({$report->user->organisation}) - Revenue: {$report->total_revenue} TND - Orders: {$report->total_orders} - Date: {$report->date}\n";
}

echo "\nTotal reports: " . $reports->count() . "\n";

echo "\nReport Histories:\n";
echo "================\n";

$histories = ReportHistory::with('user')->get();
foreach ($histories as $history) {
    echo "ID: {$history->id} - User: {$history->user->name} - Status: {$history->status} - Attempts: {$history->attempts}\n";
}

echo "\nTotal report histories: " . $histories->count() . "\n";

// Show reports per user
echo "\nReports per user:\n";
echo "================\n";

$users = User::with('reports')->get();
foreach ($users as $user) {
    echo "{$user->name} ({$user->organisation}): {$user->reports->count()} reports\n";
} 