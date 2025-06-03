@extends('layouts.contentNavbarLayout')

@section('title', 'Report List')

@section('page-style')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <style>
    .card {
      animation: fadeIn 0.5s ease-in;
    }
    .table-responsive {
      animation: fadeInUp 0.5s ease-in;
    }
    .table tr {
      transition: all 0.3s ease;
    }
    .table tr:hover {
      background-color: #f8f9fa;
      transform: scale(1.01);
    }
    .badge {
      margin: 2px;
    }
    .date-range-cell {
      text-align: center; /* Center-aligns the content */
      padding: 10px 20px; /* Adds padding inside the cell (left and right) */
    }

    .date-range-cell div {
      margin: 0 auto; /* Centers the div content */
      overflow-wrap: break-word; /* Prevents text overflow by wrapping */
    }

    .table td:nth-child(2) { /* Date Range column */
      min-width: 200px; /* Ensures the column is wide enough for centered content */
    }
    .table td:nth-child(2) { /* Date Range column */
      min-width: 200px; /* Ensures the column is wide enough for centered content */
    }
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
@endsection

@section('content')
  <h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Report List</span>
  </h4>

  <!-- Bordered Table -->
  <div class="card">
    <h5 class="card-header">List of Reports</h5>
    <div class="card-body">
      @if(Session::has('success'))
        <div class="alert alert-success animate__animated animate__bounceIn">
          {{ Session::get('success') }}
        </div>
      @endif
      @if(Session::has('error'))
        <div class="alert alert-danger animate__animated animate__shakeX">
          {{ Session::get('error') }}
        </div>
      @endif
      <div class="mb-3">
        <a href="{{ route('organisation-reports-create') }}" class="btn btn-warning"><i class="bx bx-plus"></i> Create New Report</a>
      </div>
      <div class="table-responsive text-nowrap">
        <table class="table table-bordered">
          <thead>
          <tr>
            <th>#</th>
            <th>Date Range</th>
            <th>Recipient Emails</th>
            <th>Selected KPIs</th>
            <th>Schedule</th>
            <th>Time</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
          </thead>
          <tbody>
          @if(count($reports) > 0)
            @foreach($reports as $index => $report)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td class="date-range-cell">
                  <div>
                    <strong>Start Date:</strong> {{ $report->startDate ? \Carbon\Carbon::parse($report->startDate)->format('Y-m-d') : 'N/A' }}
                  </div>
                  <br>
                  <div>
                    <strong>End Date:</strong> {{ $report->endDate ? \Carbon\Carbon::parse($report->endDate)->format('Y-m-d') : 'N/A' }}
                  </div>
                </td>
                <td>
                  @if($report->users_email)
                    @if(is_array($report->users_email))
                      {{ implode(', ', $report->users_email) }}
                    @else
                      {{ $report->users_email }}
                    @endif
                  @else
                    N/A
                  @endif
                </td>
                <td>
                  @php
                    $allKpis = [
                      'total_orders' => 'Total Orders',
                      'total_revenue' => 'Total Revenue',
                      'average_sales' => 'Average Sales',
                      'total_quantities' => 'Total Quantities Sold',
                      'total_clients' => 'Total Clients',
                      'top_selling_items' => 'Top Selling Items',
                    ];
                    $selectedKpis = [];
                    if ($report->total_orders !== null) $selectedKpis[] = 'total_orders';
                    if ($report->total_revenue !== null) $selectedKpis[] = 'total_revenue';
                    if ($report->average_sales !== null) $selectedKpis[] = 'average_sales';
                    if ($report->total_quantities !== null) $selectedKpis[] = 'total_quantities';
                    if ($report->total_clients !== null) $selectedKpis[] = 'total_clients';
                    if ($report->top_selling_items !== null) $selectedKpis[] = 'top_selling_items';
                  @endphp
                  @foreach($allKpis as $key => $label)
                    <span class="badge {{ in_array($key, $selectedKpis) ? 'bg-success' : 'bg-secondary' }}">
                        {{ $label }} {{ in_array($key, $selectedKpis) ? '✓' : '' }}
                      </span>
                    <br>
                  @endforeach
                </td>
                <td>{{ ucfirst($report->schedule ?? 'N/A') }}</td>
                <td>{{ $report->time ?? 'N/A' }}</td>
                <td>
                  @if($report->status)
                    <span class="badge bg-label-success">Active</span>
                  @else
                    <span class="badge bg-label-secondary">Inactive</span>
                  @endif
                </td>
                <td>
                  <div class="d-inline-block text-nowrap">
                    <a class="btn btn-sm btn-icon text-warning" href="{{ route('organisation-reports-update', $report->id) }}">
                      <i class="bx bx-edit"></i>
                    </a>
                    <a class="btn btn-sm btn-icon delete-record text-danger" href="{{ route('organisation-reports-destroy', $report->id) }}">
                      <i class="bx bx-trash"></i>
                    </a>
                    <a class="btn btn-sm btn-icon text-primary" href="{{ route('organisation-reports-show', $report->id) }}">
                      <i class="bx bx-show"></i>
                    </a>
                  </div>
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="8">
                <div class="alert alert-warning" role="alert">
                  No record is found
                </div>
              </td>
            </tr>
          @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!--/ Bordered Table -->
@endsection
