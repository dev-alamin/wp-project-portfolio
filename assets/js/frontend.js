
jQuery(document).ready(function($) {
    // Use the dynamically passed classes for the itemSelector
    var itemSelector = '.' + isotopeSettings.uniqueClassSlugs.join(', .');
    // Initialize Isotope
    var $grid = $('.project-content').isotope({
    itemSelector: itemSelector,
    layoutMode: 'fitRows'
    });

    // Click event for filter buttons
    $('.button-group').on('click', 'button', function() {
    var filterValue = $(this).attr('data-filter');
    $grid.isotope({ filter: filterValue });
    });
       
});

Fancybox.bind('[data-fancybox]', {
    thumbs : {
        autoStart : true
      }
  });  
