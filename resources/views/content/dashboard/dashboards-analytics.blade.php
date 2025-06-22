@extends('layouts/contentNavbarLayout')

@section('title', 'Alerts & Reports Dashboard')

@section('vendor-style')
@vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
@vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
@vite('resources/assets/js/dashboards-reports.js')
@endsection

@section('content')
<script>
    // Pass data from PHP to JavaScript
    const alertsSentData = @json($alertsSent);
    const alertTypesData = @json($alertTypes);
    const alertTypesTotalCount = @json($totalAlertsForPercentage);
</script>

<div class="row">
  <!-- Total Reports -->
  <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-success"><i class='bx bxs-report'></i></span>
          </div>
        </div>
        <span class="fw-semibold d-block mb-1">Total Reports</span>
        <h3 class="card-title mb-2">{{ $totalReports }}</h3>
      </div>
    </div>
  </div>

  <!-- Total Alerts -->
  <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-primary"><i class='bx bxs-bell'></i></span>
          </div>
        </div>
        <span class="fw-semibold d-block mb-1">Total Alerts</span>
        <h3 class="card-title text-nowrap mb-2">{{ $totalAlerts }}</h3>
      </div>
    </div>
  </div>

  <!-- Upcoming Alerts -->
  <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-warning"><i class='bx bx-time-five'></i></span>
          </div>
        </div>
        <span class="fw-semibold d-block mb-1">Upcoming Alerts</span>
        <h3 class="card-title text-nowrap mb-2">{{ $totalUpcomingAlerts }}</h3>
      </div>
    </div>
  </div>

  <!-- Total Alert Types -->
  <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-info"><i class='bx bx-category'></i></span>
          </div>
        </div>
        <span class="fw-semibold d-block mb-1">Alert Types</span>
        <h3 class="card-title mb-2">{{ $totalAlertType }}</h3>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Alert Statistics Chart -->
  <div class="col-lg-8 col-12 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2">Alerts Sent Over Time</h5>
          <small class="text-muted">Total sent: {{ $totalAlerts }}</small>
        </div>
      </div>
      <div class="card-body">
        <div id="alertsSentChart"></div>
      </div>
        </div>
          </div>

  <!-- Alert Types Distribution Donut Chart -->
  <div class="col-lg-4 col-12 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h5 class="card-title mb-0">Alert Types Distribution</h5>
        </div>
      <div class="card-body">
        <div id="alertTypeChart"></div>
      </div>
    </div>
  </div>

  <!-- Recent Reports Table -->
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title m-0">Recent Reports</h5>
      </div>
          <div class="table-responsive">
        <table class="table">
          <thead class="table-light">
            <tr>
              <th class="text-nowrap">User</th>
              <th>Report Date</th>
              <th>Orders</th>
              <th>Revenue</th>
              <th>Status</th>
                </tr>
              </thead>
              <tbody>
            @forelse($recentReports as $report)
            <tr>
              <td>
                <div class="d-flex justify-content-start align-items-center">
                      <div class="avatar-wrapper">
                    <div class="avatar avatar-sm me-3">
                      <span class="avatar-initial rounded-circle bg-label-success">{{ substr($report->user->name ?? 'U', 0, 1) }}</span>
                        </div>
                      </div>
                      <div class="d-flex flex-column">
                    <span class="text-nowrap text-heading fw-medium">{{ $report->user->name ?? 'N/A' }}</span>
                    <small class="text-muted">{{ $report->user->email ?? '' }}</small>
                      </div>
                    </div>
                  </td>
              <td class="text-nowrap">{{ $report->date->format('M d, Y') }}</td>
              <td>{{ $report->total_orders }}</td>
              <td class="text-nowrap">{{ number_format($report->total_revenue, 2, '.', ',') }} TND</td>
              <td><span class="badge bg-label-{{ $report->status ? 'success' : 'warning' }}">{{ $report->status ? 'Finished' : 'Pending' }}</span></td>
                </tr>
            @empty
            <tr>
              <td colspan="5" class="text-center py-4">No recent reports found.</td>
                </tr>
            @endforelse
              </tbody>
            </table>
      </div>
    </div>
  </div>
</div>
@endsection