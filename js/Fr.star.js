function log(m){
    console.log(m);
}

(function(window){
  window.Fr = window.Fr || {};
  
  Fr.star = function(elem, rated_callback){
    rated_callback = rated_callback || function(){};
    
    elem.onmousemove = function(e) {
      if(elem.getAttribute("data-disabled") == null){
        var xCoor = e.offsetX;
        var width = elem.offsetWidth;
        
        percent = (xCoor/width) * 100;
        if(percent < 101){
          rating_decimal = ("" + (percent / 100) * 5 + "").substr(0, 3);
          if(rating_decimal.substr(-2) == ".9"){
            rating_decimal = Math.round(rating_decimal, 2);
          }
          elem.setAttribute("data-title", "Set a rating of " + rating_decimal);
          
          elem.querySelector(".Fr-star-value").style.width = percent + "%";
        }
      }
    };
    
    elem.onmouseout = function(){
      original_rating = elem.getAttribute("data-rating");      
      percent = (original_rating / 5) * 100;
      elem.querySelector(".Fr-star-value").style.width = percent + "%";
      
      elem.removeAttribute("data-disabled");
    };
    
    elem.onclick = function(){
      width = elem.querySelector(".Fr-star-value").style.width.replace("%", "");
      rating = ("" + (width/100) * 5 + "").substr(0, 3);
      
      if(rating.substr(-2) == ".9"){
        rating = Math.round(rating, 2);
      }
      
      elem.setAttribute("data-rating", rating);
      elem.setAttribute("data-disabled", 1);
      rated_callback(rating);
    };
  };
})(window);

(function($){  
  $.fn.Fr_star = function(rated_callback){
    return this.each(function(){
      Fr.star($(this).get(0), rated_callback);
    });
  };
})(jQuery);
