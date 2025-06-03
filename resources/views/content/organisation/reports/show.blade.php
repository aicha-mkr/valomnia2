@extends('layouts.contentNavbarLayout')
@section('title', 'View Report')

@section('content')
  <div class="container mt-4">
    <h4 class="py-3 mb-4">
      <span class="text-muted fw-light">Reports /</span> View
    </h4>

    <div class="card">
      <div class="card-body">
        <h5>Report Details</h5>
        <p><strong>User:</strong> {{ $report->user ? $report->user->name : 'N/A' }}</p>
        <p><strong>Start Date:</strong> {{ $report->startDate->format('Y-m-d') }}</p>
        <p><strong>End Date:</strong> {{ $report->endDate->format('Y-m-d') }}</p>
        <p><strong>Selected KPIs:</strong> {{ implode(', ', array_map(fn($kpi) => ucwords(str_replace('_', ' ', $kpi)), $kpis)) }}</p>
        <p><strong>Emails:</strong> {{ $report->users_email }}</p>
        <p><strong>Schedule:</strong> {{ ucfirst($report->schedule) }}</p>
        <p><strong>Time:</strong> {{ $report->time ?? 'N/A' }}</p>
        <p><strong>Status:</strong> {{ $report->status ? 'Active' : 'Inactive' }}</p>

        <a href="{{ route('organisation-reports') }}" class="btn btn-outline-secondary">Back</a>
      </div>
    </div>
  </div>
@endsection
