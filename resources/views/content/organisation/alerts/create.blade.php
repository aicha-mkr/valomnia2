<!-- resources/views/alerts/list/create.blade.php -->

@extends('layouts.contentNavbarLayout')

@section('title', 'Create Alert ')
@section('page-style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.31.3/tagify.css" />
<style>
.customLook {
    --tag-bg: #0052BF;
    --tag-hover: #CE0078;
    --tag-text-color: #FFF;
    --tags-border-color: silver;
    --tag-text-color--edit: #111;
    --tag-pad: .6em 1em;
    --tag-inset-shadow-size: 1.4em;
    /* compensate for the larger --tag-pad value */
    --tag-remove-btn-color: white;
    --tag-remove-btn-bg--hover: black;

    display: inline-block;
    min-width: 0;
    border: none;
}

.customLook .tagify__tag {
    margin-top: 0;
}

.customLook .tagify__tag>div {
    border-radius: 25px;
}

.customLook .tagify__tag:not(:only-of-type):not(.tagify__tag--editable):hover .tagify__tag-text {
    margin-inline-end: -1px;
}

/* Do not show the "remove tag" (x) button when only a single tag remains */
.customLook .tagify__tag:only-of-type .tagify__tag__removeBtn {
    display: none;
}

.customLook .tagify__tag__removeBtn {
    opacity: 0;
    transform: translateX(-100%) scale(.5);
    margin-inline: -20px 6px;
    /* very specific on purpose  */
    text-align: right;
    transition: .12s;
}

.customLook .tagify__tag:not(.tagify__tag--editable):hover .tagify__tag__removeBtn {
    transform: none;
    opacity: 1;
}

.customLook+button {
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

.customLook+button:hover {
    box-shadow: 0 0 0 5px inset currentColor;
}

.customLook .tagify__input {
    display: none;
}
</style>

@endsection
@section('page-script')
<script src="{{asset('assets/js/alerts-pages.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.31.3/tagify.min.js"></script>



<script>
// generate random whilist items (for the demo)
var randomStringsArr = Array.apply(null, Array(100)).map(function() {
    return Array.apply(null, Array(~~(Math.random() * 10 + 3))).map(function() {
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
    }); // "add new tag" action-button

button.addEventListener("click", onAddButtonClick)

document.addEventListener("DOMContentLoaded", function() {
  const typeSelect = document.getElementById("type_id");
  const warehouseDiv = document.querySelector(".required_stock_expired");
  const quantityDiv = document.getElementById("quantity-row");

  if (typeSelect && warehouseDiv && quantityDiv) {
    typeSelect.addEventListener("change", function() {
      const selectedOption = typeSelect.options[typeSelect.selectedIndex];
      const selectedSlug = selectedOption.getAttribute("data-slug");

      console.log("Selected Slug:", selectedSlug);

      if (selectedSlug === "expired-stock") {
        warehouseDiv.style.display = "flex";
        quantityDiv.style.display = "flex";
      } else if (selectedSlug === "checkin-out-of-hours") {
        warehouseDiv.style.display = "none"; // Pas besoin de warehouse ici
        quantityDiv.style.display = "none";  // Pas besoin de quantity
      } else {
        warehouseDiv.style.display = "none";
        quantityDiv.style.display = "none";
      }
    });
  }
});
  } else {
    console.error("One or more elements (type select, warehouse div, or quantity field) not found.");
  }
});



function onAddButtonClick() {
    tagify.addEmptyTag()
}

function onInvalidTag(e) {
    console.log("invalid", e.detail)
}


document.addEventListener("DOMContentLoaded", function() {
    const checkbox = document.getElementById("every_day");
    const dateInput = document.getElementById("html5-date-input");
    const timeInput = document.getElementById("html5-time-input");

    checkbox.addEventListener("change", function() {
        if (this.checked) {
            dateInput.style.display = "none"; // Hide date input
            dateInput.value = ""; // Clear its value
            timeInput.style.display = "block"; // Ensure time input is shown
        } else {
            dateInput.style.display = "block"; // Show date input
            timeInput.style.display = "block"; // Ensure time input is also shown
        }
    });

    document.querySelector("form").addEventListener("submit", function() {
        if (checkbox.checked) {
            dateInput.value = ""; // Clear date input if "Every Day" is checked
        }
    });
});
</script>
@endsection





@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">List of Alert/</span>
    Create Alert
</h4>

<div class="container mt-5">
    <form action="{{ url('organisation/alerts/store') }}" method="POST">
        @csrf
        <div class="card">
            <h5 class="card-header">

            </h5>
            <div class="card-body">
                @if($has_error)
                <div class="row mb-3">
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        List of warhouses is not available. Please try again later!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
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
                      <select name="warehouse_ids[]" class="form-select" id="warehouse_ids" multiple>
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
                            <input class="form-check-input" name="status" type="checkbox" value="1"
                                id="customCheckSuccess" checked="">
                            <label class="form-check-label" for="customCheckSuccess">Active</label>
                        </div>
                    </div>
                </div>
                <div class="m-0" style="padding-bottom: 40px">
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="status">Emails</label>
                        <div class="col-sm-10">
                            <input class="customLook"  name="users_email">
                            <button type="button">+</button>
                        </div>
                    </div>


                    <hr class="m-0" style="padding-bottom: 40px">
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="status">Trigger Date</label>
                        <div class="col-sm-10">
                            <!-- Checkbox for Every Day -->
                            <div class="form-check form-check-success">
                                <input class="form-check-input" name="every_day" type="checkbox" value="1"
                                    id="every_day">
                                <label class="form-check-label" for="every_day">Every Day</label>
                            </div>

                            <!-- Date Input -->
                            <div class="col-md">
                                <input class="form-control" type="date" name="date" id="html5-date-input"
                                    placeholder="YYYY-MM-DD" style="display: block;">
                            </div>

                            <!-- Time Input -->
                            <div class="col-md">
                                <input class="form-control" type="time" name="time" id="html5-time-input"
                                    placeholder="HH:MM" style="display: block;">
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
