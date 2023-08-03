<?php
/**
 * Template Name: Portfolio Project
 *
 * This is a custom page template created by the WP Project Portfolio plugin.
 * You can use this template for specific pages in your WordPress site.
 */

use \WPPP\Frontend\Component;

get_header();

$component = new Component();
$loop = $component->loop();

$unique_categories = $loop['categories'];
$category_counts = $loop['cat_counts'];
$project_ids = $loop['ids'];
?>

<div class="project-portfolio-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php
                echo $component->sort();
                echo $component->filter();
                ?>
                <div class="button-group mt-3" data-filter-group="color">
                    
                    <?php echo '<button class="active" data-filter="">' . __( 'All', 'wp-project-portfolio') . '</button>';

                    if (!empty($unique_categories)) {
                        foreach ($unique_categories as $category_slug => $category_name) {
                            $category_count = $category_counts[$category_slug];

                            echo '<button data-filter=".' . esc_attr($category_slug) . '">' . esc_html($category_name) . '</button>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="row project-content" id="project-content">
            <?php

            // Use stored post IDs to fetch the post content
            if (!empty($project_ids)) {
                foreach ($project_ids as $project_id) {
                    $terms = get_the_terms($project_id, 'project_cat'); // Replace 'project_cat' with your custom taxonomy slug

                    if (!empty($terms) && !is_wp_error($terms)) {
                        foreach ($terms as $term) {
                            ?>
                            <div class="col-lg-4 <?php echo esc_attr($term->slug); ?>">
                                <div class="portfolio-single-project">
                                    <?php 
                                    $post = get_post($project_id);
                                    setup_postdata($post); ?>
                                    <a class="data-fancybox-trigger" data-fancybox href="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>" data-caption='<?php echo $component->caption(); ?>'>
                                        <?php
                                            echo $component->content(); // Hover content
                                            echo $component->thumbnail(); // Thumbnail ?>
                                    </a>

                                </div>
                            </div>
                            <?php
                        }
                    }
                }
            } else {
                _e( 'No projects found.', 'wp-project-portfolio' );
            }
            ?>
        </div>
        <button id="load-more-button btn btn-primary" class="mb-5">
            <?php _e('Load More', 'wp-project-portfolio'); ?>
        </button>
    </div>
</div>
<?php
get_footer();
?>
