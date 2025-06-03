@extends('layouts.contentNavbarLayout')

@section('title', 'Edit Report')

@section('page-style')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.31.3/tagify.css" />
  <style>
    .customLook { --tag-bg: #0052BF; --tag-hover: #CE0078; --tag-text-color: #FFF; display: inline-block; min-width: 0; border: none; }
    .customLook .tagify__tag > div { border-radius: 25px; }
    .customLook + button { color: #0052BF; font: bold 1.4em/1.65 Arial; border: 0; background: none; box-shadow: 0 0 0 2px inset currentColor; border-radius: 50%; width: 1.65em; height: 1.65em; cursor: pointer; outline: none; transition: .1s ease-out; margin: 0 0 0 5px; vertical-align: top; }
    .customLook + button:hover { box-shadow: 0 0 0 5px inset currentColor; }
    .customLook .tagify__input { display: none; }
  </style>
@endsection

@section('content')
  <div class="container mt-4">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Reports /</span> Edit Report</h4>
    <div class="card">
      <div class="card-body">
        <form action="{{ route('organisation-reports-edit', $report->id) }}" method="POST">
          @csrf
          @method('PUT')
          <!-- Date Range -->
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="startDate">Date Range</label>
            <div class="col-sm-5">
              <input type="date" name="startDate" id="startDate" class="form-control" value="{{ old('startDate', $report->startDate) }}" required>
              @error('startDate')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="col-sm-5">
              <input type="date" name="endDate" id="endDate" class="form-control" value="{{ old('endDate', $report->endDate) }}" required>
              @error('endDate')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
          </div>
          <!-- Recipient Emails -->
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="users_email">Emails</label>
            <div class="col-sm-10 d-flex align-items-center">
              <input class="customLook form-control" name="users_email" id="users_email" placeholder="Add recipient emails" value="{{ old('users_email', $report->users_email) }}" required>
              <button type="button" class="ms-2 btn btn-outline-primary btn-icon"><i class="bx bx-plus"></i></button>
              @error('users_email')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
          </div>
          <!-- Select KPIs -->
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Select KPIs</label>
            <div class="col-sm-10">
              <div class="form-check form-check-success">
                <input class="form-check-input" type="checkbox" name="fields[]" value="total_orders" id="total_orders" {{ in_array('total_orders', $report->kpis) ? 'checked' : '' }}>
                <label class="form-check-label" for="total_orders">Total Orders</label>
              </div>
              <div class="form-check form-check-success">
                <input class="form-check-input" type="checkbox" name="fields[]" value="total_revenue" id="total_revenue" {{ in_array('total_revenue', $report->kpis) ? 'checked' : '' }}>
                <label class="form-check-label" for="total_revenue">Total Revenue</label>
              </div>
              <div class="form-check form-check-success">
                <input class="form-check-input" type="checkbox" name="fields[]" value="average_sales" id="average_sales" {{ in_array('average_sales', $report->kpis) ? 'checked' : '' }}>
                <label class="form-check-label" for="average_sales">Average Sales</label>
              </div>
              <div class="form-check form-check-success">
                <input class="form-check-input" type="checkbox" name="fields[]" value="total_quantities" id="total_quantities" {{ in_array('total_quantities', $report->kpis) ? 'checked' : '' }}>
                <label class="form-check-label" for="total_quantities">Total Quantities Sold</label>
              </div>
              <div class="form-check form-check-success">
                <input class="form-check-input" type="checkbox" name="fields[]" value="total_clients" id="total_clients" {{ in_array('total_clients', $report->kpis) ? 'checked' : '' }}>
                <label class="form-check-label" for="total_clients">Total Clients</label>
              </div>
              <div class="form-check form-check-success">
                <input class="form-check-input" type="checkbox" name="fields[]" value="top_selling_items" id="top_selling_items" {{ in_array('top_selling_items', $report->kpis) ? 'checked' : '' }}>
                <label class="form-check-label" for="top_selling_items">Top Selling Items</label>
              </div>
              @error('fields')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
          </div>
          <!-- Schedule Options -->
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="schedule">Schedule</label>
            <div class="col-sm-10">
              <select name="schedule" id="schedule" class="form-select" required>
                <option value="none" {{ old('schedule', $report->schedule) == 'none' ? 'selected' : '' }}>Send Now</option>
                <option value="daily" {{ old('schedule', $report->schedule) == 'daily' ? 'selected' : '' }}>Daily</option>
                <option value="weekly" {{ old('schedule', $report->schedule) == 'weekly' ? 'selected' : '' }}>Weekly (Every Monday)</option>
                <option value="monthly" {{ old('schedule', $report->schedule) == 'monthly' ? 'selected' : '' }}>Monthly</option>
              </select>
              @error('schedule')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
          </div>
          <!-- Time Field -->
          <div class="row mb-3" id="time-row" style="display: {{ in_array(old('schedule', $report->schedule), ['daily', 'weekly', 'monthly']) ? 'block' : 'none' }};">
            <label class="col-sm-2 col-form-label" for="time">Time</label>
            <div class="col-sm-10">
              <input type="time" name="time" id="time" class="form-control" value="{{ old('time', $report->time) }}">
              @error('time')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
          </div>
          <!-- Status Toggle -->
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="status">Status</label>
            <div class="col-sm-10">
              <div class="form-check form-check-success">
                <input class="form-check-input" type="checkbox" name="status" id="status" {{ old('status', $report->status) ? 'checked' : '' }}>
                <label class="form-check-label" for="status">Active</label>
              </div>
            </div>
          </div>
          <!-- Submit Button -->
          <div class="row justify-content-end">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-primary">Update Report Configuration</button>
              <a href="{{ route('organisation-reports') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('page-script')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.31.3/tagify.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const input = document.querySelector('.customLook');
      if (input) {
        const tagify = new Tagify(input, {
          pattern: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
          dropdown: { enabled: 1 },
          whitelist: ["example@example.com", "john.doe@company.co", "jane.smith@workplace.org"]
        });
        // Pre-populate Tagify with existing emails
        const emails = '{{ $report->users_email }}'.split(',').filter(email => email.trim() !== '');
        tagify.addTags(emails.map(email => ({ value: email.trim() })));

        const button = input.nextElementSibling;
        if (button) {
          button.addEventListener("click", function () {
            tagify.addEmptyTag();
          });
        }
      }

      const scheduleSelect = document.getElementById("schedule");
      const timeRow = document.getElementById("time-row");
      const timeInput = document.getElementById("time");
      if (scheduleSelect && timeRow && timeInput) {
        const updateTimeRow = () => {
          const selected = scheduleSelect.value;
          timeRow.style.display = (selected === "daily" || selected === "weekly" || selected === "monthly") ? "block" : "none";
          if (selected !== "daily" && selected !== "weekly" && selected !== "monthly") {
            timeInput.value = "";
          }
        };
        scheduleSelect.addEventListener("change", updateTimeRow);
        updateTimeRow();
      }
    });
  </script>
@endsection
