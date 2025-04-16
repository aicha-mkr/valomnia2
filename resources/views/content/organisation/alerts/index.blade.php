@extends('layouts/contentNavbarLayout')

@section('title', 'Alert Alert List')

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Alert List</span>
</h4>

<!-- Bordered Table -->
<div class="card">
    <h5 class="card-header">List of Alert</h5>
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
            <a href="{{ url('organisation/alerts/create') }}" class="btn btn-primary mb-3"><i class="bx bx-plus"></i>
                Create New Alert </a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Every Day</th>
                        <th>Trigger Time</th>
                        <th>Trigger Date</th>
                        <th>Status</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @if(count($alerts) > 0)
                    @foreach($alerts as $index => $alert)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="fw-medium">{{ $alert->title }}</span></td>
                        <td>{{ $alert->type->name ?? 'N/A' }}</td> <!-- Assuming type relation exists -->
                        <td>{{ $alert->description }}</td>
                        <td>
                            @if($alert->every_day)
                            <span class="badge badge-center rounded-pill bg-success" title="Triggers Every Day"><i
                                    class="bx bx-check"></i></span>
                            @else
                            <span class="badge badge-center rounded-pill bg-danger"
                                title="Triggers on a Specific Date"><i class="bx bx-minus"></i></span>
                            @endif
                        </td>
                        <td>
                            @if($alert->every_day)
                            {{ $alert->time ?? 'N/A' }}
                            <!-- Show time when "Every Day" is checked -->
                            @else
                            {{ $alert->time ?? 'N/A' }}
                            <!-- Show time for specific date -->
                            @endif
                        </td>
                        <td>
                            @if(!$alert->every_day)
                            {{ $alert->date ?? 'N/A' }}
                            <!-- Show date when not "Every Day" -->
                            @else
                            <span>Every Day</span>
                            @endif
                        </td>
                        <td>
                            @if($alert->status)
                            <span class="badge bg-label-success">Active</span>
                            @else
                            <span class="badge bg-label-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $alert->quantity ?? 'N/A' }}</td>
                        <td>
                            <div class="d-inline-block text-nowrap">
                                <a class="btn btn-sm btn-icon text-warning"
                                    href="{{ url('/organisation/alerts/update/' . $alert->id) }}">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <a class="btn btn-sm btn-icon delete-record text-danger"
                                    href="{{ url('/organisation/alerts/delete/' . $alert->id) }}">
                                    <i class="bx bx-trash"></i>
                                </a>
                                <a class="btn btn-sm btn-icon text-primary"
                                    href="{{ url('/organisation/alerts/show/' . $alert->id) }}">
                                    <i class="bx bx-show"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="10">
                            <div class="alert alert-primary" role="alert">
                                No record is found
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>


        </div>
    </div>
</div>
<!--/ Bordered Table -->
@endsection
