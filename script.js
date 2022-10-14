$(document).ready(function(){
  $(window).scroll(function(){
    if ($(window).scrollTop() > 150){
        $('.stricky-footer').addClass( "show-footer",100);
    }
    else {
    $('.stricky-footer').removeClass("show-footer",100);
    }
 });
});