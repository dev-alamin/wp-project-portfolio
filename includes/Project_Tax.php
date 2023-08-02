<?php
namespace WPPP;
/**
 * Project_Tax class.
 *
 * Registers the custom taxonomy "project_cat" for the custom post type "portfolio_project".
 *
 * @since     1.0.0
 * @package   WP_Project_Portfolio
 */
class Project_Tax {
    /**
     * Constructor.
     *
     * Initializes the Project_Tax class by adding the necessary action to register the taxonomy.
     *
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'init', [ $this, 'register_tax' ] );
    }

    /**
     * Register Taxonomy Categories.
     *
     * Registers the custom taxonomy "project_cat" for the custom post type "portfolio_project".
     *
     * @since 1.0.0
     */
    public function register_tax() {
        $labels = array(
            'name'          => __( 'Categories', 'wp-project-portfolio' ),
            'singular_name' => __( 'Category', 'wp-project-portfolio' ),
            'search_items'  => __( 'Search Category', 'wp-project-portfolio' ),
            'all_items'     => __( 'All Categories', 'wp-project-portfolio' ),
            'edit_item'     => __( 'Edit Category', 'wp-project-portfolio' ),
            'update_item'   => __( 'Update Category', 'wp-project-portfolio' ),
            'add_new_item'  => __( 'Add New Category', 'wp-project-portfolio' ),
            'new_item_name' => __( 'Add New Category', 'wp-project-portfolio' ),
        );

        register_taxonomy(
            'project_cat',
            array( 'portfolio_project' ),
            array(
                'hierarchical'       => true,
                'public'             => true,
                'publicly_queryable' => true,
                'labels'             => $labels,
                'show_ui'            => true,
                'show_in_rest'       => true,
                'show_admin_column'  => true,
                'query_var'          => true,
                'rewrite'            => array(
                    'slug' => _x( 'project_cat', 'slug', 'wp-project-portfolio' ),
                ),
            )
        );
    }
}
