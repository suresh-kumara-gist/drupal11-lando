jQuery(document).ready(function($){

  //Mobile menu toggle
  $('#menu-btn').click(function(e) {
    e.preventDefault();
    $('#slidemenu').addClass('active');
    $('body').append('<div class="overlay"></div>');
  });

  $('#menu-toggle').click(function(e) {
    e.preventDefault();
    $('#slidemenu').removeClass('active');
    $('#page').removeClass('overlay');
    $('body').find('.overlay').remove();
  })

  // Masonry Grid Layout
  $('.sbgrid .view-frontpage .view-content, .sbgrid .view-taxonomy-term .view-content').addClass('grid');
  $('.sbgrid .view-frontpage .view-content, .sbgrid .view-taxonomy-term .view-content').prepend('<div class="grid-sizer"></div>');
  $('.sbgrid .view-frontpage .view-content .views-row, .sbgrid .view-taxonomy-term .view-content .views-row').addClass('grid-item');

});