<?php
namespace WPPP;

class Ajax{
    public function __construct(){
        add_action( 'wp_ajax_wp_project_portfolio', [ $this, 'ajax_cb' ] );
        add_action( 'wp_ajax_nopriv_wp_project_portfolio', [ $this, 'ajax_cb' ] );

        add_action( 'wp_ajax_sort_projects', [ $this, 'sort_projects' ] );
        add_action( 'wp_ajax_nopriv_sort_projects', [ $this, 'sort_projects' ] );

        add_action( 'wp_ajax_filter_projects', [ $this, 'filter_projects' ] );
        add_action( 'wp_ajax_nopriv_filter_projects', [ $this, 'filter_projects' ] );
    
    }

    public function ajax_cb(){
        $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 6;
        $posts_to_show = isset($_POST['posts_to_show']) ? intval($_POST['posts_to_show']) : 6;
    
        $args = array(
            'post_type'      => 'portfolio_project',
            'post_status'    => 'publish',
            'posts_per_page' => $posts_to_show,
            'offset'         => $offset,
        );
    
        $project = new \WP_Query($args);
    
        if ($project->have_posts()) {
            ob_start();
    
            while ($project->have_posts()) {
                $project->the_post();
                $terms = get_the_terms(get_the_ID(), 'project_cat');
    
                if (!empty($terms) && !is_wp_error($terms)) {
                    foreach ($terms as $term) { ?>
                        <!-- // Your existing HTML code for displaying project posts goes here -->
                    <div class="col-lg-4 <?php echo esc_attr( $term->slug ); ?>">
                            <div class="portfolio-single-project">
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
                <?php }
                }
            }
    
            wp_reset_postdata();
            $response = ob_get_clean();
    
            // Check if there are more posts to show
            if ($project->found_posts - ($offset + $posts_to_show) <= 0) {
                $response = ''; // Return empty response to indicate no more posts
            }
        } else {
            $response = 'No more projects found.';
        }
    
        echo $response;
        wp_die();
    }

     // Function to sort projects
     public function sort_projects() {
        $sort_order = isset($_POST['sort_order']) ? $_POST['sort_order'] : 'asc';

        $args = array(
            'post_type'      => 'portfolio_project',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'title', // Sort by title
            'order'          => $sort_order, // Sort order
        );

        $project = new \WP_Query( $args );

        if ($project->have_posts()) {
            ob_start();

            while ($project->have_posts()) {
                $project->the_post();
                $terms = get_the_terms(get_the_ID(), 'project_cat');
    
                if (!empty($terms) && !is_wp_error($terms)) {
                    foreach ($terms as $term) { ?>
                        <!-- // Your existing HTML code for displaying project posts goes here -->
                    <div class="col-lg-4 <?php echo esc_attr( $term->slug ); ?>">
                            <div class="portfolio-single-project">
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
                <?php }
                }
            }

            wp_reset_postdata();
            $response = ob_get_clean();
        } else {
            $response = 'No projects found.';
        }

        echo $response;
        wp_die();
    }

    // Function to filter projects
    public function filter_projects() {
        $category_slug = isset($_POST['category_slug']) ? $_POST['category_slug'] : 'all';

        $args = array(
            'post_type'      => 'portfolio_project',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        );

        if ($category_slug !== 'all') {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'project_cat',
                    'field' => 'slug',
                    'terms' => $category_slug,
                ),
            );
        }

        $project = new \WP_Query( $args );

        if ($project->have_posts()) {
            ob_start();

            while ($project->have_posts()) {
                $project->the_post();
                $terms = get_the_terms(get_the_ID(), 'project_cat');
    
                if (!empty($terms) && !is_wp_error($terms)) {
                    foreach ($terms as $term) { ?>
                        <!-- // Your existing HTML code for displaying project posts goes here -->
                    <div class="col-lg-4 <?php echo esc_attr( $term->slug ); ?>">
                            <div class="portfolio-single-project">
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
                <?php }
                }
            }

            wp_reset_postdata();
            $response = ob_get_clean();
        } else {
            $response = 'No projects found.';
        }

        echo $response;
        wp_die();
    }
}