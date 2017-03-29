var $ = jQuery.noConflict();
$(document).ready(function(){
  $('.burger-btn').click(function(){
    $(this).parents('nav#mainNav').toggleClass('expanded');
  });
});
