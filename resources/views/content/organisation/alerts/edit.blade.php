<!-- resources/views/alerts/edit.blade.php -->
@extends('layouts.contentNavbarLayout')

@section('title', 'edit Alert ')

@section('content')
    <script src="{{asset('assets/js/alerts-pages.js')}}"></script>

    <h4 class="py-3 mb-4">
    <span class="text-muted fw-light">List of Alert /</span>
    Update Alert
</h4>
<div class="container mt-5">
    <form action="{{ url('/organisation/alerts/update', $alert->id) }}" method="POST">
        @csrf

    <div class="card">
      <h5 class="card-header"></h5>
      <div class="card-body">

                  <div class="row mb-3">
                      <label class="col-sm-2 col-form-label" for="basic-default-name">title</label>
                      <div class="col-sm-10">
                          <input type="text" name="title" class="form-control" value="{{ $alert->title }}" id="name" required>

                      </div>
                    </div>
               <div class="row mb-3">
                   <label class="col-sm-2 col-form-label" for="basic-default-type_id">type</label>
                   <div class="col-sm-10">
                       <select  name="type_id" class="form-select" id="type_id">
                           <option value="">---- select type alert ------</option>
                           @foreach($type_alerts as $type_alert)
                               <option value="{{$type_alert->id}}" data-slug="{{$type_alert->slug}}" {{$type_alert->id == $alert->type_id ?  'selected' : ''}} > {{$type_alert->name}}</option>

                           @endforeach
                       </select>

                   </div>
               </div>

               <div class="row mb-3 required_stock_expired required_by_type_alerts" style="display: {{$alert->type->slug == "expired_stock" ? '' : 'none'}}">

                   <label class="col-sm-2 col-form-label" for="basic-default-type_id">warehouse</label>
                   <div class="col-sm-10">
                       <?php
                        $selected_warhouse=isset($alert->warehouse_ids) ?  explode(',',$alert->warehouse_ids) : [];
                       ?>
                       <select  name="warehouse_ids[]" class="form-select " id="warehouse_ids" multiple>
                           @foreach($warhouses as $warhouse)
                               <option value="{{$warhouse['id']}}" {{in_array($warhouse['id'], $selected_warhouse) ? 'selected' : ''}}>{{$warhouse['name']}}</option>
                           @endforeach
                       </select>

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
                            <option value="1" {{ $alert->status == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $alert->status == '0' ? 'selected' : '' }}>Inactive</option>
                       </select>
                    </div>
                  </div>
          {{$alert->type->slug}}
               <div class="row mb-3 required_stock_expired required_by_type_alerts" style="display: {{$alert->type->slug == "expired_stock" ? '' : 'none'}}">
                   <label class="col-sm-2 col-form-label" for="basic-default-name">quantity</label>
                   <div class="col-sm-10">
                       <input type="text" value="{{$alert->quantity}}" name="quantity" class="form-control" id="quantity" required>
                   </div>
               </div>
      </div>
        <div class="card-footer">
            <div class="row justify-content-end">
                <div class="col-sm-10">
                    <a href="{{ url('organisation/alerts') }}" class="btn btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-warning">update</button>
                </div>
            </div>
        </div>


    </div>

    </form>

</div>
@endsection
