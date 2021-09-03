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
        wp_enqueue_script( 'stwatt-fa-script', 'https://kit.fontawesome.com/f866e14327.js', '', '5.5.14', true );        
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
