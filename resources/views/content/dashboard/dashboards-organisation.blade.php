@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('vendor-style')
@vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('page-style')
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Apply animation to all columns in the main rows */
    .row > [class*="col-"] {
        opacity: 0; /* Start hidden */
        animation: fadeInUp 0.5s ease-out forwards;
    }

    /* Stagger the animations for a cascading effect */
    .row:first-of-type > [class*="col-"]:nth-child(1) { animation-delay: 0.1s; }
    .row:first-of-type > [class*="col-"]:nth-child(2) { animation-delay: 0.2s; }
    .row:first-of-type > [class*="col-"]:nth-child(3) { animation-delay: 0.3s; }
    .row:first-of-type > [class*="col-"]:nth-child(4) { animation-delay: 0.4s; }
    .row:first-of-type > [class*="col-"]:nth-child(5) { animation-delay: 0.5s; }

    .row:nth-of-type(2) > .col-lg-7 { animation-delay: 0.6s; }
    .row:nth-of-type(2) > .col-lg-5 { animation-delay: 0.7s; }

    /* Pulsing Badge Animation */
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(var(--bs-danger-rgb), 0.7); }
        70% { box-shadow: 0 0 0 8px rgba(var(--bs-danger-rgb), 0); }
        100% { box-shadow: 0 0 0 0 rgba(var(--bs-danger-rgb), 0); }
    }
    .badge-pulse {
        animation: pulse 2s infinite;
    }

    /* Add interactive hover animation to top card icons and cards */
    .card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    .card .avatar img, .card .avatar-initial i, .bx-mobile-alt {
        transition: transform 0.3s ease-in-out;
    }
    .card:hover .avatar img, .card:hover .avatar-initial i, .card:hover .bx-mobile-alt {
        transform: scale(1.15);
    }

    /* Live indicator for chart titles */
    .card-header h5::before {
        content: '';
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: var(--bs-danger);
        margin-right: 10px;
        animation: pulse 2s infinite;
    }
    
    /* --- Recent Activities Feed --- */
    .activity-feed .feed-item {
        position: relative;
        padding-bottom: 20px;
        padding-left: 30px;
        border-left: 2px solid #e9ecef;
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.6s ease-out forwards;
    }
    .activity-feed .feed-item:last-child {
        border-left: none;
    }
    .activity-feed .feed-item::after {
        content: "";
        display: block;
        position: absolute;
        top: 0;
        left: -8px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background-color: var(--bs-primary);
        border: 2px solid #fff;
    }
    .activity-feed .feed-item:nth-child(2)::after { background-color: var(--bs-success); }
    .activity-feed .feed-item:nth-child(3)::after { background-color: var(--bs-info); }
    .activity-feed .feed-item:nth-child(4)::after { background-color: var(--bs-warning); }
    .activity-feed .feed-item:nth-child(5)::after { background-color: var(--bs-danger); }
</style>
@endsection

@section('vendor-script')
@vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
@vite('resources/assets/js/dashboards-analytics.js')
<script>
    // Pass data to the chart
    window.revenueHistoryLabels = @json($revenueHistoryLabels ?? []);
    window.revenueHistoryData = @json($revenueHistoryData ?? []);
    window.topItemsLabels = @json($topItemsLabels ?? []);
    window.topItemsData = @json($topItemsData ?? []);

    // Animation for counting up numbers
    function animateValue(obj, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const current = Math.floor(progress * (end - start) + start);
            obj.innerHTML = current.toLocaleString(); // Format with commas
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Animate the main card values
        document.querySelectorAll('.count-up').forEach(el => {
            const endValue = parseInt(el.textContent.replace(/[^0-9]/g, ''), 10);
            if (!isNaN(endValue)) {
                // Keep original text but animate the number
                const prefix = el.textContent.substr(0, el.textContent.search(/[0-9]/));
                const suffix = el.textContent.substr(el.textContent.search(/[a-zA-Z\s%]/));

                const numOnlyEl = el.querySelector('.value-to-animate')
                if(numOnlyEl){
                    const endVal = parseInt(numOnlyEl.textContent.replace(/[^0-9]/g, ''), 10);
                    animateValue(numOnlyEl, 0, endVal, 1500);
                }
            }
        });
    });
</script>
@endsection

@section('content')
<!-- Cards Section -->
<div class="row">
    @php
        $cards = [
            [
                'title' => 'TOTAL CLIENTS',
                'value' => $dashboardData['total_clients'] . ' Clients',
                'change' => '+5.00%',
                'icon' => 'wallet'
            ],
            [
                'title' => 'AVERAGE SALES',
                'value' => number_format($dashboardData['average_sales'], 2) . ' TND',
                'change' => '+2.00%',
                'icon' => 'wallet-info'
            ],
            [
                'title' => 'TOTAL ORDERS',
                'value' => $dashboardData['total_orders'] . ' Orders',
                'change' => '+3.00%',
                'icon' => 'wallet'
            ],
        ];
    @endphp
    @foreach ($cards as $card)
    <div class="col-xl col-md-6 p-2">
        <div class="card h-100">
            <div class="card-body d-flex flex-column">
                <div class="card-title d-flex align-items-start justify-content-between mb-4">
                    <div class="avatar flex-shrink-0">
                        <img src="{{ asset("assets/img/icons/unicons/{$card['icon']}.png") }}" alt="{{ $card['title'] }}" class="rounded">
                    </div>
                </div>
                <p class="mb-1">{{ $card['title'] }}</p>
                <h4 class="card-title mb-3"><span class="value-to-animate">{{ preg_replace('/[^0-9.]/', '', $card['value']) }}</span> {{ preg_replace('/[0-9.,\s]/', '', $card['value']) }}</h4>
                <small class="text-success fw-medium"><i class='bx bx-up-arrow-alt'></i> {{ $card['change'] }}</small>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Email Alerts Card -->
    <div class="col-xl col-md-6 p-2">
      <div class="card text-center shadow-sm h-100">
        <div class="card-body d-flex flex-column justify-content-center align-items-center">
          <div class="mb-2">
            <span class="avatar-initial rounded bg-label-success p-3"><i class="bx bx-envelope text-success" style="font-size: 2rem;"></i></span>
          </div>
          <h6 class="text-muted mb-1">Email Alerts</h6>
          <h2 class="mb-0 text-success fw-bold"><span class="value-to-animate">{{ $dashboardData['alert_success_rate'] }}</span>%</h2>
          <small class="text-muted">Success Rate</small>
          <div class="d-flex justify-content-center gap-3 mt-2">
            <span class="badge bg-label-success">Sent: {{ $dashboardData['alert_sent'] }}</span>
            <span class="badge bg-label-danger">Failed: {{ $dashboardData['alert_failed'] }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Email Reports Card -->
    <div class="col-xl col-md-6 p-2">
      <div class="card text-center shadow-sm h-100">
        <div class="card-body d-flex flex-column justify-content-center align-items-center">
          <div class="mb-2">
            <span class="avatar-initial rounded bg-label-info p-3"><i class="bx bx-bar-chart text-info" style="font-size: 2rem;"></i></span>
          </div>
          <h6 class="text-muted mb-1">Email Reports</h6>
          <h2 class="mb-0 text-info fw-bold"><span class="value-to-animate">{{ $dashboardData['report_success_rate'] }}</span>%</h2>
          <small class="text-muted">Success Rate</small>
          <div class="d-flex justify-content-center gap-3 mt-2">
            <span class="badge bg-label-info">Sent: {{ $dashboardData['report_sent'] }}</span>
            <span class="badge bg-label-danger">Failed: {{ $dashboardData['report_failed'] }}</span>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="row">
  <!-- Total Revenue -->
  <div class="col-lg-7 mb-4">
    <div class="card mb-4">
      <div class="row row-bordered g-0">
        <div class="col-md-12">
          <h5 class="card-header m-0 me-2 pb-3">Total Revenue</h5>
          <div id="totalRevenueChart" class="px-2"></div>
        </div>
      </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="m-0 me-2">Top Items by Revenue</h5>
        </div>
        <div class="card-body">
            <div id="topItemsChart"></div>
        </div>
    </div>
  </div>
  <!-- /Total Revenue -->

  <!-- Order Statistics -->
  <div class="col-lg-5 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between pb-0">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2">Order Statistics</h5>
          @php
            $topItems = $dashboardData['top_selling_items'] ?? [];
            $totalSales = 0;
            $totalOrders = 0;
            foreach ($topItems as $item) {
              $totalSales += $item['revenue'] ?? 0;
              $totalOrders += $item['quantity'] ?? 0;
            }
          @endphp
          <small class="text-muted">{{ number_format($totalSales, 0) }} Total Sales</small>
        </div>
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="d-flex flex-column align-items-center gap-1">
            <h2 class="mb-2">{{ $totalOrders }}</h2>
            <span>Total Orders</span>
          </div>
        </div>
        <ul class="p-0 m-0">
          @foreach($topItems as $item)
            <li class="d-flex mb-4 pb-1">
              <div class="avatar flex-shrink-0 me-3">
                <span class="avatar-initial rounded bg-label-primary"><i class='bx bx-mobile-alt'></i></span>
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <h6 class="mb-0">{{ $item['name'] ?? 'N/A' }}</h6>
                  <small class="text-muted">Ref: {{ $item['reference'] ?? '' }}</small>
                </div>
                <div class="user-progress d-flex align-items-center gap-3">
                  <span class="fw-medium">Qty: {{ $item['quantity'] ?? 0 }}</span>
                  <span class="fw-medium">Rev: {{ number_format($item['revenue'] ?? 0, 0) }} TND</span>
                </div>
              </div>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
  <!--/ Order Statistics -->
</div>

<!-- Recent Activities -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0 me-2">Recent Activities</h5>
            </div>
            <div class="card-body">
                <div class="activity-feed">
                    @forelse($dashboardData['activity_feed'] as $index => $activity)
                        <div class="feed-item" style="animation-delay: {{ 0.8 + ($index * 0.2) }}s;">
                            <p class="mb-0 text-muted">{{ $activity['date']->diffForHumans() }}</p>
                            <p class="mb-0">{!! $activity['message'] !!}</p>
                        </div>
                    @empty
                        <div class="feed-item" style="animation-delay: 0.8s;">
                             <p class="mb-0 text-muted">No recent activities found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ Recent Activities -->

@endsection
