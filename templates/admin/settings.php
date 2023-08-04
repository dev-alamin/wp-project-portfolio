<div class="wrap">
    <h1 class="inline-heading">
        <?php _e( 'Portfolio Settings Page', 'wp-project-portfolio' ); ?>
    </h1>
    
    <form method="post" action="options.php">
        <?php settings_fields( 'portfolio-settings-group' ); ?>
        <?php do_settings_sections( 'portfolio-settings-group' ); ?>
        <?php submit_button(); ?>
    </form>
</div>
