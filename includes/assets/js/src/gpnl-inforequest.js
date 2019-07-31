var request_form_element = {};
$('.gpnl-request__form').on('submit', function () {

  request_form_element = this;
  console.log('Submitting...');

  var post_form_value = getFormObj(request_form_element);
  var form_config = 'request_form_object';
  post_form_value.action = 'request_form_process';
  post_form_value.nonce  = window[form_config].nonce;
  post_form_value.literaturecode  = window[form_config].literaturecode;

  console.log('Disabling...');
  toggleDisable($(request_form_element).find('*'));
  if (post_form_value.human !== '') {
    showErrorMessage(request_form_element);
    return;
  }

  console.log('Ajax posting...');
  $.ajax({
    type:    'POST',
    url:     window[form_config].ajaxUrl,
    data:    post_form_value,
    success: function(data) {
      // eslint-disable-next-line no-console
      console.log('^-^');
      console.log(data);
      $(request_form_element).find('*').hide();
      $(request_form_element).append('<h2>Hoera, je bent er bijna!</h2>');

      // // Send conversion event to the GTM
      // if (typeof dataLayer !== 'undefined') {
      //   dataLayer.push({
      //     'event'         : 'nieuwsbriefformulier',
      //     'conv_campaign' : post_form_value.literaturecode,
      //     'conv_action'   : 'registreer',
      //     'conv_label'    : 'registreer-nieuwsbrief'
      //   });
      // }

    },
    error: function(data){
      // If the backend sends an error, hide the thank element and show an error urging to try again
      // eslint-disable-next-line no-console
      console.log('o_o');
      console.log(data);
      showErrorMessage(request_form_element);
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
  console.log('Toggling disable');
  el.prop('disabled', !el.prop('disabled'));
  console.log('Disabled..');
}
