@extends('layouts.contentNavbarLayout')

@section('title', 'Create Report')

@section('page-style')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.31.3/tagify.css" />
  <style>
    .customLook {
      --tag-bg: #0052BF;
      --tag-hover: #CE0078;
      --tag-text-color: #FFF;
      --tags-border-color: silver;
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
    }
    .customLook + button:hover {
      box-shadow: 0 0 0 5px inset currentColor;
    }
    .customLook .tagify__input { display: none; }
  </style>
@endsection

@section('content')
  <div class="container">
    <h2>Create Weekly Report</h2>
    <form action="{{ route('organisation-reports-store') }}" method="POST">
      @csrf
      <div class="card">
        <h5 class="card-header"></h5>
        <div class="card-body">

      <div class="mb-3">
        <label for="startDate" class="form-label">Start Date</label>
        <input type="date" class="form-control" name="startDate" id="startDate" required>
      </div>
      <div class="mb-3">
        <label for="endDate" class="form-label">End Date</label>
        <input type="date" class="form-control" name="endDate" id="endDate" required>
      </div>
          <div class="mb-3">
            <label for="recipients" class="form-label">Recipient Emails</label>
            <input class="customLook" name="recipients" id="recipients" placeholder="Add recipient emails">
            <button type="button" id="add-recipient">+</button>
          </div>
      <div class="mb-3">
        <label class="form-label">Include in Report</label>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="fields[]" value="total_orders" id="total_orders" checked>
          <label class="form-check-label" for="total_orders">Total Orders</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="fields[]" value="total_revenue" id="total_revenue" checked>
          <label class="form-check-label" for="total_revenue">Total Revenue</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="fields[]" value="average_sales" id="average_sales">
          <label class="form-check-label" for="average_sales">Average Sales</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="fields[]" value="total_quantities" id="total_quantities">
          <label class="form-check-label" for="total_quantities">Total Quantities</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="fields[]" value="total_clients" id="total_clients">
          <label class="form-check-label" for="total_clients">Total Clients</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="fields[]" value="top_selling_items" id="top_selling_items">
          <label class="form-check-label" for="top_selling_items">Top Selling Items</label>
        </div>
      </div>
      <div class="mb-3">
        <label for="schedule" class="form-label">Schedule</label>
        <select class="form-control" name="schedule" id="schedule">
          <option value="none">Send Now</option>
          <option value="weekly">Weekly (Every Monday)</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="status" id="status" value="1" checked>
          <label class="form-check-label" for="status">Active</label>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Generate Report</button>
    </form>
  </div>
@endsection

@section('page-script')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.31.3/tagify.min.js"></script>
  <script>
    // Generate random email list for demo
    const randomStringsArr = Array.apply(null, Array(100)).map(() => {
      return (
        Array.apply(null, Array(~~(Math.random() * 10 + 3)))
          .map(() => String.fromCharCode(Math.random() * (123 - 97) + 97))
          .join("") + "@gmail.com"
      );
    });

    document.addEventListener("DOMContentLoaded", function () {
      // Initialize Tagify for emails
      const input = document.querySelector('.customLook');
      const button = input ? input.nextElementSibling : null;

      if (input) {
        const tagify = new Tagify(input, {
          editTags: {
            keepInvalid: false,
          },
          pattern: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
          whitelist: randomStringsArr,
          callbacks: {
            invalid: onInvalidTag
          },
          dropdown: {
            position: 'text',
            enabled: 1
          }
        });

        // Add predefined email on button click
        if (button && typeof button.addEventListener === 'function') {
          button.addEventListener('click', function () {
            tagify.addTags(['default@example.com']);
          });
        }
      }

      // Callback for invalid tags
      function onInvalidTag(e) {
        alert('Invalid email: ' + e.detail.value);
      }
    });
  </script>
@endsection
