<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\User;
use App\Models\ReportHistory;
use Illuminate\Support\Str;

class ReportSeeder extends Seeder
{
    public function run()
    {
        \DB::table('reports')->delete();
        \DB::table('report_histories')->delete();

        $users = User::all();

        $topItems = [
            [
                'reference' => 'WWF201',
                'name' => 'HC8 WWF Puzzle animaux des formes #',
                'quantity' => rand(10, 100),
                'revenue' => rand(100, 1000)
            ],
            [
                'reference' => 'WWF084',
                'name' => 'HC8 WWF 1000 pieces puzzle - Tigres #',
                'quantity' => rand(10, 100),
                'revenue' => rand(100, 1000)
            ],
            [
                'reference' => 'SP9001',
                'name' => 'Speedy Monkey - Tableau ajustable 3 en 1 ...',
                'quantity' => rand(10, 100),
                'revenue' => rand(100, 1000)
            ],
            [
                'reference' => 'SP7001',
                'name' => 'Speedy Monkey - Draisienne - 82x35,5x55c ...',
                'quantity' => rand(10, 100),
                'revenue' => rand(100, 1000)
            ],
            [
                'reference' => 'SP5004',
                'name' => 'Speedy Monkey - Ukulele - 41x4,5x15cm - ...',
                'quantity' => rand(10, 100),
                'revenue' => rand(100, 1000)
            ],
        ];

        foreach ($users as $user) {
            for ($i = 0; $i < 5; $i++) {
                $totalOrders = rand(5, 30);
                $totalRevenue = rand(500, 5000); // en TND
                $totalQuantities = rand(50, 500);
                $totalClients = rand(3, 20);
                $averageSales = $totalRevenue / max($totalOrders, 1);
                $date = now()->subDays(rand(0, 30));
                $startDate = $date->copy()->subDays(rand(1, 5));
                $endDate = $date->copy()->addDays(rand(1, 5));
                $schedule = ['none', 'daily', 'weekly', 'monthly'][rand(0, 3)];
                $usersEmail = json_encode([
                    ['value' => 'mokhtaraichaa@example.com'],
                    ['value' => 'thabtiissam7@example.com']
                ]);
                $time = $date->format('H:i');
                $status = rand(0, 1);

                $report = Report::create([
                    'user_id' => $user->id,
                    'date' => $date,
                    'total_orders' => $totalOrders,
                    'total_revenue' => $totalRevenue,
                    'average_sales' => round($averageSales, 2),
                    'total_quantities' => $totalQuantities,
                    'total_clients' => $totalClients,
                    'top_selling_items' => json_encode($topItems),
                    'created_at' => $date->copy()->subMinutes(rand(1, 60)),
                    'updated_at' => $date->copy()->addMinutes(rand(1, 60)),
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'schedule' => $schedule,
                    'users_email' => $usersEmail,
                    'time' => $time,
                    'status' => $status,
                ]);

                // Create some report history entries for testing
                if ($i < 2) { // Only create history for first 2 reports per user
                    ReportHistory::create([
                        'report_id' => $report->id,
                        'user_id' => $user->id,
                        'status' => ['sent', 'failed', 'pending'][rand(0, 2)],
                        'attempts' => rand(0, 3),
                        'created_at' => $date->copy()->addMinutes(rand(1, 60)),
                        'updated_at' => $date->copy()->addMinutes(rand(1, 60)),
                    ]);
                }
            }
        }
    }
} 