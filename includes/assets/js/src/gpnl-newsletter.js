var newsletter_form_element = {};
$('.gpnl-newsletter__form').on('submit', function () {

  newsletter_form_element = this;
  // Get the  parameter from the newsletter form and add the action and CSRF protection
  var post_form_value = getFormObj(newsletter_form_element);
  var form_config = 'newsletter_form_object_' + post_form_value['form_id'];

  post_form_value.action = 'newsletter_form_process';
  post_form_value.nonce  = window[form_config].nonce;
  post_form_value.marketingcode  = window[form_config].marketingcode;
  post_form_value.literaturecode  = window[form_config].literaturecode;
  post_form_value.screenid  = window[form_config].screenid;

  toggleDisable($(newsletter_form_element).find('*'));
  if (post_form_value.human !== '') {
    showErrorMessage(newsletter_form_element);
    return;
  }

  $.ajax({
    type:    'POST',
    url:     window[form_config].ajaxUrl,
    data:    post_form_value,
    success: function() {
      // eslint-disable-next-line no-console
      console.log('^-^');
      $(newsletter_form_element).find('*').hide();
      $('.gpnl-newsletter__title').html('Hoera, je bent er bijna! ');
      $('.gpnl-newsletter__description').html('<h4>Bevestig je aanmelding via de mail die je van ons ontvangt. Bedankt!  </h4>');

      // Send conversion event to the GTM
      if (typeof dataLayer !== 'undefined') {
        dataLayer.push({
          'event'         : 'nieuwsbriefformulier',
          'conv_campaign' : post_form_value.literaturecode,
          'conv_action'   : 'registreer',
          'conv_label'    : 'registreer-nieuwsbrief'
        });
      }

    },
    error: function(){
      // If the backend sends an error, hide the thank element and show an error urging to try again
      // eslint-disable-next-line no-console
      console.log('o_o');
      showErrorMessage(newsletter_form_element);
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

// Toggle the disabled state on form elements
function toggleDisable(el) {
  el.prop('disabled', !el.prop('disabled'));

}

function showErrorMessage(newsletter_form_element) {
  $(newsletter_form_element).find('*').hide();
  $('.gpnl-newsletter__title').html('Oh nee!');
  $('.gpnl-newsletter__description').html('<p>Hier gaat helaas iets mis, sorry. </p>');
  $(newsletter_form_element).append(
    '<a href=\''+window.location.href +'\' class="btn btn-primary btn-block"' +
    '">Probeer je het nog eens? </a>');
}

$(document).ready(function() {
  let forms = $('.gpnl-newsletter__form');

  $.each(forms, function(){
    let form = this;
    let form_id = $(form).find('[name="form_id"]').val();
    let form_config = 'newsletter_form_object_' + form_id;
    $.ajax({
      type:    'POST',
      url:     window['p4_vars'].ajaxurl,
      data:    {'action' : 'request_id'},
      success: function(response) {
        // eslint-disable-next-line no-console
        window[form_config].nonce = response.data.nonce;
        toggleDisable($(form).find(':submit'));
      },
      error: function(){
        // If the backend sends an error, hide the thank element and show an error urging to try again
        // eslint-disable-next-line no-console
        console.log('o_o');
        showErrorMessage(form);
      }
    });
  });
});
