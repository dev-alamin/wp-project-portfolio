<?php 
namespace WPPP\Admin;

class Menu{
    public function __construct(){
        add_action( 'admin_menu', [ $this, 'menu' ] );
    }

    public function menu(){
        add_submenu_page( 'edit.php?post_type=portfolio_project', __( 'Portfolio Settings Page', 'wp-project-portfolio'), __( 'Settings', 'wp-project-portfolio'), 'manage_options', 'portfolio_settings', [ $this, 'menu_cb' ] );
    }

    public function menu_cb(){
        $file = __DIR__ . '/../../templates/admin/settings.php';

        if( file_exists( $file ) ) {
            include $file;
        }else{
            _e( 'Sorry, page does not exist!', 'wp-project-portfolio' );
        }
    }
}