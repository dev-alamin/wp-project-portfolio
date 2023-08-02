<?php
namespace WPPP;

/**
 * WP Project Portfolio Custom Page Template
 *
 * This class registers a custom page template from the WP Project Portfolio plugin.
 * The template allows users to select a custom page layout for specific pages.
 *
 * @package WP_Project_Portfolio
 */

 class Page_Template {

    /**
     * Constructor.
     *
     * Initializes the custom page template class and adds the necessary hooks.
     */
    public function __construct() {
        add_filter( 'theme_page_templates', array( $this, 'register_custom_template' ) );
        add_filter( 'page_template', array( $this, 'load_custom_template' ) );
    }

    /**
     * Register custom page template from the plugin.
     *
     * @param array $templates List of page templates.
     * @return array Modified list of page templates with the custom template.
     */
    public function register_custom_template( $templates ) {
        $template_path = WPPP_PLUGIN_PATH . 'templates/project-template.php';
        $template_name = __( 'Portfolio Project', 'wp-project-portfolio' );

        // Add the template to the list of page templates
        $templates[basename( $template_path )] = $template_name;

        return $templates;
    }

    /**
     * Load the Portfolio Project custom template when selected.
     *
     * @param string $template Current page template.
     * @return string Modified page template path for the custom template.
     */
    public function load_custom_template( $template ) {
        if ( is_page_template( 'project-template.php' ) ) {
            $template = WPPP_PLUGIN_PATH . 'templates/project-template.php';
        }

        return $template;
    }
}
