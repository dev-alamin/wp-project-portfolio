<?php 
namespace WPPP;
/**
 * Class Assets
 *
 * Enqueues scripts and styles for the WP Project Portfolio plugin frontend.
 *
 * @package WP_Project_Portfolio
 */
class Assets {

    /**
     * Class constructor
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    /**
     * Get the list of scripts to be enqueued.
     *
     * @return array An array containing the script handles, sources, versions, and dependencies.
     */
    private function get_scripts() {
        return [
            'bootstrap-script' => [
                'src'     => WPPP_PLUGIN_URL . 'assets/js/bootstrap.bundle.min.js',
                'version' => fileatime( WPPP_PLUGIN_PATH . 'assets/js/bootstrap.bundle.min.js' ),
                'deps'    => [ 'jquery' ],
            ],
            'fancybox-umd-script' => [
                'src'     => WPPP_PLUGIN_URL . 'assets/js/fancybox.umd.js',
                'version' => fileatime( WPPP_PLUGIN_PATH . 'assets/js/fancybox.umd.js' ),
                'deps'    => [ 'jquery' ],
            ],
            'masonary-pkgd' => [
                'src'     => WPPP_PLUGIN_URL . 'assets/js/masonry.pkgd.min.js',
                'version' => fileatime( WPPP_PLUGIN_PATH . 'assets/js/masonry.pkgd.min.js' ),
                'deps'    => [ 'jquery' ],
            ],
            'isotope-pkgd' => [
                'src'     => WPPP_PLUGIN_URL . 'assets/js/isotope.pkgd.min.js',
                'version' => fileatime( WPPP_PLUGIN_PATH . 'assets/js/isotope.pkgd.min.js' ),
                'deps'    => [ 'jquery' ],
            ],
            // 'fancybox-umd-script' => [
            //     'src'     => WPPP_PLUGIN_URL . 'assets/js/jquery-ui.min.js',
            //     'version' => fileatime( WPPP_PLUGIN_PATH . 'assets/js/jquery-ui.min.js' ),
            //     'deps'    => [ 'jquery' ],
            // ],
            'portfolio-script' => [
                'src'     => WPPP_PLUGIN_URL . 'assets/js/frontend.js',
                'version' => fileatime( WPPP_PLUGIN_PATH . 'assets/js/frontend.js' ),
                'deps'    => [ 'jquery', 'masonary-pkgd', 'fancybox-umd-script', 'isotope-pkgd' ],
            ],
        ];
    }

    /**
     * Get the list of styles to be enqueued.
     *
     * @return array An array containing the style handles, sources, versions, and dependencies.
     */
    private function get_styles() {
        return [
            'portfolio-style' => [
                'src'     => WPPP_PLUGIN_URL . 'assets/css/frontend.css',
                'version' => fileatime( WPPP_PLUGIN_PATH . 'assets/css/frontend.css' ),
                'deps'    => ['bootstrap-style'],
            ],
            'bootstrap-style' => [
                'src'     => WPPP_PLUGIN_URL . 'assets/css/bootstrap.min.css',
                'version' => fileatime( WPPP_PLUGIN_PATH . 'assets/css/bootstrap.min.css' ),
            ],
            'fancybox-style' => [
                'src'     => WPPP_PLUGIN_URL . 'assets/css/fancybox.css',
                'version' => fileatime( WPPP_PLUGIN_PATH . 'assets/css/fancybox.css' ),
            ],
        ];
    }

    /**
     * Enqueue scripts and styles for the frontend.
     *
     * @return void
     */
    public function enqueue_assets() {
        $cpt = 'portfolio_project';

        $scripts = $this->get_scripts();
        $styles  = $this->get_styles();

        if( get_post_type() === $cpt || is_page_template( 'project-template.php' )  ) {
            foreach ( $scripts as $handle => $script ) {
                $deps = isset( $script['deps'] ) ? $script['deps'] : false;
                
                wp_enqueue_script( $handle, $script['src'], $deps, $script['version'], true );
            }
            
            foreach ( $styles as $handle => $style ) {
                $deps = isset( $style['deps'] ) ? $style['deps'] : false;
                
                wp_enqueue_style( $handle, $style['src'], $deps, $style['version'] );
            }

            // Get the unique class slugs from PHP and pass it to your script
            $args = array(
                'post_type'      => 'portfolio_project',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
            );

            $project = new \WP_Query($args);

            $unique_categories = array();
            if ($project->have_posts()) {
                while ($project->have_posts()) {
                    $project->the_post();
                    $terms = get_the_terms(get_the_ID(), 'project_cat');

                    if (!empty($terms) && !is_wp_error($terms)) {
                        foreach ($terms as $term) {
                            $unique_categories[$term->slug] = $term->name;
                        }
                    }
                }
                wp_reset_postdata();
            }

            wp_localize_script('portfolio-script', 'portofolioObject', array(
                'uniqueClassSlugs' => array_keys($unique_categories),
                'adminUrl'      => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce( 'project_porfoltio_nonce'),
            ));
        }
    }
}
