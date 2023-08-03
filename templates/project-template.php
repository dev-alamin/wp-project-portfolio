<?php
/**
 * Template Name: Portfolio Project 
 *
 * This is a custom page template created by the WP Project Portfolio plugin.
 * You can use this template for specific pages in your WordPress site.
 */

get_header(); ?>

<div class="project-portfolio-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <select id="sort-by-title">
                    <option value="asc">Sort by Title (A-Z)</option>
                    <option value="desc">Sort by Title (Z-A)</option>
                </select>

                <select id="sort-by-category">
                    <option value="all">All Categories</option>
                    
                    <?php
                        $categories = get_terms(array(
                        'taxonomy' => 'project_cat', // Replace 'project_cat' with your custom taxonomy slug
                        'hide_empty' => true,
                        ));

                    if (!empty($categories) && !is_wp_error($categories)) {
                        foreach ($categories as $category) {
                        echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
                        }
                    }
                    ?>
                </select>


                <div class="button-group mt-3" data-filter-group="color">
                    
                    <?php
                    $args = array(
                        'post_type'      => 'portfolio_project',
                        'post_status'    => 'publish',
                        'posts_per_page' => 15,
                    );

                    $project = new WP_Query( $args );

                    $unique_categories = array();
                    $project_ids = array(); // Store post IDs here
                    $category_counts = [];

                    if ( $project->have_posts() ) {
                        while ( $project->have_posts() ) {
                            $project->the_post();
                            $terms = get_the_terms( get_the_ID(), 'project_cat' ); // Replace 'project_cat' with your custom taxonomy slug
                           
                            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                                foreach ( $terms as $term ) {
                                    $unique_categories[ $term->slug ] = $term->name;

                                    if ( isset( $category_counts[ $term->slug ] ) ) {
                                        $category_counts[ $term->slug ]++;
                                    } else {
                                        $category_counts[ $term->slug ] = 1;
                                    }
                                }
                            }

                            // Store post IDs
                            $project_ids[] = get_the_ID();
                        }
                        wp_reset_postdata();
                    }

                    echo '<button class="active" data-filter="">' . __( 'All - ' . array_sum($category_counts), 'wp-project-portfolio') . '</button>';

                    if ( ! empty( $unique_categories ) ) {
                        foreach ( $unique_categories as $category_slug => $category_name ) {
                            $category_count = $category_counts[ $category_slug ];

                            echo '<button data-filter=".' . esc_attr( $category_slug ) . '">' . esc_html( $category_name ) . ' (' . __($category_count) . ')' . '</button>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="row project-content" id="project-content">
            <?php
            // Use stored post IDs to fetch the post content
            if ( ! empty( $project_ids ) ) {
                foreach ( $project_ids as $project_id ) {
                    $terms = get_the_terms( $project_id, 'project_cat' ); // Replace 'project_cat' with your custom taxonomy slug

                    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                        foreach ( $terms as $term ) {
                            ?>
                            <div class="col-lg-4 <?php echo esc_attr( $term->slug ); ?>">
                                <div class="portfolio-single-project">
                                    <?php
                                    $post = get_post( $project_id );
                                    setup_postdata( $post ); ?>
                                    <?php
                                    $caption = '<h4>' . get_the_title(get_the_ID()) . '</h4>';
                                    $caption .= '<p>' . get_the_excerpt( get_the_ID() );
                                    $caption .= '<a class="readmore" href="' . esc_url( get_the_permalink(get_the_ID() ) ) . '">';
                                    $caption .= __( 'Read more', 'wp-project-portfolio' );
                                    $caption .= '</a>';

                                    $remove_cont_tags = wp_strip_all_tags( get_the_excerpt() );
                                    $shorter_cont = wp_trim_words( $remove_cont_tags, 8, '' );
                                    ?>

                                    <a class="data-fancybox-trigger"
                                    data-fancybox
                                    href="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>" 
                                    data-caption='<?php echo $caption; ?>'>
                                        <div class="text">
                                            <?php the_title( '<h4 class="project-title">', '</h4>' ); ?>
                                            <p><?php echo esc_html__( $shorter_cont ); ?></p>
                                            <span class="open-popup"><?php _e( 'View', 'wp-project-portfolio' ); ?></span>
                                        </div>
                                        <div class="thumb" style="background-image: url(<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large') ?>);">

                                        </div>
                                    </a>

                                </div>
                            </div>
                            <?php
                        }
                    }
                }
            } else {
                echo 'No projects found.';
            }
            ?>
        </div>
        <button id="load-more-button" class="mb-5">Load More</button>
    </div>
</div>
<?php 
get_footer();
?>