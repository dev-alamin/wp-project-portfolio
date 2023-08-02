<?php
/**
 * Plugin Name: WP Project Portfolio
 * Plugin URI:  https://almn.me/wp-project-portfolio
 * Description: This is a plugin for showing your previous works in an awesome style using WordPress.
 * Version:     1.0
 * Author:      Al Amin
 * Author URI:  https://almn.me
 * Text Domain: wp-project-portfolio
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package     WPProjectPortfolio
 * @author      Al Amin
 * @copyright   2023 AwesomeDigitalSolution
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 *
 * Prefix:      WPPP
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );


function wp_project_portfolio_autoloader( $class ) {
    $namespace = 'WPPP';
    $base_dir  = __DIR__ . '/includes/';

    $class = ltrim( $class, '\\' );
    if ( strpos( $class, $namespace . '\\' ) === 0 ) {
        $relative_class = substr( $class, strlen( $namespace . '\\' ) );
        $file           = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';
        if ( file_exists( $file ) ) {
            require $file;
        }
    }
}
spl_autoload_register( 'wp_project_portfolio_autoloader' );

/**
 * WP Project Portfolio Activation and Deactivation Hooks.
 *
 * Registers activation and deactivation hooks for the WP Project Portfolio plugin.
 *
 * @since     1.0.0
 * @package   WP_Project_Portfolio
 */

/**
 * Callback function for plugin activation.
 *
 * Flushes the rewrite rules when the WP Project Portfolio plugin is activated. This is done to ensure
 * that any new custom post types or taxonomies registered by the plugin are properly handled in the
 * permalink structure.
 *
 * @since     1.0.0
 * @return    void
 */
function wppp_activation() {
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'wppp_activation' );

/**
 * Callback function for plugin deactivation.
 *
 * Flushes the rewrite rules when the WP Project Portfolio plugin is deactivated. This is done to ensure
 * that any changes made to the permalink structure or custom post types are reverted to their default
 * state.
 *
 * @since     1.0.0
 * @return    void
 */
function wppp_deactivation() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'wppp_deactivation' );


/**
 * WP_Project_Portfolio Class.
 *
 * A final class that initializes and manages the WP Project Portfolio plugin.
 *
 * This class follows the Singleton design pattern to ensure only one instance
 * of the plugin is loaded throughout the WordPress runtime.
 *
 * @since     1.0.0
 * @final
 */
final class WP_Project_Portfolio {
    /**
     * The single instance of the class.
     *
     * @var WP_Project_Portfolio|null
     */
    private static $instance = null;

    /**
     * Private constructor.
     *
     * Initializes the plugin by adding an action hook to the 'plugins_loaded' event.
     */
    private function __construct() {
        add_action( 'plugins_loaded', [ $this, 'init' ] );
        $this->define_constants();
    }

    /**
     * Get the single instance of the class.
     *
     * Ensures that only one instance of the class is created.
     *
     * @return WP_Project_Portfolio The single instance of the class.
     */
    public static function get_instance() {
        if ( ! self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Initializes the plugin by loading localization files.
     *
     * @return void
     */
    public function init() {
        load_plugin_textdomain( 'wp-project-portfolio', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
        new \WPPP\CPT();
        new \WPPP\Project_Tax();
        new \WPPP\Admin_Column();
        new \WPPP\Page_Template();

        new \WPPP\Frontend();

        $this->load_admin_hooks();
        $this->load_frontend_hooks();
    }

    /**
     * Load hooks specific to the admin area.
     *
     * @return void
     */
    private function load_admin_hooks() {
        if ( is_admin() ) {
            // Add admin-specific hooks here.
            $plugin_file = plugin_basename( __FILE__ );
            new WPPP\Admin\Action_Link( $plugin_file );
        }
    }

    /**
     * Load hooks specific to the frontend.
     *
     * @return void
     */
    private function load_frontend_hooks() {
        if ( ! is_admin() ) {
            // Add frontend-specific hooks here.
        }
    }

    /**
     * Defines the constants used by the plugin.
     *
     * @return void
     */
    public function define_constants() {
        define( 'WPPP_VERSION', 'WPProjectPortfolio' );
        define( 'WPPP_PLUGIN', __FILE__ );
        define( 'WPPP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
        define( 'WPPP_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
    }

}

/**
 * WP_Project_Portfolio initialization function.
 *
 * Initializes the WP_Project_Portfolio class and ensures only one instance is created.
 *
 * @return WP_Project_Portfolio The single instance of the WP_Project_Portfolio class.
 */
function WPPP_plugin_init() {
    return WP_Project_Portfolio::get_instance();
}

// Initialize the plugin.
WPPP_plugin_init();

function custom_portfolio_excerpt_length( $length ) {
    if ( is_singular( 'portfolio_project' ) ) {
        return 30; // Change this number to the desired word count for the excerpt
    } else {
        return $length;
    }
}
add_filter( 'excerpt_length', 'custom_portfolio_excerpt_length' );

function custom_portfolio_excerpt_more( $more ) {
    return ''; // Remove the square symbols ([...])
}
add_filter( 'excerpt_more', 'custom_portfolio_excerpt_more' );
