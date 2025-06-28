<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Report</title>
    <style>
        body { font-family: 'Segoe UI', 'DejaVu Sans', Arial, sans-serif; color: #222; background: #f7f9fa; margin: 0; padding: 0; }
        .header-bar {
            background: #007bff;
            color: #fff;
            padding: 24px 32px 18px 32px;
            border-radius: 0 0 18px 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            margin-bottom: 32px;
        }
        h1 { margin: 0; font-size: 2.2rem; letter-spacing: 1px; }
        .section-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            margin: 0 auto 28px auto;
            padding: 28px 32px 18px 32px;
            max-width: 800px;
        }
        h2 {
            color: #007bff;
            font-size: 1.3rem;
            margin-top: 0;
            margin-bottom: 18px;
            border-left: 5px solid #007bff;
            padding-left: 12px;
            letter-spacing: 0.5px;
        }
        .kpi-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 10px;
        }
        .kpi-table th, .kpi-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e9ecef;
        }
        .kpi-table th {
            background: #f1f6fb;
            color: #007bff;
            font-weight: 600;
            border-top: 1px solid #e9ecef;
        }
        .kpi-table tr:last-child td {
            border-bottom: none;
        }
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
        }
        .badge-success { background: #d4edda; color: #218838; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
        .badge-secondary { background: #e2e3e5; color: #383d41; }
        .footer {
            text-align: center;
            color: #888;
            font-size: 13px;
            margin-top: 40px;
            padding-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header-bar">
        <h1>Dashboard Report</h1>
    </div>

    <div class="section-card">
        <h2>Key Performance Indicators</h2>
        <table class="kpi-table">
            <tr><th>Total Revenue</th><td>{{ $dashboardData['total_revenue'] ?? 'N/A' }}</td></tr>
            <tr><th>Total Orders</th><td>{{ $dashboardData['total_orders'] ?? 'N/A' }}</td></tr>
            <tr><th>Total Clients</th><td>{{ $dashboardData['total_clients'] ?? 'N/A' }}</td></tr>
        </table>
    </div>

    <div class="section-card">
        <h2>Top 5 Selling Items</h2>
        <table class="kpi-table">
            <tr><th>Name</th><th>Reference</th><th>Category</th></tr>
            @if(isset($itemsList) && count($itemsList))
                @foreach(array_slice($itemsList, 0, 5) as $item)
                    <tr>
                        <td>{{ $item['name'] ?? 'N/A' }}</td>
                        <td>{{ $item['reference'] ?? 'N/A' }}</td>
                        <td>{{ $item['itemCategory']['name'] ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="3">No items found.</td></tr>
            @endif
        </table>
    </div>

    <div class="section-card">
        <h2>Top 5 Most Recently Created Categories</h2>
        <table class="kpi-table">
            <tr><th>Name</th><th>Date Created</th></tr>
            @if(isset($recentCategories) && count($recentCategories))
                @foreach($recentCategories as $cat)
                    <tr>
                        <td>{{ $cat['name'] ?? 'N/A' }}</td>
                        <td>{{ $cat['date'] ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="2">No categories found.</td></tr>
            @endif
        </table>
    </div>

    <div class="section-card">
        <h2>Order Status Distribution</h2>
        <table class="kpi-table">
            <tr><th>Status</th><th>Count</th></tr>
            @if(isset($statusLabels) && isset($statusData) && count($statusLabels))
                @foreach($statusLabels as $i => $label)
                    <tr>
                        <td>
                            @php
                                $badge = 'badge-secondary';
                                if (strtolower($label) === 'paid') $badge = 'badge-success';
                                elseif (strtolower($label) === 'not_paid') $badge = 'badge-warning';
                                elseif (strtolower($label) === 'deposit') $badge = 'badge-info';
                            @endphp
                            <span class="badge {{ $badge }}">{{ $label }}</span>
                        </td>
                        <td>{{ $statusData[$i] ?? 0 }}</td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="2">No status data found.</td></tr>
            @endif
        </table>
    </div>

    <div class="footer">
        Report generated on {{ date('Y-m-d H:i') }}
    </div>
</body>
</html> 