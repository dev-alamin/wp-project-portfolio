<?php
namespace WPPP\Admin;

class Save_Settings {

    public function __construct() {
        add_action( 'admin_init', [ $this, 'register_portfolio_settings' ] );
    }

    /**
     * Register portfolio settings and sections.
     */
    public function register_portfolio_settings() {
        register_setting( 'portfolio-settings-group', 'portfolio_page_title' );
        register_setting( 'portfolio-settings-group', 'portfolio_subtitle' );
        register_setting( 'portfolio-settings-group', 'portfolio_equal_width' );
        register_setting( 'portfolio-settings-group', 'portfolio_show_sort' );
        register_setting( 'portfolio-settings-group', 'portfolio_show_filter' );

        add_settings_section(
            'portfolio-general',
            __( 'General Settings', 'wp-project-portfolio' ),
            [ $this, 'render_general_section' ],
            'portfolio-settings-group'
        );

        add_settings_field(
            'portfolio-page-title',
            __( 'Page Title', 'wp-project-portfolio' ),
            [ $this, 'render_page_title_field' ],
            'portfolio-settings-group',
            'portfolio-general'
        );

        add_settings_field(
            'portfolio-subtitle',
            __( 'Subtitle', 'wp-project-portfolio' ),
            [ $this, 'render_subtitle_field' ],
            'portfolio-settings-group',
            'portfolio-general'
        );

        add_settings_field(
            'portfolio-equal-width',
            __( 'Equal Width Projects', 'wp-project-portfolio' ),
            [ $this, 'render_equal_width_field' ],
            'portfolio-settings-group',
            'portfolio-general'
        );

        add_settings_field(
            'portfolio-show-sort',
            __( 'Show Sort Option', 'wp-project-portfolio' ),
            [ $this, 'render_show_sort_field' ],
            'portfolio-settings-group',
            'portfolio-general'
        );

        add_settings_field(
            'portfolio-show-filter',
            __( 'Show Filter Option', 'wp-project-portfolio' ),
            [ $this, 'render_show_filter_field' ],
            'portfolio-settings-group',
            'portfolio-general'
        );
    }

    /**
     * Render the general settings section.
     */
    public function render_general_section() {
        echo '<p>' . __( 'Configure general settings for the portfolio.', 'wp-project-portfolio' ) . '</p>';
    }

    /**
     * Render the page title field.
     */
    public function render_page_title_field() {
        $title = get_option( 'portfolio_page_title', '' );
        echo '<input type="text" name="portfolio_page_title" value="' . esc_attr( $title ) . '" class="regular-text">';
    }

    /**
     * Render the subtitle field.
     */
    public function render_subtitle_field() {
        $subtitle = get_option( 'portfolio_subtitle', '' );
        echo '<input type="text" name="portfolio_subtitle" value="' . esc_attr( $subtitle ) . '" class="regular-text">';
    }

    /**
     * Render the equal width projects field.
     */
    public function render_equal_width_field() {
        $equal_width = get_option( 'portfolio_equal_width', false );
        echo '<label><input type="checkbox" name="portfolio_equal_width" value="1" ' . checked( $equal_width, true, false ) . '> ' . __( 'Enable equal width projects', 'wp-project-portfolio' ) . '</label>';
    }

    /**
     * Render the show sort option field.
     */
    public function render_show_sort_field() {
        $show_sort = get_option( 'portfolio_show_sort', true );
        echo '<label><input type="checkbox" name="portfolio_show_sort" value="1" ' . checked( $show_sort, true, false ) . '> ' . __( 'Show sort option', 'wp-project-portfolio' ) . '</label>';
    }

    /**
     * Render the show filter option field.
     */
    public function render_show_filter_field() {
        $show_filter = get_option( 'portfolio_show_filter', true );
        echo '<label><input type="checkbox" name="portfolio_show_filter" value="1" ' . checked( $show_filter, true, false ) . '> ' . __( 'Show filter option', 'wp-project-portfolio' ) . '</label>';
    }
}