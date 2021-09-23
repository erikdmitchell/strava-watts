<?php

/**
 * STWATT_Admin class.
 */
class STWATT_Admin {

    /**
     * Construct class.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'scripts_styles' ) );
        add_action( 'admin_menu', array( $this, 'update_settings' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_init', array( $this, 'force_update_tokens' ) );
    }

    /**
     * Include scripts and styles.
     *
     * @access public
     * @return void
     */
    public function scripts_styles() {
        wp_enqueue_style( 'stwatt-grid-style', STWATT_ASSETS_URL . 'css/grid.css', '', STWATT_VERSION ); // not used?
        wp_enqueue_style( 'stwatt-admin-style', STWATT_ASSETS_URL . 'css/admin.css', '', STWATT_VERSION );
    }

    /**
     * Add to admin settings menu.
     *
     * @access public
     * @return void
     */
    public function admin_menu() {
        add_options_page( 'Strava Settings', 'Strava Settings', 'manage_options', 'stwatts-settings', array( $this, 'admin_page' ) );
    }

    /**
     * Load admin page.
     *
     * @access public
     * @return void
     */
    public function admin_page() {
        $path = STWATT_ABSPATH . 'includes/settings.php';
        $args = array(
            'page_url' => admin_url( 'options-general.php?page=stwatts-settings' ),
            'prefix' => '_stwatt_',
        );

        // include.
        if ( file_exists( $path ) ) {
            extract( $args );
            include( $path );
        }
    }

    /**
     * Update settings.
     *
     * @access public
     * @return void
     */
    public function update_settings() {
        if ( ! isset( $_POST['stwatt_settings_nonce'] ) || ! wp_verify_nonce( $_POST['stwatt_settings_nonce'], 'update_settings' ) ) {
            return;
        }

        $prefix = '_stwatt_';
        $settings = isset( $_POST['stwatt_settings'] ) ? $_POST['stwatt_settings'] : array();

        if ( empty( $settings ) ) {
            return;
        }

        foreach ( $settings as $key => $value ) {
            update_option( $prefix . $key, $value );
        }

    }
    
    /**
     * Update tokens.
     * 
     * @access public
     * @return void
     */
    public function force_update_tokens() {
        if (isset($_GET['action']) && 'force_tokens' == $_GET['action']) {
            stwatt()->auth->update_tokens();
            
            wp_redirect( admin_url('options-general.php?page=stwatts-settings&message=Tokens-updated') );
            exit;
        }
    }
}

if ( is_admin() ) {
    new STWATT_Admin();
}
