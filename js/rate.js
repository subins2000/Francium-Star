$(function(){
  $(".Fr-star.userChoose").Fr_star(function(rating){
    $.post("ajax_rate.php", {'id' : 'index_page', 'rating': rating}, function(){
      alert("Rated " + rating + " !!");
    });
  });
});
