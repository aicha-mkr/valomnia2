<!-- resources/views/admin/alerts/types/show.blade.php -->

@extends('layouts.contentNavbarLayout')

@section('title', 'Type Alert Details')

@section('content')
<div class="container mt-5">
    <h2>Type Alert Details</h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $typeAlert->name }}</h5>
            <p class="card-text">Slug: {{ $typeAlert->slug }}</p>
            <p class="card-text">name: {{ $typeAlert->name }}</p>
            <p class="card-text">Description: {{ $typeAlert->description }}</p>
            <p class="card-text">Status: {{ $typeAlert->status }}</p>
            <p class="card-text">Created at: {{ $typeAlert->created_at->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</div>
@endsection
