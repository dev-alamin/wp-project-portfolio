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
                <div class="button-group" data-filter-group="color">
                    <button data-filter="">All</button>
                    <?php
                    $args = array(
                        'post_type'      => 'portfolio_project',
                        'post_status'    => 'publish',
                        'posts_per_page' => -1,
                    );

                    $project = new WP_Query( $args );

                    $unique_categories = array();
                    $project_ids = array(); // Store post IDs here

                    if ( $project->have_posts() ) {
                        while ( $project->have_posts() ) {
                            $project->the_post();
                            $terms = get_the_terms( get_the_ID(), 'project_cat' ); // Replace 'project_cat' with your custom taxonomy slug

                            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                                foreach ( $terms as $term ) {
                                    $unique_categories[ $term->slug ] = $term->name;
                                }
                            }

                            // Store post IDs
                            $project_ids[] = get_the_ID();
                        }
                        wp_reset_postdata();
                    }

                    if ( ! empty( $unique_categories ) ) {
                        foreach ( $unique_categories as $category_slug => $category_name ) {
                            echo '<button data-filter=".' . esc_attr( $category_slug ) . '">' . esc_html( $category_name ) . '</button>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="row project-content">
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
                                    <a data-fancybox="gallery" href="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>" 
                                    data-caption='
                                    <h4><?php echo get_the_title(get_the_ID()); ?></h4>
                                    <a class="readmore" 
                                        href="<?php echo esc_url( get_the_permalink(get_the_ID() ) ); ?>">
                                        <?php _e( 'Read more', 'wp-project-portfolio' ); ?>
                                    </a>'>
                                    <?php 
                                        echo '<div class="text">';
                                        the_title( '<h4 class="project-title">', '</h4>' );
                                        echo '<a class="content-readmore" href="'. get_the_permalink( get_the_ID() ) . '">' . __( 'Read more', 'wp-project-portfolio' ) . '</a>';
                                        echo '</div>';
                                        the_post_thumbnail( 'medium' );

                                    wp_reset_postdata();
                                    ?>
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
    </div>
</div>
<?php 
get_footer();
?>