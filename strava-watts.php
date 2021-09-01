<?php
/**
 * Plugin Name:     Strava Watts
 * Plugin URI:      
 * Description:     Add cool Strava graphs and information to your WordPress site.
 * Author:          Erik Mitchell
 * Author URI:      https://erikmitchell.net
 * Text Domain:     stwatt
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         emwpst
 */
 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

if ( ! defined( 'STWATT_PLUGIN_FILE' ) ) {
    define( 'STWATT_PLUGIN_FILE', __FILE__ );
}

// Include the main STWATT class.
if ( ! class_exists( 'STWATT' ) ) {
    include_once dirname( __FILE__ ) . '/includes/class-stwatt.php';
}