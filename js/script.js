$(".file-input").on("change", function (e) {
  $(".file-name").text(e.target.files[0].name);
});

$(".confirm-delete").on("click", function () {
  if (confirm("Are you sure you want to delete?")) return true;
  else return false;
});


$('.lyrics-song').each(function(i) {
  const lines = $(this).text().split("\n").length;  
  const limitLen = 5;
  const $lyrics = $('.lyrics-song').eq(i);


  if( lines > limitLen ) {
    $(this).after('<a class="show-more">Show more</a>');

    $lyrics.next().on('click', function() {
      $lyrics.toggleClass('hide');

      if( $lyrics.hasClass('hide') )
        $(this).text('Show more');
      else
        $(this).text('Show less');
    });
  }
});