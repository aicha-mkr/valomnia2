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
    const emailsEvolution = @json($emailsEvolution);
    const alertTypesData = @json($alertTypes);
    const alertTypesTotalCount = @json($totalAlertsForPercentage);
    const emailTypesDistribution = @json($emailTypesDistribution);
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

  <!-- Emails Sent Today -->
  <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-danger"><i class='bx bx-envelope'></i></span>
          </div>
        </div>
        <span class="fw-semibold d-block mb-1">Emails Sent Today</span>
        <h3 class="card-title mb-2">{{ $emailsSentToday }}</h3>
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
          <h5 class="m-0 me-2">Email Sent Over Time</h5>
          <small class="text-muted">Total sent: {{ $totalEmailsSentThisMonth }}</small>
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

  <!-- Recent Emails Sent Table -->
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title m-0">Recent Emails Sent</h5>
        <div class="d-flex align-items-center">
          <span class="badge bg-label-primary me-2">Total: {{ $recentEmails->count() }}</span>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead class="table-light">
            <tr>
              <th class="text-nowrap">Type</th>
              <th>Title</th>
              <th>Alert Type</th>
              <th>Recipient</th>
              <th>Status</th>
              <th>Attempts</th>
              <th>Sent At</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentEmails as $email)
            <tr>
              <td>
                <div class="d-flex align-items-center">
                  <div class="avatar avatar-sm me-3">
                    @if($email['type'] === 'Alert')
                      <span class="avatar-initial rounded-circle bg-label-warning">
                        <i class='bx bxs-bell'></i>
                      </span>
                    @else
                      <span class="avatar-initial rounded-circle bg-label-success">
                        <i class='bx bxs-report'></i>
                      </span>
                    @endif
                  </div>
                  <span class="fw-medium">{{ $email['type'] }}</span>
                </div>
              </td>
              <td>
                <div class="d-flex flex-column">
                  <span class="text-nowrap text-heading fw-medium">{{ $email['title'] }}</span>
                  @if($email['response'])
                    <small class="text-muted">{{ Str::limit($email['response'], 50) }}</small>
                  @endif
                </div>
              </td>
              <td>
                <span class="badge bg-label-info">{{ $email['alert_type'] }}</span>
              </td>
              <td>
                <div class="d-flex flex-column">
                  <span class="text-nowrap text-heading fw-medium">{{ $email['recipient'] }}</span>
                </div>
              </td>
              <td>
                <span class="badge bg-label-{{ $email['status_class'] }}">{{ $email['status'] }}</span>
              </td>
              <td>
                <span class="badge bg-label-secondary">{{ $email['attempts'] }}</span>
              </td>
              <td class="text-nowrap">{{ $email['sent_at']->format('M d, Y H:i') }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="text-center py-4">
                <div class="d-flex flex-column align-items-center">
                  <i class='bx bx-envelope-open fs-1 text-muted mb-2'></i>
                  <span class="text-muted">No emails sent yet</span>
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection