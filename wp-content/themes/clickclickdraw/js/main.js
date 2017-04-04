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
      filterContent('?ajax&category='+myFilter, '.work-grid');
      offset = 0;
    }
  });

  if($('.work-tile').length){
    lazyLoopInit();
  }

});

function lazyLoopInit(){
  lazyLoop = setInterval(function(){
    if($('.work-tile').length && $('.work-tile').last().offset().top - $(window).scrollTop() < $(window).height()){
      offset += 8;
      lazyLoad('.work-tile', '.work-grid');
    }
  }, 100);
}

function lazyLoad(child, content){
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
      }
      else clearInterval(lazyLoop);
    }
  });
}

function filterContent(query, content){
  var myUrl = window.location.href+query;
  $.ajax({
    url: myUrl,
    type: 'GET',
    dataType: 'html',
    success: function(data){
      var newContent = $(data).find(content);
      $('.work-grid').html(newContent.children()).removeAttr('style');
      lazyLoopInit();
    }
  });
}
