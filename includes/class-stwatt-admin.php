<?php
class STWATT_Admin {
    
    /**
     * Construct class.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        add_action( 'admin_enqueue_scripts', array($this, 'scripts_styles' ) );        
        add_action( 'admin_menu', array($this, 'update_settings' ) );
        add_action( 'admin_menu', array($this, 'admin_menu' ) );
    }
    
    public function scripts_styles() { 
        wp_enqueue_style( 'stwatt-grid-style', STWATT_ASSETS_URL . 'css/grid.css', '', STWATT_VERSION );
    }

    public function admin_menu() {
        add_options_page( 'Strava Settings', 'Strava Settings', 'manage_options', 'stwatts-settings', array($this, 'admin_page' ) );
    }

    public function admin_page() {
        $path = STWATT_ABSPATH . "includes/settings.php";
        $args = array(
            'page_url' => admin_url( 'options-general.php?page=stwatts-settings'),
            'prefix' => '_stwatt_',
        );
        
        // include.
        if ( file_exists( $path ) ) {
            extract( $args );
            include( $path );
        }   
    }
    
    public function update_settings() {
        if ( ! isset( $_POST['stwatt_settings_nonce'] ) || ! wp_verify_nonce( $_POST['stwatt_settings_nonce'], 'update_settings' ) ) {
            return;
        }

        $prefix = '_stwatt_';
        $settings = isset( $_POST['stwatt_settings'] ) ? $_POST['stwatt_settings'] : array();

        if (empty($settings))
            return;
            
        foreach ($settings as $key => $value) {
            update_option( $prefix . $key, $value );
        }
               
    }
}

if (is_admin()) {
    new STWATT_Admin();
}
