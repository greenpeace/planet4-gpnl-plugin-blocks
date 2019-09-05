// Vul het modal met de juiste data
$('#electionModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  var id = button.data('id');
  var modal = $(this);
  var config = window['election_object_' + id];

  modal.find('.modal-title').text('Stem op ' + config.title);
  modal.find('.modal-subtitle').text(config.subtitle);
  modal.find('.modal-body').text( config.description );
  modal.find('input[name=\'form_id\']').val(id);
  modal.find('input[name=\'marketingcode\']').val(config.mcode);
});

$(document).ready(function() {
  // convert an element to slider using slick js
  function slickify(element) {
    let numOptions = $('#electionModal').data('num');
    $(element).slick({
      infinite: false,
      mobileFirst: true,
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: true,
      dots: true,
      centerMode: true,
      centerPadding: '50px',
      initialSlide: 3,
      responsive: [
        {
          breakpoint: 992,
          settings: {slidesToShow: numOptions}
        },
        {
          breakpoint: 768,
          settings: {slidesToShow: 3}
        },
        {
          breakpoint: 576,
          settings: {slidesToShow: 2}
        }
      ]
    });
  }

  slickify('.election-options');

  // Hide the consentbox if the opt=in url var is set. (this is for set for ie mailings)
  var opt = getUrlVars()['opt'];
  this.url_cg = getUrlVars()['cg'];
  var isfacebook = document.referrer.indexOf('facebook') !== -1;
  var istwitter = document.referrer.indexOf('twitter') !== -1;

  let clangct = getUrlVars()['clangct'];

  if (clangct != undefined) {
    $.ajax({
      url: '/wp-content/plugins/planet4-gpnl-plugin-blocks/includes/assets/js/clang-landing.js?clangct=' + clangct,
      dataType: 'script',
    });
  }

  if (opt != undefined && $('.optin').length != 0 && opt == 'in') {
    $('.optin').hide();
    $('.gpnl-petition-checkbox').prop('checked', true);

    // Here we check if we know the mail being entered if the opt=in var is set.
    // If we don't know the entered mail we should display the consentbox
    $('input[name=\'mail\']').keyup(function () {
      // First loosely check if the value in the mailinput is indeed a mailadress, if it indeed is, we pass it onto the database checker
      // eslint-disable-next-line
      var mailRegex = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{3,})$/i;
      if (mailRegex.test(this.value)) {
        let mail = encodeURIComponent(this.value);
        $.ajax({
          type: 'GET',
          url: 'https://secure.greenpeacephp.nl/kenikdeze.php?mail=' + mail,
          complete: function (data) {
            // If we do not know the email, we display the consentbox again
            if (data.responseText.includes('false')) {
              $('.optin').show();
              $('.gpnl-petition-checkbox').prop('checked', false);
            }
          }
        });
      }
    });

    if (!(istwitter || isfacebook) && this.url_cg != undefined) {
      prefillByGuid('prefill', this);
    }
  }

  let global_config = window['election_object'];
  // totaal aantal stemmen opvragen
  let form = {
    tellerCode: global_config.analytics_campaign,
    type: 'total'
  };
  prefillByGuid('teller', form);

  if (!Number(global_config.hideresults)) {
    // opvragen stemmen per opties (verborgen tot na stemmen)
    let counters = $('.subcounter');
    let halfSize = Math.floor(counters.length / 2);
    counters = counters.slice(0, halfSize);
    $(counters).each(function () {
      let id = $(this).data('id');
      let counter_config = window['election_object_' + id];
      this.id = id;
      this.type = 'subtotal';
      this.tellerCode = counter_config.mcode;
      prefillByGuid('teller', this);
    });
  }
});


$('.gpnl-petitionform').on('submit', function () {


  var petition_form_element = this;
  // Get the  parameter from the petition form and add the action and CSRF protection
  var post_form_value = getFormObj(petition_form_element);
  var global_config = window['election_object'];


  post_form_value.action = 'petition_form_process';
  post_form_value.nonce  = global_config.nonce;
  post_form_value.ad_campaign = global_config.ad_campaign;

  // Disable the form so people can't resubmit
  toggleDisable($(petition_form_element).find('*'));
  toggleDisable($('.btn-submit'));

  // Do a ajax call to the wp_admin admin_ajax.php,
  // which triggers processing function in the petition block
  $.ajax({
    type:    'POST',
    url:     global_config.ajaxUrl,
    data:    post_form_value,
    success: function(data) {
      // eslint-disable-next-line no-console
      console.log('^-^');

      const {phone, consent} = post_form_value;
      let optin = ('on' === consent);
      let phoneFilled = ('' !== phone);
      let phonetmp = data['data']['phoneresult'];
      let phoneResult = (false === phonetmp || true === phonetmp) ? phonetmp : null;

      let mailtmp = data['data']['mailresult'];
      let mailResult = (false === mailtmp || true === mailtmp) ? mailtmp : null;

      // Send conversion event to the GTM
      if (typeof dataLayer !== 'undefined') {
        // New conversion object
        dataLayer.push(
          {
            'event': 'RegisterComplete',
            'campaign': global_config.analytics_campaign,
            'action': 'verkiezing',
            'emailKnown': mailResult,
            'telKnown': phoneResult,
            'telFilled': phoneFilled,
            'optin': optin,
          });

        dataLayer.push({
          'event': 'petitiebutton',
          'conv_campaign': global_config.analytics_campaign,
          'conv_action': 'verkiezing',
          'conv_label': 'registreer'
        });

        // if consent was given by entering phonenumber
        if (post_form_value.phone !== '') {
          // Send conversion event to the GTM
          dataLayer.push({
            'event': 'petitiebutton',
            'conv_campaign': global_config.analytics_campaign,
            'conv_action': 'telnr',
            'conv_label': 'Ja'
          });
          // If an ad campaign is run by an external company fire the conversiontracking
          // if (global_config.ad_campaign === 'SB') {
          //   fbq('track', 'Lead');
          //   // if it is run by social blue, also deduplicate
          //   socialBlueDeDuplicate(post_form_value['mail'], data['data']['phone'], global_config.apref);
          // } else if (global_config.ad_campaign === 'JA') {
          //   fbq('track', global_config.jalt_track);
          // }
        } else {
          dataLayer.push({
            'event': 'petitiebutton',
            'conv_campaign': global_config.analytics_campaign,
            'conv_action': 'telnr',
            'conv_label': 'Nee'
          });
        }
      }

      // cardflip the card, positionattribute flips to make sure no problems arises with different lengths of the front and back of the card, finally hide the front
      cardflip(petition_form_element);



      // Bedankt melding verbergen na sluiten van modal window?
      $('#electionModal').on('hidden.bs.modal', function () {
        $($('.gpnl-petition-thank')[0]).hide();
      });
      let clangct=getUrlVars()['clangct'];
      if(clangct != undefined){clang.conversion.track();}
    },
    error: function(){
      // If the backend sends an error, hide the thank element and show an error urging to try again
      // eslint-disable-next-line no-console
      console.log('o_o');
      var cardback = $(petition_form_element.parentNode.nextElementSibling);
      cardback.find('*').hide('');
      cardback.append('<p>Sorry, er gaat momenteel iets fout, probeer het nu of later opnieuw.</p>');
      cardback.append(
        '<a href=\''+window.location.href +'\' class="btn btn-primary btn-block"' +
        '">Probeer opnieuw</a>');
      cardflip(petition_form_element);
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

// toggle the flipped class for the card parent
function cardflip(el) {
  let element = $(el);
  let parent =  el.parentNode;
  let card = $(el.parentNode.parentNode);

  // first hide the signing button
  $('.btn-submit').toggle();
  $(element.find('.policies')).toggle();
  // then cardflip the position attribute on the front and back of the card to support different lengths front and back
  $(parent.nextElementSibling).css( 'position', flip_positionattribute(parent.nextElementSibling));
  $(parent).css( 'position', flip_positionattribute(parent));
  // then cardflip the card
  card.toggleClass('flipped');

  // after animation hide the front
  card.one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend',
    function() {
      $(parent).toggle();
      card.off('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend');
    });

}

function flip_positionattribute (el){
  return $(el).css('position') === 'absolute' ? 'relative' : 'absolute';
}

/* eslint-disable */
function fireShareEvent (platform){
  if (typeof dataLayer !== 'undefined') {
    let global_config = window['election_object'];
    dataLayer.push({
      'event'         :'petitiebutton',
      'conv_campaign' :global_config.analytics_campaign,
      'conv_action'   :'verkiezing',
      'conv_label'    :'share_' + platform});
  }
  else{
    console.log('GTM not defined?');
  }
}
/* eslint-enable */

/* eslint-disable */
// Send the supporter data to for deduplication
function socialBlueDeDuplicate(email, phone, apref) {
  var apHost = ('https:' == document.location.protocol ? 'https://secure.event.' : 'http://www.') + 'affiliatepartners.com';
  var apSrc = '/js/ApConversionPixelV1.1.js';

  _apOrderValue = 0;
  _apOrderNumber = 'email=' + email + '-telefoonnumer=' + phone;
  _apRef = apref;

  try {
    $('body').append(unescape('%3Cscript src=\'' + apHost + apSrc + '\' type=\'text/javascript\'%3E%3C/script%3E'));
  } catch (err) {
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = apHost + apSrc;
    document.getElementsByTagName('head')[0].appendChild(script);
  }
}
/* eslint-enable */

// REFACTOR IE11 doesn't support UrlSearchParams, so custom UrlParam function.
// 	Consider polyfilling it now? or wait until we drop IE11 support and switch then?
function getUrlVars(){
  var vars = [],
    hash;
  var uri = window.location.href.split('#')[0];
  var hashes = uri.slice(window.location.href.indexOf('?') + 1).split('&');
  for(var i = 0; i < hashes.length; i++){
    hash = hashes[i].split('=');
    vars.push(hash[0]);
    vars[hash[0]] = hash[1];
  }
  return vars;
}

// TODO add language preference detection for better formatting of numbers
function showCounter(num_responses, counter){
  let total = $('#counter_total').data('num');
  $(counter).find('.counter').show();
  let perc_slider = Math.round(100 *(num_responses / total));

  $(counter).find('.counter__slider').animate({width: perc_slider+'%', opacity: 1}, 2000, 'easeInOutCubic');
  $(counter).find('.counter__gettext').html(perc_slider + '% van de stemmen');
  $(counter).find('.counter__text').fadeIn(2000);
}

// soap request naar charibase
function prefillByGuid(type, form) {
  var config = window['election_object'];
  var xmlhttp = new XMLHttpRequest();
  var query_id = '';
  var requestValue = '';
  // waar gaat het om? Een teller of een prefill?
  if (type === 'prefill') {
    query_id = 'GET_FIRST_NAME_EMAIL';
    requestValue = form.url_cg;
  } else if (type === 'teller') {
    query_id = 'CAMP_TTL_PETITIONS';
    requestValue = form.tellerCode;
  }
  xmlhttp.open('POST', 'https://www.mygreenpeace.nl/GPN.WebServices/WIDSService.asmx', true);
  // build SOAP request
  var sr = '<' + '?' + 'xml version="1.0" encoding="utf-8"?>' +
    '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">' +
    '  <soap:Body>' +
    '    <WatIsDestand xmlns="http://www.mygreenpeace.nl/GPN.WebServices/">' +
    '      <queryId>' + query_id + '</queryId>' +
    '      <requestValue>' + requestValue + '</requestValue>' +
    '    </WatIsDestand>' +
    '  </soap:Body>' +
    '</soap:Envelope>';

  xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) { // 200 = OK
      var response = xmlhttp.responseXML.getElementsByTagName('WatIsDestandResult')[0].firstChild.nodeValue;
      if (response !== '') {
        var res = response.split('|');
        // waar gaat het om? Een teller of een prefill
        if (type === 'prefill') {
          var naam = res[0];
          $(form).find('input[name=\'name\']').val(naam);
          var email = res[1];
          $(form).find('input[name=\'mail\']').val(email);
        } else if (type === 'teller') {
          if (Number(response) >= config.counter_min) {
            if (form.type === 'total'){
              $('#counter_total').data('num', response);
              $('#counter_total').text(response + ' mensen hebben al gestemd.');
            }
          }
        }
      }
    }
  };
  // Send the POST request
  xmlhttp.setRequestHeader('Content-Type', 'text/xml');
  xmlhttp.setRequestHeader('SOAPAction', 'http://www.mygreenpeace.nl/GPN.WebServices/WatIsDestand');
  xmlhttp.send(sr);
  // send request
}
