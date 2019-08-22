$('#electionModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  var id = button.data('id');
  var modal = $(this);
  var config = 'election_object_' + id;

  modal.find('.modal-title').text('Stem op ' + window[config].title);
  modal.find('.modal-subtitle').text(' - ' + window[config].subtitle);
  modal.find('.modal-body').text( window[config].description );
});

$('.btn-submit').on('click', function () {
  let btn = $('.btn-submit');
  let form = $('.gpnl-petitionform').first();
  form.show(500);
  btn.text('Afronden');
});

// on modal close
//  reset btn
//  reset form

jQuery(function ($) {

  // convert an element to slider using slick js
  function slickify(element) {
    $(element).slick({
      infinite:       false,
      mobileFirst:    true,
      slidesToShow:   1,
      slidesToScroll: 1,
      arrows:         true,
      dots:           true,
      centerMode:     true,
      centerPadding:  '20px',
      initialSlide:   3,
      responsive: [
        {
          breakpoint: 992,
          settings: { slidesToShow: 5 }
        },
        {
          breakpoint: 768,
          settings: { slidesToShow: 3 }
        },
        {
          breakpoint: 576,
          settings: { slidesToShow: 2 }
        }
      ]
    });
  }

  slickify('.election-options');

});
