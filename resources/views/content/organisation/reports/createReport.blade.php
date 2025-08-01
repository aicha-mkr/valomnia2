@extends('layouts.contentNavbarLayout')
@section('title', 'Create Report')

@section('page-style')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.31.3/tagify.css " />
  <style>
    .customLook {
      --tag-bg: #0052BF;
      --tag-hover: #CE0078;
      --tag-text-color: #FFF;
      display: inline-block;
      min-width: 0;
      border: none;
    }
    .customLook .tagify__tag > div {
      border-radius: 25px;
    }

    .customLook + button {
      color: #0052BF;
      font: bold 1.4em/1.65 Arial;
      border: 0;
      background: none;
      box-shadow: 0 0 0 2px inset currentColor;
      border-radius: 50%;
      width: 1.65em;
      height: 1.65em;
      cursor: pointer;
      outline: none;
      transition: .1s ease-out;
      margin: 0 0 0 5px;
      vertical-align: top;
    }

    .customLook + button:hover {
      box-shadow: 0 0 0 5px inset currentColor;
    }

    .customLook .tagify__input { display: none; }
  </style>
@endsection

@section('content')
  <div class="container mt-4">
    <h4 class="py-3 mb-4">
      <span class="text-muted fw-light">Reports /</span> Configure Weekly Report
    </h4>

    <div class="card">
      <div class="card-body">

        <form action="{{ route('organisation-reports-store') }}" method="POST">
          @csrf

          <!-- Email Template Dropdown -->
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="email_template_id">Email Template <span style="color:red">*</span></label>
            <div class="col-sm-10">
              <select name="email_template_id" id="email_template_id" class="form-select" required>
                <option value="">-- Select a report email template --</option>
                @foreach($reportTemplates ?? [] as $template)
                  <option value="{{ $template->id }}">{{ $template->title ?? $template->subject ?? 'Template #'.$template->id }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <!-- Date Range -->
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="startDate">Date Range</label>
            <div class="col-sm-5">
              <input type="date" name="startDate" id="startDate" class="form-control" value="{{ old('startDate', $defaultData['startDate'] ?? '') }}" required>
            </div>
            <div class="col-sm-5">
              <input type="date" name="endDate" id="endDate" class="form-control" value="{{ old('endDate', $defaultData['endDate'] ?? '') }}" required>
            </div>
          </div>

          <!-- Recipient Emails -->
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="users_email">Emails</label>
            <div class="col-sm-10 d-flex align-items-center">
              <input class="customLook form-control" name="users_email" id="users_email" placeholder="Add recipient emails" value="{{ old('users_email') }}">
              <button type="button" class="ms-2 btn btn-outline-primary btn-icon">
                <i class="bx bx-plus"></i>
              </button>
            </div>
          </div>

          <!-- Select KPIs (hidden but checked by default) -->
          <div class="row mb-3" style="display:none;">
            <label class="col-sm-2 col-form-label">Select KPIs</label>
            <div class="col-sm-10">
              <input class="form-check-input kpi-checkbox" type="checkbox" name="fields[]" value="total_orders" id="total_orders" checked>
              <input class="form-check-input kpi-checkbox" type="checkbox" name="fields[]" value="total_revenue" id="total_revenue" checked>
              <input class="form-check-input kpi-checkbox" type="checkbox" name="fields[]" value="average_sales" id="average_sales" checked>
              <input class="form-check-input kpi-checkbox" type="checkbox" name="fields[]" value="total_quantities" id="total_quantities" checked>
              <input class="form-check-input kpi-checkbox" type="checkbox" name="fields[]" value="total_clients" id="total_clients" checked>
              <input class="form-check-input kpi-checkbox" type="checkbox" name="fields[]" value="top_selling_items" id="top_selling_items" checked>
            </div>
          </div>

          <!-- Schedule Options -->
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="schedule">Schedule</label>
            <div class="col-sm-10">
              <select name="schedule" id="schedule" class="form-select" required>
                <option value="">Choose schedule</option>
                <option value="none" {{ ($defaultData['schedule'] ?? '') == 'none' ? 'selected' : '' }}>Send Now</option>
                <option value="daily" {{ ($defaultData['schedule'] ?? '') == 'daily' ? 'selected' : '' }}>Daily</option>
                <option value="weekly" {{ ($defaultData['schedule'] ?? '') == 'weekly' ? 'selected' : '' }}>Weekly (Every Monday)</option>
                <option value="monthly" {{ ($defaultData['schedule'] ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
              </select>
            </div>
          </div>

          <!-- Time Field -->
          <div class="row mb-3 align-items-center" id="time-row" style="display: {{ in_array($defaultData['schedule'] ?? '', ['daily', 'weekly', 'monthly']) ? 'flex' : 'none' }};">
            <label class="col-sm-2 col-form-label" for="time" style="margin-bottom:0;">Time</label>
            <div class="col-sm-10 d-flex align-items-center" style="padding-top:0;padding-bottom:0;">
              <input type="time" name="time" id="time" class="form-control" style="height:40px;display:flex;align-items:center;" value="{{ old('time', $defaultData['time'] ?? '08:00') }}" placeholder="--:--">
            </div>
          </div>

          <!-- Status Toggle -->
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="status">Status</label>
            <div class="col-sm-10">
              <div class="form-check form-check-success">
                <input class="form-check-input" type="checkbox" name="status" id="status" {{ ($defaultData['status'] ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="status">Active</label>
              </div>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="row justify-content-end">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-primary">Save Report Configuration</button>
              <a href="{{ route('organisation-reports') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('page-script')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.31.3/tagify.min.js "></script>
  <script>
    // Tagify Email Input
    document.addEventListener("DOMContentLoaded", function () {
      const input = document.querySelector('.customLook');
      if (input) {
        const tagify = new Tagify(input, {
          pattern: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
          dropdown: {
            enabled: 1
          },
          whitelist: [
            "example@example.com",
            "john.doe@company.co",
            "jane.smith@workplace.org"
          ]
        });

        // Pre-populate Tagify with existing emails if any
        const existingEmails = '{{ $defaultData["users_email"] ?? "" }}';
        if (existingEmails && existingEmails.trim() !== '') {
          const emails = existingEmails.split(',').filter(email => email.trim() !== '');
          if (emails.length > 0) {
            tagify.addTags(emails.map(email => ({ value: email.trim() })));
          }
        }

        const button = input.nextElementSibling;
        if (button) {
          button.addEventListener("click", function () {
            tagify.addEmptyTag();
          });
        }
      }

      // Show/hide time field based on schedule
      const scheduleSelect = document.getElementById("schedule");
      const timeRow = document.getElementById("time-row");
      const timeInput = document.getElementById("time");

      function updateTimeRow() {
        if (!scheduleSelect || !timeRow || !timeInput) return;
        const selected = scheduleSelect.value;
        if (selected === "daily" || selected === "weekly" || selected === "monthly") {
          timeRow.style.display = "flex";
        } else {
          timeRow.style.display = "none";
          timeInput.value = "";
        }
      }

      if (scheduleSelect && timeRow && timeInput) {
        scheduleSelect.addEventListener("change", updateTimeRow);
        setTimeout(updateTimeRow, 0); // Correction: forcer l'état au chargement réel
      }
    });
  </script>
@endsection
