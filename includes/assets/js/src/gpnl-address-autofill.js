$(document).ready(function () {


  // this will get the address object that is inserted with the wp_localize_script() function in the controller
  var address_object = 'get_address_object';


  var zipcodeInput = document.getElementById('postal-code');
  var houseNoInput = document.getElementById('housenumber');

  $('#housenumber').focusout(function () {

    var zipcodeValue = zipcodeInput.value;
    var houseNoValue = houseNoInput.value;

    var ajax_values = {
      action: 'get_address',
      zipcode: zipcodeValue,
      house_no: houseNoValue,
      nonce: window[address_object].nonce
    };

    // validate zipcode and house-number and only make ajax call when valid.
    var zipRegex = /^[1-9][0-9]{3}[\s]?[A-Za-z]{2}$/i;
    if ( zipRegex.test(zipcodeValue) === true && !isNaN(houseNoValue) ) {

      // Do a ajax call to the wp_admin admin_ajax.php,
      // which triggers processing function in the petition block
      $.ajax({
        type: 'POST',
        url: window[address_object].ajaxUrl,
        data: ajax_values,
        success: function (t) {

          var streetInput = $('#street');
          var cityInput = $('#city');

          streetInput.val(t.data.cUrlresult.result.straat);
          cityInput.val(t.data.cUrlresult.result.woonplaats);

        }
      });

    }

  });

});
