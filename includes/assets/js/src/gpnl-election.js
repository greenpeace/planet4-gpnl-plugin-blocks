$('#electionModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  var id = button.data('id');
  var modal = $(this);
  var config = 'election_object_' + id;

  modal.find('.modal-title').text('Stem op ' + window[config].title);
  modal.find('.modal-body').text( window[config].description );
  console.log();
});
