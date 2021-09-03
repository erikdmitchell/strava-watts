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
