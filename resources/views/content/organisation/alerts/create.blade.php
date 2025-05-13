<!-- resources/views/content/organisation/alerts/create.blade.php -->
@extends('layouts.contentNavbarLayout')

@section('title', 'Create Alert')

@section('page-style')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.31.3/tagify.css" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <style>
    .customLook {
      --tag-bg: #0052BF;
      --tag-hover: #CE0078;
      --tag-text-color: #FFF;
      --tags-border-color: silver;
      --tag-text-color--edit: #111;
      --tag-pad: .6em 1em;
      --tag-inset-shadow-size: 1.4em;
      --tag-remove-btn-color: white;
      --tag-remove-btn-bg--hover: black;
      display: inline-block;
      min-width: 0;
      border: none;
    }
    .customLook .tagify__tag { margin-top: 0; }
    .customLook .tagify__tag > div { border-radius: 25px; }
    .customLook .tagify__tag:not(:only-of-type):not(.tagify__tag--editable):hover .tagify__tag-text {
      margin-inline-end: -1px;
    }
    .customLook .tagify__tag:only-of-type .tagify__tag__removeBtn { display: none; }
    .customLook .tagify__tag__removeBtn {
      opacity: 0;
      transform: translateX(-100%) scale(.5);
      margin-inline: -20px 6px;
      text-align: right;
      transition: .12s;
    }
    .customLook .tagify__tag:not(.tagify__tag--editable):hover .tagify__tag__removeBtn {
      transform: none;
      opacity: 1;
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
    .employee-list { display: flex; margin-bottom: 1rem; }
    .employee-list[style*="display: none"] { display: none !important; }
  </style>
@endsection

@section('page-script')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.31.3/tagify.min.js"></script>
  <script src="{{ asset('assets/js/alerts-pages.js') }}"></script>

  <script>

    if (typeof jQuery !== 'undefined') {
      console.log('jQuery is loaded');

      jQuery(document).ready(function($) {
        console.log('Document ready, initializing Select2');

        try {
          if (typeof $.fn.select2 === 'function') {
            console.log('Select2 is available');
            $('#employee').select2({
              placeholder: "Search employees...",
              allowClear: true,
              width: '100%'
            });
            console.log('Select2 initialized successfully');
          } else {
            console.error('Select2 function not found');
          }
        } catch (error) {
          console.error('Error initializing Select2:', error);
        }


        // Type selection handler
        const typeSelect = document.getElementById("type_id");
        const warehouseDiv = document.querySelector(".required_stock_expired");
        const quantityDiv = document.getElementById("quantity-row");
        const employeeListDiv = document.querySelector(".employee-list");

        if (typeSelect && warehouseDiv && quantityDiv && employeeListDiv) {
          typeSelect.addEventListener("change", function() {
            const selectedOption = typeSelect.options[typeSelect.selectedIndex];
            const selectedSlug = selectedOption.getAttribute("data-slug");
            console.log('Selected slug:', selectedSlug);

            if (selectedSlug === "expired-stock") {
              warehouseDiv.style.display = "flex";
              quantityDiv.style.display = "flex";
              employeeListDiv.style.display = "none";
            } else if (selectedSlug === "checkin-out-of-hours") {
              warehouseDiv.style.display = "none";
              quantityDiv.style.display = "none";
              employeeListDiv.style.display = "flex";
            } else {
              warehouseDiv.style.display = "none";
              quantityDiv.style.display = "none";
              employeeListDiv.style.display = "none";
            }
          });
        } else {
          console.error('Missing elements:', {
            typeSelect: !!typeSelect,
            warehouseDiv: !!warehouseDiv,
            quantityDiv: !!quantityDiv,
            employeeListDiv: !!employeeListDiv
          });
        }

        // Form submission handler
        const checkbox = document.getElementById("every_day");
        const dateInput = document.getElementById("html5-date-input");
        document.querySelector("form").addEventListener("submit", function(event) {
          console.log('Form submission triggered');
          if (checkbox && dateInput && checkbox.checked) {
            console.log('Clearing date input due to Every Day checkbox');
            dateInput.value = "";
          }
        });
      });
    } else {
      console.error('jQuery is not loaded');
    }



    // generate random whilist items (for the demo)
    var randomStringsArr = Array.apply(null, Array(100)).map(function () {
      return Array.apply(null, Array(~~(Math.random() * 10 + 3))).map(function () {
        return String.fromCharCode(Math.random() * (123 - 97) + 97)
      }).join('') + '@gmail.com'
    })

    var input = document.querySelector('.customLook'),
      button = input.nextElementSibling,
      tagify = new Tagify(input, {
        editTags: {
          keepInvalid: false, // better to auto-remove invalid tags which are in edit-mode (on blur)
        },
        // email address validation (https://stackoverflow.com/a/46181/104380)
        pattern: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
        whitelist: randomStringsArr,
        callbacks: {
          "invalid": onInvalidTag
        },
        dropdown: {
          position: 'text',
          enabled: 1 // show suggestions dropdown after 1 typed character
        }
      });  // "add new tag" action-button

    button.addEventListener("click", onAddButtonClick)

    function onAddButtonClick() {
      tagify.addEmptyTag()
    }

    function onInvalidTag(e) {
      console.log("invalid", e.detail)
    }
  </script>
@endsection

@section('content')
  <h4 class="py-3 mb-4">
    <span class="text-muted fw-light">List of Alert/</span> Create Alert
  </h4>

  <div class="container mt-5">
    <form action="{{ route('organisation-alerts-store') }}" method="POST">
      @csrf
      <div class="card">
        <h5 class="card-header"></h5>
        <div class="card-body">
          @if($has_error)
            <div class="row mb-3">
              <div class="alert alert-danger alert-dismissible" role="alert">
                List of warhouses is not available. Please try again later!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            </div>
          @endif

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-name">title</label>
            <div class="col-sm-10">
              <input type="text" name="title" class="form-control" id="name" required>
            </div>
          </div>

          <!-- Type Dropdown -->
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="type_id">Type</label>
            <div class="col-sm-10">
              <select name="type_id" class="form-select" id="type_id" required>
                <option value="" disabled selected>---- Select Type Alert ----</option>
                @foreach($type_alerts as $type_alert)
                  <option value="{{ $type_alert->id }}" data-slug="{{ $type_alert->slug }}">{{ $type_alert->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <!-- Warehouse Dropdown -->
          <div class="row mb-3 required_stock_expired" style="display: none;">
            <label class="col-sm-2 col-form-label" for="warehouse_ids">Warehouse</label>
            <div class="col-sm-10">
              <select name="warehouse_ids[]" class="form-select" id="warehouse_ids">
                <option value="">Select a warehouse</option>
                @foreach($warhouses as $warhouse)
                  <option value="{{ $warhouse['id'] }}">{{ $warhouse['name'] }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <!-- Quantity Field -->
          <div class="row mb-3 required_stock_expired required_by_type_alerts" style="display: none;" id="quantity-row">
            <label class="col-sm-2 col-form-label" for="quantity">Quantity</label>
            <div class="col-sm-10">
              <input type="number" name="quantity" class="form-control" id="quantity" min="1">
            </div>
          </div>

          <!-- Employee List -->
          <div class="row mb-3 employee-list" style="display: none;">
            <label class="col-sm-2 col-form-label" for="employee">Employee List</label>
            <div class="col-sm-10">
              <select class="form-select" id="employee" name="employee">
                <option value="">Select an employee</option>
                @foreach($employees as $employee)
                  <option value="{{ $employee['reference'] ?? '' }}" data-email="{{ $employee['email'] ?? '' }}">
                    {{ $employee['firstName'] ?? '' }} {{ $employee['lastName'] ?? '' }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <!-- Template Dropdown -->
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="template_id">Template</label>
            <div class="col-sm-10">
              <select name="template_id" class="form-select" id="template_id" required>
                <option value="" disabled selected>---- Select Template ----</option>
                @foreach($templates as $template)
                  <option value="{{ $template->id }}">{{ $template->title }}</option>
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
              <div class="form-check form-check-success">
                <input class="form-check-input" name="status" type="checkbox" value="1" id="customCheckSuccess" checked="">
                <label class="form-check-label" for="customCheckSuccess">Active</label>
              </div>
            </div>
          </div>

            <hr class="m-0" style="padding-bottom: 40px">
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="users_email">Emails</label>
              <div class="col-sm-10">
                <input class='customLook' name="users_email" value='some.name@website.com'>
                <button type="button">+</button>
              </div>
            </div>
            <hr class="m-0" style="padding-bottom: 40px">
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="status">Trigger Date</label>
              <div class="col-sm-10">
                <div class="col-md">
                  <div class="form-check form-check-success" style="padding-bottom: 20px">
                    <input class="form-check-input" name="every_day" type="checkbox" value="1"
                           id="every_day">
                    <label class="form-check-label" for="every_day">Every Day</label>
                  </div>
                </div>
                <div class="col-md">
                  <input class="form-control " type="datetime-local" placeholder="YYYY-MM-DD HH:MM"
                         name="date" value="" id="html5-datetime-local-input"
                         style="margin-bottom: 20px;"/>

                </div>
                <div class="col-md">
                  <input class="form-control" type="time" name="time" value="" placeholder="HH:MM"
                         id="html5-time-input" style="margin-bottom: 20px;display: none"/>

                </div>

              </div>
            </div>


        </div>
        <div class="card-footer">
          <div class="row justify-content-end">
            <div class="col-sm-10">
              <a href="{{ url('organisation/alerts') }}" class="btn btn-danger">Cancel</a>
              <button type="submit" class="btn btn-primary">Create</button>
            </div>
          </div>
        </div>

      </div>
    </form>


  </div>
@endsection
