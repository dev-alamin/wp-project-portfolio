<?php
namespace WPPP\Frontend;
use \WPPP\Frontend\Component;
use \WPPP\Assets;

class Shortcode{

    public function __construct(){
        add_action( 'init', [ $this, 'portolio_shortcode' ] );
    }

    public function portolio_shortcode(){
        add_shortcode('wp_project_portfolio', [ $this, 'shortcode_cb' ] );
    }

    public function shortcode_cb( $atts ) {
        $atts = shortcode_atts(array(
            'category' => 'food',
        ), $atts);
    
        $component = new Component();
        $loop = $component->loop();
    
        $unique_categories = $loop['categories'];
        $category_counts = $component->cat_counts();
        $project_ids = $loop['ids'];
    
        ob_start();
    
        $asset = new Assets();
        $asset->enqueue_assets();
        ?>

<?php do_action( 'wp_project_portfolio_before_wrapper' ); ?>

<div class="project-portfolio-wrapper">
<?php do_action( 'wp_project_portfolio_before_container' ); ?>
    <div class="container">
        <div class="row">
        <?php do_action( 'wp_project_portfolio_inside_bt_rows' ); // You can add bootstrap rows here ?>
            <div class="col-lg-12">
                <div class="portfolio-page-header mt-3 mb3">
                <?php do_action( 'wp_project_portfolio_before_title' ); ?>
                <?php 
                $title = get_option( 'portfolio_title' );

                if( ! empty( $title ) ) : ?>    
                    <h1>
                        <?php esc_html_e( $title ); ?>
                    </h1>
                <?php endif; ?>

                <?php 
                $subtitle = get_option( 'portfolio_subtitle' );

                if( ! empty( $subtitle ) ) : ?>    
                    <p>
                        <?php esc_html_e( $subtitle ); ?>
                    </p>
                <?php endif; ?>

                <?php do_action( 'wp_project_portfolio_after_title' ); ?>
                
                </div>
                    <div class="portfolio-meta">
                    <?php
                    $show_sort = get_option( 'portfolio_show_sort' );
                    $show_filter = get_option( 'portfolio_show_filter' );
                    $is_cat_present = ( is_array( $category_counts ) &&  isset( $category_counts ) ) ? array_sum( $category_counts ) : [];
                    $total_items = array_sum( $category_counts );
                    $portfolio_number = get_option( 'portfolio_show_numbers', true );
                    $is_show_number = $portfolio_number ? $total_items : '';
                    $all_btn = ! empty( $is_show_number ) ? __( 'All - ' . $total_items, 'wp-project-portfolio' ) : __( 'All', 'wp-project-portfolio' );
                    
                    if( ! empty( $show_sort ) ) {
                        echo $component->sort();
                    }

                    if( ! empty ( $show_filter ) ) {
                        echo $component->filter();
                    }
                    ?>

                <?php do_action( 'wp_project_portfolio_after_sort_filter' ); ?>

                <div class="button-group mt-3">
                    <?php echo '<button class="active" data-filter="">' . $all_btn . '</button>';

                        if (!empty( $unique_categories )) {
                            foreach ( $unique_categories as $category_slug => $category_name ) {
                                $category_count = $category_counts[$category_slug];
                                if( $portfolio_number ) {
                                    echo '<button data-filter=".' . esc_attr( $category_slug ) . '">';
                                    esc_html_e( $category_name . '(' . $category_count . ')' );
                                    echo '</button>';
                                }else{
                                    echo '<button data-filter=".' . esc_attr( $category_slug ) . '">';
                                    esc_html_e( $category_name );
                                    echo '</button>';
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row project-content" id="project-content">
            <?php

            // Use stored post IDs to fetch the post content
            if (!empty($project_ids)) {
                $index = 1;
                foreach ($project_ids as $project_id) {
                    $terms = get_the_terms($project_id, 'project_cat'); // Replace 'project_cat' with your custom taxonomy slug

                    if (!empty($terms) && !is_wp_error($terms)) {
                        foreach ($terms as $term) {
                            $bt_class = $component->Bootstrap_Class($index);
                            $equal_width = get_option( 'portfolio_equal_width', true );
                            $show_gallery_thumbanil = get_option( 'portfolio_show_gallery_thumbnail', true );
                            $index++;
                            ?>
                            <div class="<?php echo esc_attr( $term->slug ); ?> <?php echo $equal_width ? ' col-lg-4' :  $bt_class; ?>">
                                <div class="portfolio-single-project">
                                    <a class="data-fancybox-trigger" <?php echo $show_gallery_thumbanil ? 'data-fancybox="gallery"' : 'data-fancybox'; ?> href="<?php echo esc_url(get_the_post_thumbnail_url( $project_id, 'large')); ?>" data-caption='<?php echo $component->caption( $project_id ); ?>'>
                                        <?php
                                            echo $component->content( $project_id ); // Hover content
                                            echo $component->thumbnail( $project_id ); // Thumbnail ?>
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
        <?php do_action( 'wp_project_portfolio_before_load_more_button' ); ?>
        <button id="load-more-button" class="mb-5">
            <?php echo apply_filters('wp_project_portfolio_load_more_button_label', __('Load More', 'wp-project-portfolio')); ?>
        </button>
        <?php do_action( 'wp_project_portfolio_after_load_more_button' ); ?>
    </div>
</div>
<?php do_action( 'wp_project_portfolio_after_wrapper' ); ?>
        <?php
        // End of HTML and PHP logic
    
        $output = ob_get_clean();
    
        return $output;
    }
    
}