<?php

/**
 * STWATT_Shortcode class.
 */
class STWATT_Shortcode {
    /**
     * Construct class.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        add_shortcode( 'stravawatts', array( $this, 'shortcode' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'scripts_styles' ) );
    }

    /**
     * Load scripts and styles.
     *
     * @access public
     * @return void
     */
    public function scripts_styles() {
        wp_enqueue_script( 'stwatt-fa-script', 'https://kit.fontawesome.com/f866e14327.js', '', '5.5.14', true ); 
        
        wp_enqueue_style( 'stwatt-shortcode-style', STWATT_ASSETS_URL . 'css/shortcode.css', '', STWATT_VERSION );
    }

    /**
     * Main shortcode function.
     *
     * @access public
     * @param mixed $atts
     * @return void
     */
    public function shortcode( $atts ) {
        $atts = shortcode_atts(
            array(
                'athlete_id' => 0,
            ),
            $atts,
            'stravawatts'
        );

        $path = STWATT_ABSPATH . 'includes/shortcode.php';

        // include.
        if ( file_exists( $path ) ) {
            extract( $atts );
            include( $path );
        }
    }

}

new STWATT_Shortcode();
