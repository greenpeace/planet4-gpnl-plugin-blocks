var request_form_element = {};
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
      $(request_form_element).find('*').hide();
      $(request_form_element).append('<h2>Hoera, je bent er bijna!</h2>');

    },
    error: function(){
      // If the backend sends an error, hide the thank element and show an error urging to try again
      showErrorMessage(request_form_element);
    }
  });
});

$('.gpnl-check__form').on('submit', function () {
  let mail = $('.gpnl-check__form input[name="mail"]').val();
  $.ajax({
    type: 'GET',
    url: 'https://secure.greenpeacephp.nl/kenikdeze.php?mail=' + mail,
    complete: function (data) {
      // If we do not know the email, we display the consentbox again
      if (data.responseText.includes('false')) {
        console.log("Ik ken deze niet: " + mail);
      }
      else {
        console.log("Ik ken " + mail);
      }
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
