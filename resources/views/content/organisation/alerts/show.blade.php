<!-- resources/views/admin/alerts/types/show.blade.php -->

@extends('layouts.contentNavbarLayout')

@section('title', 'Alert Details')

@section('content')
<div class="container mt-5">
    <h2> Alert Details</h2>
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="basic-default-name">title</label>
                <div class="col-sm-10">
                    {{ $alert->title }}

                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="basic-default-name">Type</label>
                <div class="col-sm-10">
                    {{ $alert->type->slug }}

                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="basic-default-name">Description</label>
                <div class="col-sm-10">
                    {{ $alert->Description }}
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="basic-default-name">Status</label>
                <div class="col-sm-10">
                    <span class="badge bg-label-success">Active</span>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="basic-default-name">Date</label>
                <div class="col-sm-10">
                    @if($alert->time === null)
                        <span>{{ $alert->date }}</span>
                    @else
                        <span>{{ $alert->time }}</span>
                        @endif</p>                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="basic-default-name">Quantity</label>
                <div class="col-sm-10">
                    {{ $alert->quantity }}
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="basic-default-name">Everyday</label>
                <div class="col-sm-10">
                    <span class="badge badge-center rounded-pill bg-success"><i class="bx bx-check"></i></span>
                </div>
            </div>
            <a href="{{ url('organisation/alerts') }}" class="btn btn-primary">Go back</a>

        </div>
    </div>
</div>
@endsection
