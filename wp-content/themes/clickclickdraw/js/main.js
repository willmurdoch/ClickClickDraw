var $ = jQuery.noConflict(), myFilter = '', offset = 0, lazyLoop;
$(document).ready(function(){

  $('.burger-btn').click(function(){
    $(this).parents('nav#mainNav').toggleClass('expanded');
  });

  $('.work-filters a').click(function(e){
    e.preventDefault();
    if(!$(this).hasClass('active')){
      $(this).addClass('active').siblings().removeClass('active');
      myFilter = $(this).attr('data-category');
      $('.work-grid').css('height', $('.work-grid').height()).html('<div class="loading"></div>');
      filterContent('?ajax&category='+myFilter, '.work-grid', '.work-wrap');
      offset = 0;
    }
  });

  if($('.work-tile').length){
    lazyLoopInit();
  }

});

$(window).scroll(function(){
  $('.hero img').css('transform', 'translateY('+$(window).scrollTop()/1.1+'px)');
});

function lazyLoopInit(){
  lazyLoop = setInterval(function(){
    if($('.work-tile').length && $('.work-tile').last().offset().top - $(window).scrollTop() < $(window).height()){
      offset += 8;
      lazyLoad('.work-tile', '.work-grid', '.work-wrap');
    }
  }, 100);
}

function lazyLoad(child, content, wrapper){
  var myHeight = $(content).outerHeight(true);
  $(wrapper).height(myHeight);
  if(myFilter != ''){
    var myUrl = window.location.href+'?ajax&category='+myFilter+'&offset='+offset;
  }
  else var myUrl = window.location.href+'?ajax&offset='+offset;
  $.ajax({
    url: myUrl,
    type: 'GET',
    dataType: 'html',
    success: function(data){
      var newContent = $(data).find(child);
      if($(newContent).length > 0){
        $(content).append(newContent);
        myHeight = $(content).outerHeight(true);
        $(wrapper).height(myHeight);
      }
      else clearInterval(lazyLoop);
    }
  });
}

function filterContent(query, content, wrapper){
  var myUrl = window.location.href+query;
  var myHeight = $(content).outerHeight(true);
  $(wrapper).height(myHeight);
  $.ajax({
    url: myUrl,
    type: 'GET',
    dataType: 'html',
    success: function(data){
      var newContent = $(data).find(content);
      $('.work-grid').html(newContent.children()).removeAttr('style');
      myHeight = $(content).outerHeight(true);
      $(wrapper).height(myHeight);
      lazyLoopInit();
    }
  });
}
