
jQuery(document).ready(function($) {
    // Use the dynamically passed classes for the itemSelector
    var itemSelector = '.' + portofolioObject.uniqueClassSlugs.join(', .');
    // Initialize Isotope
    var $grid = $('.project-content').isotope({
        itemSelector: '.grid-item',
        masonry: {
          columnWidth: 100,
          horizontalOrder: true,
        },
    itemSelector: itemSelector,
    layoutMode: 'fitRows'
    });

    // Click event for filter buttons
    $('.button-group').on('click', 'button', function() {
        var filterValue = $(this).attr('data-filter');
        $grid.isotope({ filter: filterValue });
    });

    // Add click event handler to all buttons with class 'filter-button'
    $('.project-portfolio-wrapper .button-group button').on('click', function() {
        // Remove 'active' class from all buttons
        $('.project-portfolio-wrapper .button-group button').removeClass('active');

        // Add 'active' class to the clicked button
        $(this).addClass('active');
        });   

        var postsToShow = 10; // Number of posts to show per click
        var offset = 15; // Initial offset for fetching posts
        var $loadMoreButton = $('#load-more-button');
        // Function to load more posts
        function loadMorePosts() {
            $.ajax({
                url: portofolioObject.adminUrl,
                type: 'POST',
                data: {
                    action: 'wp_project_portfolio', // The PHP function to handle the request
                    offset: offset,
                    posts_to_show: postsToShow,
                    nonce: portofolioObject.none,
                },
                beforeSend: function(xhr) {
                    // Set the nonce in the AJAX request headers
                    xhr.setRequestHeader('X-WP-Nonce', portofolioObject.nonce);
                    $loadMoreButton.text('Loading...'); // Show loading text on the button
                },
                success: function(response) {
                    if (response) {
                      var $newPosts = $(response); // Convert the response HTML to jQuery object
                      $('.project-content').append($newPosts); // Append the new posts after the existing ones
                      offset += postsToShow;
            
                      // Check if there are more posts to show
                      if (response.trim() === '') {
                        $loadMoreButton.remove(); // No more posts to show, remove the load more button
                      } else {
                        $loadMoreButton.text('Load More'); // Restore the button text
                      }
            
                      // Reload Isotope items and re-layout
                      $('.project-content').isotope('appended', $newPosts).isotope('layout');
                      $(window).trigger('resize');
                    } else {
                      $loadMoreButton.remove(); // No more posts to show, remove the load more button
                    }
                  },
                error: function(errorThrown) {
                    console.log(' Error: ' + errorThrown);
                }
            });
        }

        // Load more button click event
        $('#load-more-button').on('click', function() {
            loadMorePosts();
        });

        // Initially hide the "Load More" button if there are no more posts
        if ($('#project-content').children().length < postsToShow) {
            $('#load-more-button').hide();
        }

    // Function to handle sorting
    function sortProjects(sortOrder) {
        var $projectContent = $('#project-content');
        var $items = $projectContent.children('.col-lg-4');
    
        var categoryFilter = ''; // Store the current category filter
        if ($projectContent.data('isotope')) {
            categoryFilter = $projectContent.data('isotope').options.filter;
        }
    
        $items.sort(function (a, b) {
            var titleA = $(a).find('.project-title').text().toUpperCase();
            var titleB = $(b).find('.project-title').text().toUpperCase();
    
            if (sortOrder === 'asc') {
                return (titleA < titleB) ? -1 : (titleA > titleB) ? 1 : 0;
            } else {
                return (titleA > titleB) ? -1 : (titleA < titleB) ? 1 : 0;
            }
        });
    
        // Restore the category filter before reapplying Isotope
        if (categoryFilter) {
            $items.hide(); // Hide all items temporarily
            $projectContent.html($items); // Replace the existing content with the sorted items
            $projectContent.isotope('destroy'); // Destroy the previous Isotope instance
            $projectContent.isotope({ // Create a new Isotope instance
                itemSelector: '.col-lg-4',
                percentPosition: true,
                masonry: {
                    columnWidth: '.col-lg-4'
                },
                filter: categoryFilter // Apply the category filter
            });
            $(window).trigger('resize');
        } else {
            $projectContent.html($items); // Replace the existing content with the sorted items
            $projectContent.isotope('destroy'); // Destroy the previous Isotope instance
            $projectContent.isotope({ // Create a new Isotope instance
                itemSelector: '.col-lg-4',
                percentPosition: true,
                masonry: {
                    columnWidth: '.col-lg-4'
                }
            });
            $(window).trigger('resize');
        }
    }
    

    // Function to handle filtering
    function filterProjects(categorySlug) {
        $.ajax({
            url: portofolioObject.adminUrl, // WordPress AJAX URL
            type: 'POST',
            data: {
                action: 'filter_projects',
                category_slug: categorySlug
            },
            success: function (response) {
                if (response) {
                    var $newItems = $(response); // Convert the response to jQuery object
                    var $projectContent = $('#project-content');
                    $projectContent.html($newItems); // Replace the existing content with the new items
                    $projectContent.isotope('destroy'); // Destroy the previous Isotope instance
                    $projectContent.isotope({ // Create a new Isotope instance
                        itemSelector: '.col-lg-4',
                        percentPosition: true,
                        masonry: {
                            columnWidth: '.col-lg-4'
                        }
                    });
                    $(window).trigger('resize');
                }
            }
        });
    }


    // Sort by title select change event
    $('#sort-by-title').on('change', function () {
        var sortOrder = $(this).val();
        sortProjects(sortOrder);
    });

    // Sort by category select change event
    $('#sort-by-category').on('change', function () {
        var categorySlug = $(this).val();
        filterProjects(categorySlug);
    });



});


Fancybox.bind('[data-fancybox]', {
    thumbs : {
        autoStart : true
    }
});  