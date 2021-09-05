<?php

defined( 'ABSPATH' ) || exit;

/**
 * STWATT_Install Class.
 */
class STWATT_Install {

    /**
     * Initalize.
     *
     * @access public
     * @static
     * @return void
     */
    public static function init() {
        add_action( 'init', array( __CLASS__, 'check_version' ), 5 );
    }

    /**
     * Check plugin versoin.
     *
     * @access public
     * @static
     * @return void
     */
    public static function check_version() {
        if ( version_compare( STWATT_VERSION, get_option( 'stwatt_version', 0 ), '>' ) ) {
            self::install();
        }
    }

    /**
     * Run install process/functions.
     *
     * @access public
     * @static
     * @return void
     */
    public static function install() {
        if ( ! is_blog_installed() ) {
            return;
        }

        // Check if we are not already running this routine.
        if ( 'yes' === get_transient( 'stwatt_installing' ) ) {
            return;
        }

        // If we made it till here nothing is running yet, lets set the transient now.
        set_transient( 'stwatt_installing', 'yes', MINUTE_IN_SECONDS * 10 );

        self::create_tables();
        self::update_version();
        self::maybe_update_db_version();
        self::setup_cron_jobs();

        delete_transient( 'stwatt_installing' );
    }

    /**
     * Create db tables.
     *
     * @access public
     * @static
     * @return void
     */
    public static function create_tables() {
        global $wpdb;

        $sql = array();

        $stwatt_db_version = get_option( 'stwatt_db_version', 0 );

        if ( version_compare( $stwatt_db_version, 0, '>' ) ) {
            return;
        }

        $charset_collate = $wpdb->get_charset_collate();

        $sql[] = "CREATE TABLE stwatt_athletes (
            id int(11) unsigned NOT NULL AUTO_INCREMENT,
            age varchar(12) DEFAULT NULL,
            athlete_id int(11) DEFAULT NULL,
            first_name varchar(60) DEFAULT NULL,
            gender varchar(1) DEFAULT NULL,
            last_name varchar(64) DEFAULT NULL,             
            PRIMARY KEY (id)
        ) $charset_collate;";

        $sql[] = "CREATE TABLE stwatt_tokens (
            id int(11) unsigned NOT NULL AUTO_INCREMENT,
            athlete_id int(11) DEFAULT NULL,
            scope varchar(32) DEFAULT NULL,
            refresh_token varchar(255) DEFAULT NULL, 
            access_token varchar(255) DEFAULT NULL,
            expires_at int(11) DEFAULT NULL, 
            last_updated datetime NOT NULL,            
            PRIMARY KEY (id)
        ) $charset_collate;";

        $sql[] = "CREATE TABLE stwatt_athlete_activities (
            id int(11) unsigned NOT NULL AUTO_INCREMENT,
            activity_id bigint(8) DEFAULT NULL,
            athlete_id int(11) DEFAULT NULL,
            name varchar(255) DEFAULT NULL,
            distance decimal(10,1),
            time int (11),
            elevation int (11),
            date datetime NOT NULL,
            bike_type varchar(64) DEFAULT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        $sql[] = "CREATE TABLE stwatt_athlete_activities (
            id int(11) unsigned NOT NULL AUTO_INCREMENT,
            athlete_id int(11) DEFAULT NULL,
            elevation int(11) DEFAULT NULL,
            distance decimal(10,1) DEFAULT NULL,
            time int (11),
            distance_road decimal(10,1) DEFAULT NULL,
            distance_cross decimal(10,1) DEFAULT NULL,  
            distance_mtb decimal(10,1) DEFAULT NULL,
            distance_tt decimal(10,1) DEFAULT NULL,
            distance_gravel decimal(10,1) DEFAULT NULL,                                              
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

        update_option( 'stwatt_db_version', STWATT_DB_VERSION );
    }

    /**
     * Update version of plugin option.
     *
     * @access public
     * @static
     * @return void
     */
    public static function update_version() {
        update_option( 'stwatt_version', STWATT_VERSION );
    }

    /**
     * Check if we need to update db version.
     *
     * @access public
     * @static
     * @return void
     */
    public static function maybe_update_db_version() {
        // no updates yet
    }

    /**
     * Setup token check cron job.
     *
     * @access public
     * @static
     * @return void
     */
    public static function setup_cron_jobs() {
        // Use wp_next_scheduled to check if the event is already scheduled
        $timestamp = wp_next_scheduled( 'stwatt_user_token_check' );

        // If $timestamp == false schedule daily backups since it hasn't been done previously
        if ( $timestamp == false ) {
            // Schedule the event for right now, then to repeat daily using the hook 'stwatt_user_token_check'
            wp_schedule_event( time(), 'twicedaily', 'stwatt_user_token_check' );
        }
    }

}

STWATT_Install::init();
