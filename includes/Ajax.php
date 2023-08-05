<?php
namespace WPPP;
use WPPP\Frontend\Component;

/**
 * Class Ajax
 *
 * Handles AJAX actions related to project portfolio functionality.
 */
class Ajax{

    /**
    * Class constructor.
    *
    * Initializes the AJAX actions for project portfolio.
    */
    public function __construct(){
        add_action( 'wp_ajax_wp_project_portfolio', [ $this, 'load_more' ] );
        add_action( 'wp_ajax_nopriv_wp_project_portfolio', [ $this, 'load_more' ] );

        add_action( 'wp_ajax_filter_projects', [ $this, 'filter_projects' ] );
        add_action( 'wp_ajax_nopriv_filter_projects', [ $this, 'filter_projects' ] );
    
    }

    /**
    * AJAX callback to fetch and display additional project posts.
    */
    public function load_more(){
        $component = new Component();

        $offset = isset( $_POST['offset']) ? intval( $_POST['offset']) : 6;
        $posts_to_show = isset( $_POST['posts_to_show']) ? intval( $_POST['posts_to_show']) : 6;
    
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
                $show_gallery_thumbanil = get_option( 'portfolio_show_gallery_thumbnail', true );
                $project->the_post();
                $terms = get_the_terms(get_the_ID(), 'project_cat');
    
                if ( ! empty( $terms) && !is_wp_error( $terms ) ) {
                    foreach ( $terms as $term ) { ?>
                        <!-- // Your existing HTML code for displaying project posts goes here -->
                    <div class="<?php echo $component->Bootstrap_Class() . ' ' . esc_attr( $term->slug ); ?>">
                            <div class="portfolio-single-project">
                                <a class="data-fancybox-trigger"
                                <?php echo $show_gallery_thumbanil ? 'data-fancybox="gallery"' : 'data-fancybox'; ?>
                                href="<?php echo esc_url( get_the_post_thumbnail_url(get_the_ID(), 'large') ); ?>" 
                                data-caption='<?php echo $component->caption( get_the_ID() ); ?>'>
                                    <?php 
                                    echo $component->content( get_the_ID() );
                                    echo $component->thumbnail( get_the_ID() );
                                    ?>
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
            $response = _e( 'No projects found.', 'wp-project-portfolio' );
        }
    
        echo $response;
        wp_die();
    }

     /**
     * AJAX callback to filter projects based on category.
     */
    public function filter_projects() {
        $component = new Component();
    
        $category_slug = isset($_POST['category_slug']) ? $_POST['category_slug'] : 'all';
        $posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : -1; // Get the posts_per_page value
        $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0; // Get the offset value

        if( 'all' == $category_slug ) {
            $args = array(
                'post_type'      => 'portfolio_project',
                'post_status'    => 'publish',
                'posts_per_page' => $posts_per_page,
                'offest'        => $offset,
            );
        }else{
            $args = array(
                'post_type'      => 'portfolio_project',
                'post_status'    => 'publish',
                'posts_per_page' => $posts_per_page,
                'offest'        => $offset,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'project_cat',
                        'field' => 'slug',
                        'terms' => $category_slug,
                    ),
                ),
            );
        }
        

    
        $project = new \WP_Query( $args );
    
        if ($project->have_posts()) {
            ob_start();
    
            while ($project->have_posts()) {
                $show_gallery_thumbanil = get_option( 'portfolio_show_gallery_thumbnail', true );
                $project->the_post();
                $terms = get_the_terms(get_the_ID(), 'project_cat');
    
                if (!empty($terms) && !is_wp_error($terms)) {
                    foreach ($terms as $term) { ?>
                        <div class="<?php echo $component->Bootstrap_Class() . ' ' . esc_attr( $term->slug ); ?>">
                            <div class="portfolio-single-project">
                                <a class="data-fancybox-trigger"
                                    <?php echo $show_gallery_thumbanil ? 'data-fancybox="gallery"' : 'data-fancybox'; ?>
                                    href="<?php echo esc_url( get_the_post_thumbnail_url(get_the_ID(), 'large') ); ?>" 
                                    data-caption='<?php echo $component->caption( get_the_ID() ); ?>'>
                                    <?php 
                                    echo $component->content( get_the_ID() );
                                    echo $component->thumbnail( get_the_ID() );
                                    ?>
                                </a>
                            </div>
                        </div>
                <?php }
                }else{ ?>
                        <div class="<?php echo $component->Bootstrap_Class() . ' ' . esc_attr( $term->slug ); ?>">
                            <div class="portfolio-single-project">
                                <a class="data-fancybox-trigger"
                                    <?php echo $show_gallery_thumbanil ? 'data-fancybox="gallery"' : 'data-fancybox'; ?>
                                    href="<?php echo esc_url( get_the_post_thumbnail_url(get_the_ID(), 'large') ); ?>" 
                                    data-caption='<?php echo $component->caption( get_the_ID() ); ?>'>
                                    <?php 
                                    echo $component->content( get_the_ID() );
                                    echo $component->thumbnail( get_the_ID() );
                                    ?>
                                </a>
                            </div>
                        </div>
                <?php }; 
            }
    
            wp_reset_postdata();
            $response = ob_get_clean();
        } else {
            $response = _e( 'No projects found.', 'wp-project-portfolio' );
        }
    
        echo $response;
        wp_die();
    }
    
}