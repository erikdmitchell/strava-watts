<?php

class STWATT_Shortcode {
    /**
     * Construct class.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        add_shortcode( 'stravawatts', array( $this, 'shortcode' ) );
        add_action( 'wp_enqueue_scripts', array($this, 'scripts_styles' ) );
    }
    
    public function scripts_styles() {
        // https://developers.google.com/fonts/docs/material_icons
        wp_enqueue_style( 'stwatt-google-icons-style', 'https://fonts.googleapis.com/icon?family=Material+Icons', '', '4.0.0' );
    }

    public function shortcode( $atts ) {
        $atts = shortcode_atts( array(), $atts, 'stravawatts' );

        $path = STWATT_ABSPATH . 'includes/shortcode.php';

        // include.
        if ( file_exists( $path ) ) {
            extract( $atts );
            include( $path );
        }
    }

}

new STWATT_Shortcode();
