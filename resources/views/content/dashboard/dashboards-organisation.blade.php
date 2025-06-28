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
        margin-bottom: 0.75rem !important;
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

    .row {
        margin-bottom: 0.5rem !important;
    }
    .card-body {
        padding-top: 0.75rem !important;
        padding-bottom: 0.75rem !important;
    }
</style>
@endsection

@section('vendor-script')
@vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
@vite('resources/assets/js/dashboards-analytics.js')
<script>
    // Global data variables
    window.ordersHistoryDataDay = @json($ordersHistoryDataDay ?? []);
    window.ordersHistoryLabelsDay = @json($ordersHistoryLabelsDay ?? []);
    window.ordersHistoryDataMonth = @json($ordersHistoryDataMonth ?? []);
    window.ordersHistoryLabelsMonth = @json($ordersHistoryLabelsMonth ?? []);
    window.topItemsData = @json($topItemsData ?? []);

    // Cache management
    let dashboardCache = {
        lastUpdated: '{{ $dashboardData["lastUpdated"] ?? "" }}',
        cacheExpires: '{{ $dashboardData["cacheExpires"] ?? "" }}',
        isStale: false
    };

    // Check if cache is stale
    function checkCacheStatus() {
        if (dashboardCache.cacheExpires) {
            const expiresAt = new Date(dashboardCache.cacheExpires);
            const now = new Date();
            dashboardCache.isStale = now > expiresAt;
            
            if (dashboardCache.isStale) {
                showCacheWarning();
            }
        }
    }

    // Show cache warning
    function showCacheWarning() {
        const warningHtml = `
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bx bx-time me-2"></i>
                <strong>Data may be outdated!</strong> 
                Last updated: ${new Date(dashboardCache.lastUpdated).toLocaleString()}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        const container = document.querySelector('.container-fluid');
        if (container) {
            container.insertAdjacentHTML('afterbegin', warningHtml);
        }
    }

    // Refresh dashboard data
    function refreshDashboardData() {
        const refreshBtn = document.getElementById('refreshBtn');
        const originalText = refreshBtn.innerHTML;
        
        refreshBtn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i>Refreshing...';
        refreshBtn.disabled = true;

        fetch('{{ route("dashboard.refresh") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cache info
                dashboardCache.lastUpdated = data.data.lastUpdated;
                dashboardCache.cacheExpires = data.data.cacheExpires;
                dashboardCache.isStale = false;
                
                // Show success message
                showToast('Data refreshed successfully!', 'success');
                
                // Reload page to show new data
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                throw new Error(data.message || 'Failed to refresh data');
            }
        })
        .catch(error => {
            console.error('Error refreshing data:', error);
            showToast('Failed to refresh data. Please try again.', 'error');
        })
        .finally(() => {
            refreshBtn.innerHTML = originalText;
            refreshBtn.disabled = false;
        });
    }

    // Show toast notification
    function showToast(message, type = 'info') {
        const toastHtml = `
            <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        
        const toastContainer = document.getElementById('toastContainer') || createToastContainer();
        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        
        const toast = toastContainer.lastElementChild;
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Remove toast element after it's hidden
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }

    // Create toast container if it doesn't exist
    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
        return container;
    }

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
        // Check cache status on load
        checkCacheStatus();
        
        // Add refresh button event listener
        const refreshBtn = document.getElementById('refreshBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', refreshDashboardData);
        }

        // Animate the main card values
        document.querySelectorAll('.count-up').forEach(el => {
            const endValue = parseInt(el.textContent.replace(/[^0-9]/g, ''), 10);
            if (!isNaN(endValue)) {
                // Keep original text but animate the number
                const prefix = el.textContent.substr(0, el.textContent.search(/[0-9]/));
                const suffix = el.textContent.substr(el.textContent.search(/[a-zA-Z\s%]/));
                el.textContent = prefix + '0' + suffix;
                
                setTimeout(() => {
                    animateValue(el, 0, endValue, 2000);
                }, 500);
            }
        });

        // Initialize the chart with monthly data
        let chart;
        const chartEl = document.querySelector('#totalRevenueChart');
        const options = {
            series: [{
                name: 'Orders',
                data: window.ordersHistoryDataMonth
            }],
            chart: {
                type: 'area',
                height: 300,
                toolbar: { show: false }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            colors: ['#7367F0'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.2,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: window.ordersHistoryLabelsMonth,
                title: { text: 'Month', style: { fontWeight: 600, fontSize: '15px' } }
            },
            yaxis: { title: { text: 'Orders' } },
            tooltip: {
                y: { formatter: function(val) { return val + ' orders'; } }
            },
            grid: { borderColor: '#f0f0f0' }
        };
        chart = new ApexCharts(chartEl, options);
        chart.render();

        // Buttons to change the period
        document.getElementById('showMonthChart').addEventListener('click', function() {
            chart.updateOptions({
                series: [{ name: 'Orders', data: window.ordersHistoryDataMonth }],
                xaxis: { categories: window.ordersHistoryLabelsMonth },
            });
            document.getElementById('ordersChartTitle').textContent = 'Orders per Month (Last 12 Months)';
        });
        document.getElementById('showDayChart').addEventListener('click', function() {
            chart.updateOptions({
                series: [{ name: 'Orders', data: window.ordersHistoryDataDay }],
                xaxis: { categories: window.ordersHistoryLabelsDay },
            });
            document.getElementById('ordersChartTitle').textContent = 'Orders per Day (Last 30 Days)';
        });

        const ordersStatusChartEl = document.querySelector('#ordersStatusChart');
        if (ordersStatusChartEl) {
            const options = {
                series: @json($statusData ?? []),
                labels: @json($statusLabels ?? []),
                chart: { type: 'donut', height: 300 },
                legend: { position: 'bottom' }
            };
            const chart = new ApexCharts(ordersStatusChartEl, options);
            chart.render();
        }
    });
</script>
@endsection

@section('content')
<!-- Download PDF Button under navbar -->
<div class="d-flex justify-content-between align-items-center mb-3">
  <div class="d-flex align-items-center">
    <small class="text-muted me-3">
      <i class="bx bx-time me-1"></i>
      Last updated: {{ $dashboardData['lastUpdated'] ? \Carbon\Carbon::parse($dashboardData['lastUpdated'])->format('M d, Y H:i') : 'Never' }}
    </small>
    @if($dashboardData['cacheExpires'])
      <small class="text-muted">
        <i class="bx bx-refresh me-1"></i>
        Expires: {{ \Carbon\Carbon::parse($dashboardData['cacheExpires'])->format('H:i') }}
      </small>
    @endif
  </div>
  <div class="d-flex gap-2">
    <button id="refreshBtn" class="btn btn-outline-primary btn-sm" onclick="refreshDashboardData()">
      <i class="bx bx-refresh me-1"></i>Refresh Data
    </button>
    <a href="{{ route('dashboard.downloadReport') }}" class="btn btn-primary btn-sm" target="_blank">
      <i class="bx bx-download me-1"></i>Download Report (PDF)
    </a>
  </div>
</div>

<!-- Floating Info Toast (dismissible, fixed position, bottom right, with recent activities) -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080;">
  <div class="toast align-items-center bg-primary text-white border-0 show" id="systemStatusToast" role="alert" aria-live="assertive" aria-atomic="true" style="max-width: 350px; min-width: 260px;">
    <div class="d-flex flex-column w-100">
      <div class="toast-body">
        <div class="d-flex align-items-center mb-2">
          <i class="bx bx-info-circle me-2 text-white"></i>
          <strong>Last Activity in the System</strong>
        </div>
        <ul class="list-unstyled mb-0" style="max-height: 120px; overflow-y: auto;">
          @if(!empty($dashboardData['activity_feed']) && isset($dashboardData['activity_feed'][0]))
            <li class="mb-1 small">
              <span class="text-light">{{ $dashboardData['activity_feed'][0]['date']->diffForHumans() }}:</span>
              <span class="fw-semibold">{!! $dashboardData['activity_feed'][0]['message'] !!}</span>
            </li>
          @else
            <li class="small text-light">No recent activities.</li>
          @endif
        </ul>
      </div>
      <button type="button" class="btn-close btn-close-white ms-auto me-2 mb-2" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>
<!-- End Floating Info Toast -->

<!-- Cards Section -->
<div class="row">
    @php
        $cards = [
            [
                'title' => "CHIFFRE D'AFFAIRES",
                'value' => number_format($dashboardData['total_revenue'], 2, ',', ' ') . ' TND',
                'change' => '',
                'icon' => 'chart'
            ],
            [
                'title' => 'TOTAL CLIENTS',
                'value' => $dashboardData['total_clients'] . ' Clients',
                'change' => '+5.00%',
                'icon' => 'wallet'
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
<!-- Section côte à côte : Alerts & Reports Created (This Month) et Recent Activities -->
<div class="row">
  <div class="col-lg-6 mb-2">
    <div class="card mb-2">
      <div class="card-header">
        <h5 class="card-title mb-0">Alerts & Reports Created (This Month)</h5>
      </div>
      <div class="card-body pt-2">
        <div>
          <div id="alertsReportsCreatedChart" style="min-height: 300px;"></div>
        </div>
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            // Generate labels from 1 to 31 for the X axis
            const labels = Array.from({length: 31}, (_, i) => (i + 1).toString());
            const alertsData = @json($alertsCreatedEvolution['data'] ?? []);
            const reportsData = @json($reportsCreatedEvolution['data'] ?? []);
            const chartEl = document.querySelector('#alertsReportsCreatedChart');
            if (chartEl) {
              const options = {
                series: [
                  { name: 'Alerts Created', data: alertsData },
                  { name: 'Reports Created', data: reportsData }
                ],
                chart: {
                  type: 'bar',
                  height: 300,
                  toolbar: { show: false }
                },
                plotOptions: {
                  bar: { horizontal: false, columnWidth: '60%', barHeight: '80%' }
                },
                colors: ['#FF3E1D', '#696CFF'],
                dataLabels: { enabled: false },
                stroke: { show: true, width: 2, colors: ['transparent'] },
                xaxis: {
                  categories: labels,
                  title: { text: 'Day of Month', style: { fontWeight: 600, fontSize: '15px' } }
                },
                yaxis: { title: { text: 'Created' } },
                fill: { opacity: 0.9 },
                tooltip: {
                  y: { formatter: function(val) { return val + ' created'; } }
                },
                legend: { position: 'top' }
              };
              const chart = new ApexCharts(chartEl, options);
              chart.render();
            }
          });
        </script>
      </div>
    </div>
  </div>
  <div class="col-lg-6 mb-2">
    <div class="card mb-2" style="min-height: 300px;">
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


<!-- Pie chart du nombre d'articles par catégorie (top 10) -->
<div class="card mb-2">
  <div class="card-header">
    <h5 class="m-0 me-2">Articles per Category (Top 10)</h5>
  </div>
  <div class="card-body">
    <div id="articlesPerCategoryPieChart"></div>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Top 10 catégories uniquement
        const labels = @json(array_slice($mainLabels ?? [], 0, 10));
        const data = @json(array_slice($mainData ?? [], 0, 10));
        const chartEl = document.querySelector('#articlesPerCategoryPieChart');
        if (chartEl) {
          const options = {
            series: data,
            labels: labels,
            chart: {
              type: 'pie',
              height: 320
            },
            legend: { position: 'bottom' },
            dataLabels: { enabled: true },
            tooltip: {
              y: { formatter: function(val) { return val + ' items'; } }
            },
            colors: ['#7367F0', '#28C76F', '#FF9F43', '#EA5455', '#00CFE8', '#FF5B5C', '#A3A0FB', '#FFD600', '#BDBDBD', '#00B8D9']
          };
          const chart = new ApexCharts(chartEl, options);
          chart.render();
        }
      });
    </script>
  </div>
</div>
 

<!-- Top 5 selling Items (left, wide) -->
  <div class="col-lg-7 mb-2">
    <div class="card mb-2">
      <div class="card-header">
        <h5 class="m-0 me-2">Top 5 selling Items</h5>
      </div>
      <div class="card-body p-0">
        <ul class="list-group list-group-flush">
          @forelse(array_slice($itemsList, 0, 5) as $item)
            <li class="list-group-item d-flex align-items-center">
              <div>
                <strong>{{ $item['name'] ?? 'N/A' }}</strong>
                <br>
                <span class="text-muted">Ref: {{ $item['reference'] ?? '' }}</span>
                @if(isset($item['itemCategory']['name']))
                  <br><span class="text-muted">Category: {{ $item['itemCategory']['name'] }}</span>
                @endif
              </div>
            </li>
          @empty
            <li class="list-group-item">No items found.</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>

  <!-- Top 4 Active Employees (right, narrow) -->
  <div class="col-lg-5 mb-2">
    <div class="card mb-2 h-100" style="min-height: 370px;">
      <div class="card-header d-flex align-items-center justify-content-between pb-0">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2">Top 4 Active Employees</h5>
          <small class="text-muted">Most active employees (by orders)</small>
        </div>
      </div>
      <div class="card-body">
        <div class="mb-1"><strong>TOP 4 Employees</strong></div>
        <ul class="list-group list-group-flush" style="max-height: none; overflow: visible; margin-bottom: 0;">
          @forelse(array_slice($topEmployeesList, 0, 4) as $employee)
            <li class="list-group-item d-flex align-items-center py-3" style="border: none;">
              <span class="avatar-initial rounded-circle bg-label-primary me-3" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;font-weight:bold;font-size:1.1rem;">
                {{ strtoupper(substr($employee['name'], 0, 2)) }}
              </span>
              <div>
                <div class="fw-semibold">{{ $employee['name'] }}</div>
                <small class="text-muted">Ref: {{ $employee['reference'] }}</small><br>
                <small class="text-muted">Orders: {{ $employee['orders'] }}</small>
              </div>
            </li>
          @empty
            <li class="list-group-item">No employees found.</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="row">
<!-- Orders List Section -->
<div class="row mt-2">
  <div class="col-12">
    <div class="card mt-2">
      <div class="card-header">
        <h5 class="m-0 me-2">Latest Orders</h5>
      </div>
      <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Reference</th>
              <th>Customer</th>
              <th>Total</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($ordersList as $order)
              <tr>
                <td>{{ $order['reference'] }}</td>
                <td>{{ $order['customer'] }}</td>
                <td>{{ number_format($order['total'], 2, ',', ' ') }} TND</td>
                <td>
                  <span class="badge {{ $order['status'] === 'PAID' ? 'bg-label-success' : 'bg-label-warning' }}">
                    {{ $order['status'] }}
                  </span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center">No orders found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
  
  <!-- Row: Order Status Distribution & Top 5 Most Recently Created Categories + Info Popover Card -->
  <div class="row align-items-start">
    <!-- Order Status Distribution (left, wide) -->
    <div class="col-lg-7 mb-2">
      <div class="card mb-2 h-100" style="min-height: 370px;">
        <div class="card-header">
          <h5 class="m-0 me-2">Order Status Distribution</h5>
        </div>
        <div class="card-body">
          <div id="ordersStatusChart"></div>
          <script>
            document.addEventListener('DOMContentLoaded', function() {
              const chartEl = document.querySelector('#ordersStatusChart');
              if (chartEl) {
                const options = {
                  series: @json($statusData ?? []),
                  labels: @json($statusLabels ?? []),
                  chart: { type: 'donut', height: 300 },
                  legend: { position: 'bottom' }
                };
                const chart = new ApexCharts(chartEl, options);
                chart.render();
              }
            });
          </script>
        </div>
      </div>
    </div>
    <!-- Top 5 Most Recently Created Categories (right, narrow) -->
    <div class="col-lg-5 mb-2">
      <div class="card mb-2 h-100" style="min-height: 370px;">
        <div class="card-header d-flex align-items-center justify-content-between pb-0">
          <div class="card-title mb-0 d-flex align-items-center">
            <h5 class="m-0 me-2">Top 5 Most Recently Created Categories</h5>
            <button type="button" class="btn btn-sm btn-outline-info ms-2" data-bs-toggle="modal" data-bs-target="#allCategoriesModal" title="Show all categories">
              <i class="bx bx-info-circle"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <ul class="list-group list-group-flush">
            @forelse($recentCategories as $cat)
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><strong>{{ $cat['name'] }}</strong></span>
                <span class="badge bg-label-info">{{ $cat['date'] }}</span>
              </li>
            @empty
              <li class="list-group-item">No categories found.</li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal: All Categories -->
<div class="modal fade" id="allCategoriesModal" tabindex="-1" aria-labelledby="allCategoriesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="allCategoriesModalLabel">All Categories</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="list-group">
          @forelse($categories as $cat)
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <span><strong>{{ $cat['name'] }}</strong></span>
              <span class="badge bg-label-info">{{ isset($cat['dateCreated']) ? \Carbon\Carbon::parse($cat['dateCreated'])->format('Y-m-d') : '' }}</span>
            </li>
          @empty
            <li class="list-group-item">No categories found.</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
</div>

@endsection
