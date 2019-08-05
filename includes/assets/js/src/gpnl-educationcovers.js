var tags = [];
var covers = $('.cover-card-column');
$( '.tagselector input[type=checkbox]' ).on( 'click', function () {
  tags = $('.tagselector').find('input[type="checkbox"]:checked')
    .map(function() { return this.id; })
    .get();
  if (tags.length == 0){
    covers.show();
  }
  else {
    filterCovers(tags);
  }
} );

function filterCovers(tags) {
  covers.hide();
  covers.map(function(){
    let cover = this;
    $(tags).each(function () {
      if ($(cover).data('tags').includes(this)) {
        $(cover).show();
      }
    });
  });

}
