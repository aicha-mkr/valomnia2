@extends('layouts.contentNavbarLayout')

@section('title', 'Alert Details')

@section('content')
  <div class="container mt-5">
    <h2>Alert Details</h2>
    <div class="card">
      <div class="card-body">
        <!-- Title -->
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label">Title</label>
          <div class="col-sm-10">
            {{ $alert->title }}
          </div>
        </div>

        <!-- Type -->
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label">Type</label>
          <div class="col-sm-10">
            {{ $alert->type->slug }}
          </div>
        </div>

        <!-- Description -->
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label">Description</label>
          <div class="col-sm-10">
            {{ $alert->description }}
          </div>
        </div>

        <!-- Status -->
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label">Status</label>
          <div class="col-sm-10">
                    <span class="badge {{ $alert->status == 1 ? 'bg-label-success' : 'bg-label-danger' }}">
                        {{ $alert->status == 1 ? 'Active' : 'Inactive' }}
                    </span>
          </div>
        </div>

        <!-- Date or Time -->
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label">Date / Time</label>
          <div class="col-sm-10">
            @if ($alert->time === null)
              {{ $alert->date }}
            @else
              {{ $alert->date }} at {{ $alert->time }}
            @endif
          </div>
        </div>

        <!-- Quantity -->
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label">Quantity</label>
          <div class="col-sm-10">
            {{ $alert->quantity }}
          </div>
        </div>

        <!-- Everyday -->
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label">Everyday</label>
          <div class="col-sm-10">
                    <span class="badge badge-center rounded-pill {{ $alert->every_day == 1 ? 'bg-success' : 'bg-danger' }}">
                        {{ $alert->every_day == 1 ? 'Yes' : 'No' }}
                    </span>
          </div>
        </div>

        <!-- Template -->
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label">Template</label>
          <div class="col-sm-10">
            {{ $alert->template->title ?? 'N/A' }}
          </div>
        </div>

        <!-- Warehouses -->
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label">Warehouses</label>
          <div class="col-sm-10">
            @if ($alert->warehouse_ids)
              @foreach (explode(',', trim($alert->warehouse_ids, ',')) as $warehouse_id)
                <span class="badge bg-label-info">{{ $warehouse_id }}</span>
              @endforeach
            @else
              N/A
            @endif
          </div>
        </div>

        <!-- Emails -->
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label">Emails</label>
          <div class="col-sm-10">
            {{ $alert->users_email ?? 'N/A' }}
          </div>
        </div>

        <!-- Back Button -->
        <a href="{{ url('organisation/alerts') }}" class="btn btn-primary">Go Back</a>
      </div>
    </div>
  </div>
@endsection
