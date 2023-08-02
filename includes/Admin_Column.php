<?php 
namespace WPPP;

/**
 * Admin_Column class.
 *
 * Adds a custom admin column "Thumbnail" to the custom post type "portfolio_project" in the WordPress admin.
 *
 * @since     1.0.0
 * @package   WP_Project_Portfolio
 */
class Admin_Column {
    /**
     * Constructor.
     *
     * Initializes the Admin_Column class by adding the necessary filters and actions.
     *
     * @since 1.0.0
     */
    public function __construct() {
        add_filter( 'manage_portfolio_project_posts_columns', [ $this, 'add_admin_column' ] );
        add_action( 'manage_portfolio_project_posts_custom_column', [ $this, 'populate_admin_column' ], 10, 2 );
    }

    /**
     * Add admin column to the custom post type "portfolio_project".
     *
     * Moves the "project_thumbnail" column to be placed before the "date" column.
     *
     * @since 1.0.0
     *
     * @param array $columns Existing columns in the custom post type list table.
     * @return array Modified columns with the "project_thumbnail" column added.
     */
    public function add_admin_column( $columns ) {
        $date_column = $columns['date'];
        unset( $columns['date'] );

        $columns['project_thumbnail'] = __( 'Thumbnail', 'wp-project-portfolio' );

        $columns['date'] = $date_column; // Add the "date" column back after the "project_thumbnail" column.

        return $columns;
    }

    /**
     * Populate the admin column with data.
     *
     * Displays the post thumbnail in the "project_thumbnail" column.
     *
     * @since 1.0.0
     *
     * @param string $column  Current column being displayed.
     * @param int    $post_id Current post ID.
     */
    public function populate_admin_column( $column, $post_id ) {
        if ( $column === 'project_thumbnail' ) {
            echo get_the_post_thumbnail( $post_id, [ 80, 80 ] ); // Use 'thumbnail' size, change it to your desired image size if needed.
        }
    }

}

