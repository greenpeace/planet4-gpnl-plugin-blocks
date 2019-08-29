var request_form_element = {};
var check_form_element = {};
var form_config = 'request_form_object';

$(document).ready(function() {
  let auth = new URLSearchParams(window.location.search).has('e');
  if ( '1' === window[form_config].hider ) {
    $('.inforequest__wrapper').nextAll().hide();
    $('.inforequest__title').html('<h3>Lesmateriaal downloaden</h3>');
    $('.inforequest__title').after('<p class="inforequest__message">Registreer of log in om het lesmateriaal te downloaden.</p>');
  }
  if (null !== readCookie('gpnl_education') || auth ){
    $('.inforequest__title').html('<h3>Welkom terug!</h3>');
    $('.inforequest__message').html('<p>Je kan nu gebruikmaken van de lesmaterialen.</p>');
    $('.inforequest__wrapper').nextAll().show(1000);
  }
  else{
    $('.hideshowbtn').show(1000);
  }
});

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
      if (null !== readCookie('greenpeace')){
        createCookie('gpnl_education', 1, 365);
        if (window[form_config].hider === '1') {
          $('.inforequest__wrapper').nextAll().show(1000);
          $('.inforequest__message').hide(1000);

        }
      }
      else{
        enableDownloadlinks();
      }

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
      $(check_form_element).find('*').hide(1000);
      $('.inforequest__title').html('<h3>Welkom terug!</h3>');
      $(check_form_element).prepend('<p>Je kan nu gebruikmaken van de lesmaterialen.</p>');
      if (null !== readCookie('greenpeace')){
        createCookie('gpnl_education', 1, 365);
        if (window[form_config].hider === '1') {
          $('.inforequest__wrapper').nextAll().show(1000);
          $('.inforequest__message').hide(1000);
        }
      }
      else {
        enableDownloadlinks();
      }

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

function createCookie(name, value, days) {
  var date = new Date();
  date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
  document.cookie = encodeURI(name) + '=' + encodeURI(value) + ';domain=.' + document.domain + ';path=/;' + '; expires=' + date.toGMTString();
}

function readCookie(name) {
  var nameEQ = name + '=';
  var ca = document.cookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) === ' ') {
      c = c.substring(1, c.length);
    }
    if (c.indexOf(nameEQ) === 0) {
      return c.substring(nameEQ.length, c.length);
    }
  }
  return null;
}

function enableDownloadlinks() {
  $('.cover-card-column').each(function(){
    let cover = this;
    $(cover).find('a').each(function(){
      let link = this;
      let href = $(this).attr('href');
      $(link).attr('href', href + '?e=1');
    });
  });
}
