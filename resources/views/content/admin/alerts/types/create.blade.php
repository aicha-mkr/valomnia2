<!-- resources/views/alerts/types/create.blade.php -->

@extends('layouts.contentNavbarLayout')

@section('title', 'Create Alert ')

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">List of Alert type /</span>
    Create Alert type
</h4>
<div class="container mt-5">
    <div class="card">
      <h5 class="card-header"></h5>
      <div class="card-body">
           <form action="{{ url('admin/alerts/types/store') }}" method="POST">
                  @csrf
                  <div class="row mb-3">
                      <label class="col-sm-2 col-form-label" for="basic-default-name">Name</label>
                      <div class="col-sm-10">
                          <input type="text" name="name" class="form-control" id="name" required>

                      </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-default-name">Description</label>
                        <div class="col-sm-10">
                            <textarea name="description" class="form-control" id="description" rows="5" required></textarea>

                        </div>
                    </div>
                  <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="status">Status</label>
                    <div class="col-sm-10">
                       <select name="status" class="form-control" id="status" required>
                             <option value="1">Active</option>
                             <option value="0">Inactive</option>
                       </select>
                    </div>
                  </div>
                  <div class="row justify-content-end">
                      <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Create</button>
                      </div>
                  </div>

              </form>
      </div>
    </div>
    <h2></h2>

</div>
@endsection
