@extends('layouts/contentNavbarLayout')

@section('title', 'Alert history')

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Alert history</span>
</h4>



<!-- Bordered Table -->
<div class="card">
  <h5 class="card-header">List of Alert history</h5>
  <div class="card-body">
    <div class="table-responsive text-nowrap">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>idalert</th>
            <th>iduser</th>
            <th>Type</th>
            <th>Organisation</th>
            <th>Nbre of attempts</th>
            <th>created_at</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
           @foreach($historiqueAlerts as $index=> $historiqueAlert)
          <tr>
            <td> <span class="fw-medium">{{ $historiqueAlert->idalert }}</span></td>//**Stock wharehouse agaba
            <td> {{ $historiqueAlert->iduser }}</td>
            <td> {{ $historiqueAlert->type }}</td>
            <td> {{ $historiqueAlert->organisation }}Lepidor</td>
            <td>{{ $historiqueAlert->numberofattempts }}</td>
            <td>{{ $historiqueAlert->created_at }}</td>
            <td>{{ $historiqueAlert->Status }} <span class="badge bg-label-success me-1">Completed</span></td>
            <td>
            <div class="dropdown">
               <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
               <div class="dropdown-menu">
                 <a class="dropdown-item text-success" href="{{url('/admin/alerts/history/regenerate/'.$historiqueAlert->id) }}"><i class="bx bx-repeat me-1"></i> regenerate</a>
                 <a class="dropdown-item text-danger" href="{{ url('/admin/alerts/history/delete/'.$historiqueAlert->id) }}"><i class="bx bx-trash me-1"></i> Delete</a>
               </div>
            </div>
            </td>
          </tr>

           @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
<!--/ Bordered Table -->

@endsection
