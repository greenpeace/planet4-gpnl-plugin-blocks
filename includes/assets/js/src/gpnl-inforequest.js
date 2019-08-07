var request_form_element = {};
var check_form_element = {};
$('.hideshowbtn').on('click', function () {
  let btn = $(this);
  let form  = btn.data('target');
  $(form).show(1000);
  $('.hideshowbtn').hide(1000);
});

$('.inforequest_form').on('reset', function () {
  $(this).hide(1000);
  $('.hideshowbtn').show(1000);
});

$('.gpnl-request__form').on('submit', function () {
  request_form_element = this;
  var post_form_value = getFormObj(request_form_element);
  var form_config = 'request_form_object';
  post_form_value.action = 'request_form_process';
  post_form_value.nonce  = window[form_config].nonce;
  post_form_value.literaturecode  = window[form_config].literaturecode;

  toggleDisable($(request_form_element).find('*'));
  if (post_form_value.human !== '') {
    showErrorMessage(request_form_element);
    return;
  }

  $.ajax({
    type:    'POST',
    url:     window[form_config].ajaxUrl,
    data:    post_form_value,
    success: function() {
      $(request_form_element).find('*').hide(1000);
      $('.inforequest__title').html('<h3>Dank je voor je aanmelding!</h3>');
      $(request_form_element).prepend('<p>Je kan nu gebruikmaken van de lesmaterialen.</p>');

    },
    error: function(){
      // If the backend sends an error, hide the thank element and show an error urging to try again
      showErrorMessage(request_form_element);
    }
  });
});

$('.gpnl-check__form').on('submit', function () {
  check_form_element = this;
  var post_form_value = getFormObj(check_form_element);
  var form_config = 'request_form_object';
  post_form_value.action = 'check_form_process';
  post_form_value.nonce  = window[form_config].nonce;

  toggleDisable($(check_form_element).find('*'));
  if (post_form_value.human !== '') {
    showErrorMessage(check_form_element);
    return;
  }

  $.ajax({
    type:    'POST',
    url:     window[form_config].ajaxUrl,
    data:    post_form_value,
    success: function() {
      // $(check_form_element).find('*').hide();
      // $(check_form_element).append('<h2>Hoera, je bent er bijna!</h2>');

    },
    error: function(){
      // If the backend sends an error, hide the thank element and show an error urging to try again
      $('.resetBtn').remove('*');
      $('.gpnl-check__form').hide(1000);
      $('.gpnl-request__form').prepend('<p>Dit emailadres is niet bij ons bekend. Wil je je inschrijven?');
      $('.gpnl-request__form').show(1000);
      $('.hideshowbtn').remove('*');
    }
  });
});

// Get the key+value from the input fields in the form
function getFormObj(el) {
  var formObj = {};
  var inputs = $(el).serializeArray();
  $.each(inputs, function (i, input) {
    formObj[input.name] = input.value;
  });
  return formObj;
}

function showErrorMessage(request_form_element) {
  $(request_form_element).find('*').hide();
  $(request_form_element).append('Oh nee!');
  $(request_form_element).append('<p>Hier gaat helaas iets mis, sorry. </p>');
  $(request_form_element).append(
    '<a href=\''+window.location.href +'\' class="btn btn-primary btn-block"' +
    '">Probeer je het nog eens? </a>');
}

// Toggle the disabled state on form elements
function toggleDisable(el) {
  el.prop('disabled', !el.prop('disabled'));
}
