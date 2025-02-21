/**
 * alert pages js
 */

'use strict';

(function () {

  document.getElementById('every_day').addEventListener('change', function() {
    var dateTimeInput = document.getElementById('html5-datetime-local-input');
    var timeInput = document.getElementById('html5-time-input');

    if (this.checked) {
      dateTimeInput.style.display = 'none';
      timeInput.style.display = '';
    } else {
      dateTimeInput.style.display = '';
      timeInput.style.display = 'none';
    }
  });
  document.getElementById('type_id').addEventListener('change', function() {
    var selectedSlug = this.options[this.selectedIndex].dataset.slug;
    console.log("data-slug:", selectedSlug);
    var elements = document.getElementsByClassName('required_by_type_alerts');
    for (var i = 0; i < elements.length; i++) {
      elements[i].style.display = 'none';
    }
    if (selectedSlug === 'expired_stock') {
      var elements = document.getElementsByClassName('required_stock_expired');
      for (var i = 0; i < elements.length; i++) {
        elements[i].style.display = '';
      }
    }
  });
})();
