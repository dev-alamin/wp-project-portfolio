<?php
namespace WPPP;

class CPT{
    public function __construct(){
        add_action( 'init', [ $this, 'register_cpt' ] );
    }

    /**
     * Register Post Type POST Projects
     *
     * @return void
     **/
    public function register_cpt(){
        $labels = array(
            'name'               => __( 'Projects', 'wp-project-portfolio' ),
            'singular_name'      => __( 'Project', 'wp-project-portfolio' ),
            'add_new'            => __( 'Add New Project', 'wp-project-portfolio' ),
            'add_new_item'       => __( 'Add New Project', 'wp-project-portfolio' ),
            'edit_item'          => __( 'Edit Project', 'wp-project-portfolio' ),
            'new_item'           => __( 'New Project', 'wp-project-portfolio' ),
            'view_item'          => __( 'View Project', 'wp-project-portfolio' ),
            'search_items'       => __( 'Search Projects', 'wp-project-portfolio' ),
            'not_found'          => __( 'Not found Projects', 'wp-project-portfolio' ),
            'not_found_in_trash' => __( 'Not found Projects in trash', 'wp-project-portfolio' ),
        );

        $args   = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_rest'       => true, // Adds gutenberg support.
            'query_var'          => true,
            'rewrite'            => array(
                'slug'       => _x( 'project', 'slug', 'wp-project-portfolio' ),
                'with_front' => false,
            ),
            'has_archive'        => false,
            'capability_type'    => 'post',
            'hierarchical'       => false,
            'menu_position'      => 90,
            'menu_icon'          => 'dashicons-admin-page', // https://developer.wordpress.org/resource/dashicons/.
            'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
        );
        register_post_type( 'portfolio_project', $args );
    }
}
