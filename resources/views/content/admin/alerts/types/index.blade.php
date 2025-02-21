@extends('layouts.contentNavbarLayout')

@section('title', 'Alert TypeAlerts')

@section('content')

    <!-- Bordered Table -->
    <div class="card">
        <h5 class="card-header">List of Alert types</h5>
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
                <a href="{{ url('admin/alerts/types/create') }}" class="btn btn-primary mb-3"><i class="bx bx-plus"></i>
                    Create New Alert Type</a>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>slug</th>
                        <th>name</th>
                        <th>description</th>
                        <th>created_at</th>
                        <th>status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($typeAlerts) > 0)
                        @foreach($typeAlerts as $index=> $typeAlert)
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td><span class="fw-medium">{{ $typeAlert->slug }}</span></td>
                                <td><span class="fw-medium">{{ $typeAlert->name }}</span></td>
                                <td><span class="fw-medium">{{ $typeAlert->description }}</span></td>
                                <td><span class="fw-medium">{{ date("d-m-Y",strtotime($typeAlert->created_at) )}}</span>
                                </td>
                                <td>
                                    @if($typeAlert->status ==1)
                                        <span class="badge bg-label-success">Active</span>
                                    @else
                                        <span class="badge bg-label-secondary">Inactive</span>
                                    @endif

                                </td>

                                <td>
                                    <div class="d-inline-block text-nowrap">
                                        <a class="btn btn-sm btn-icon text-warning"
                                           href="{{url('/admin/alerts/types/update/'.$typeAlert->id) }}">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <a class="btn btn-sm btn-icon delete-record text-danger"
                                           href="{{ url('/admin/alerts/types/delete/'.$typeAlert->id) }}">
                                            <i class="bx bx-trash"></i>
                                        </a>

                                    </div>

                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">
                                <div class="alert alert-primary" role="alert">
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
