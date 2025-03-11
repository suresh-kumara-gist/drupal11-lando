jQuery(document).ready(function($){
	
	var $grid;
	$grid = $('.grid');

  $('.grid').masonry({
	  itemSelector: '.grid-item',
    columnWidth: '.grid-sizer',
    percentPosition: true,
    gutter: 20,
    transitionDuration: '0.2s'
	});

});
	
