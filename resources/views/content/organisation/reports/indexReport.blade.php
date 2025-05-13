@extends('layouts/contentNavbarLayout')

@section('title', 'Report List')

@section('content')
  <h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Report List</span>
  </h4>

  <!-- Bordered Table -->
  <div class="card">
    <h5 class="card-header">List of Reports</h5>
    <div class="card-body">
      @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
          {{ Session::get('success') }}
        </div>
      @endif
      @if(Session::has('error'))
        <div class="alert alert-danger" role="alert">
          {{ Session::get('error') }}
        </div>
      @endif
      <div class="mb-3">
        <a href="{{ url('organisation/reports/createReport') }}" class="btn btn-warning"><i class="bx bx-plus"></i>
          Create New Report</a>
      </div>
      <div class="table-responsive text-nowrap">
        <table class="table table-bordered">
          <thead>
          <thead>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Type</th>
            <th>Description</th>
            <th>Every Day</th>
            <th>Time</th>
            <th>Date</th>
            <th>Top Selling Items</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
          </thead>

          </thead>

          <tbody>
          @if(count($reports) > 0)
            @foreach($reports as $index => $report)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td><span class="fw-medium">{{ $report->title }}</span></td>
                <td>{{ $report->type->name ?? 'N/A' }}</td>
                <td>{{ $report->description }}</td>
                <td>
                  @if($report->every_day)
                    <span class="badge badge-center rounded-pill bg-success" title="Triggers Every Day"><i
                        class="bx bx-check"></i></span>
                  @else
                    <span class="badge badge-center rounded-pill bg-danger"
                          title="Triggers on a Specific Date"><i class="bx bx-minus"></i></span>
                  @endif
                </td>
                <td>{{ $report->time ?? 'N/A' }}</td>
                <td>
                  @if(!$report->every_day)
                    {{ $report->date ?? 'N/A' }}
                  @else
                    <span>Every Day</span>
                  @endif
                </td>
                <td>
                  @if($report->status)
                    <span class="badge bg-label-success">Active</span>
                  @else
                    <span class="badge bg-label-secondary">Inactive</span>
                  @endif
                </td>
                <td>{{ $report->quantity ?? 'N/A' }}</td>
                <td>
                  <div class="d-inline-block text-nowrap">
                    <a class="btn btn-sm btn-icon text-warning"
                       href="{{ url('/organisation/reports/update/' . $report->id) }}">
                      <i class="bx bx-edit"></i>
                    </a>
                    <a class="btn btn-sm btn-icon delete-record text-danger"
                       href="{{ url('/organisation/reports/delete/' . $report->id) }}">
                      <i class="bx bx-trash"></i>
                    </a>
                    <a class="btn btn-sm btn-icon text-primary"
                       href="{{ url('/organisation/reports/show/' . $report->id) }}">
                      <i class="bx bx-show"></i>
                    </a>
                  </div>
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="10">
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
