<?php 
namespace WPPP\Admin;

class Action_Link {
    private $plugin_file;

    public function __construct( $plugin_file ) {
        $this->plugin_file = $plugin_file;
        add_filter( 'plugin_action_links', [ $this, 'settings_link' ], 20, 2 );
    }

    /**
     * Add "Settings" link to the plugin's entry in the "Plugins" page.
     *
     * @param array $links An array of existing action links.
     * @param string $file Plugin file path.
     * @return array Modified action links with the "Settings" link.
     */
    public function settings_link( $links, $file ) {
        if ( $file === $this->plugin_file ) {
            $settings_link = '<a href="' . admin_url( 'edit.php?post_type=portfolio_project' ) . '">' . esc_html__( 'Settings', 'wp-project-portfolio' ) . '</a>';
            array_push( $links, $settings_link );
        }
        return $links;
    }
}
