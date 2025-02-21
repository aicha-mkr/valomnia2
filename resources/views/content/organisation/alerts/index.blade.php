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
                <a href="{{ url('organisation/alerts/create') }}" class="btn btn-primary mb-3"><i
                        class="bx bx-plus"></i> Create New Alert </a>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>title</th>
                        <th>Type</th>
                        <th>description</th>
                        <th>Every Day</th>
                        <th>date</th>
                        <th>Status</th>
                        <th>quantity</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($alerts) > 0)
                        @foreach($alerts as $index=> $alert)
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td><span class="fw-medium">{{ $alert->title }}</span></td>

                                <td>{{ $alert->description }}</td>
                                <td>
                                    @if($alert->every_day ==1)
                                        <span class="badge badge-center rounded-pill bg-success"><i class="bx bx-check"></i></span>
                                    @else
                                        <span class="badge badge-center rounded-pill bg-danger"><i class="bx bx-minus"></i></span>
                                    @endif

                                </td>
                                <td>
                                @if($alert->time === null)
                                  <span>{{ $alert->date }}</span>
                                @else
                                <span>{{ $alert->time }}</span>
                                @endif
                                </td>

                                <td>
                                    @if($alert->status ==1)
                                        <span class="badge bg-label-success">Active</span>
                                    @else
                                        <span class="badge bg-label-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $alert->quantity }}</td>
                                <td>
                                    <div class="d-inline-block text-nowrap">
                                        <a class="btn btn-sm btn-icon text-warning"
                                           href="{{url('/organisation/alerts/update/'.$alert->id) }}">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <a class="btn btn-sm btn-icon delete-record text-danger"
                                           href="{{ url('/organisation/alerts/delete/'.$alert->id) }}">
                                            <i class="bx bx-trash"></i>
                                        </a>
                                        <a class="btn btn-sm btn-icon text-primary"
                                           href="{{ url('/organisation/alerts/show/'.$alert->id) }}">
                                            <i class="bx bx-show"></i>
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8">
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
