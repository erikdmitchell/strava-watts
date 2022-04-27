<?php

/**
 * Check if athlete exists.
 *
 * @access public
 * @param int $athlete_id (default: 0).
 * @return boolean
 */
function stwatt_athlete_exists( $athlete_id = 0 ) {
    if ( stwatt()->athletes_db->get_column_by( 'id', 'athlete_id', $athlete_id ) ) {
        return true;
    }

    return false;
}

/**
 * Is athlete authroized via Strava.
 *
 * @access public
 * @param int $athlete_id (default: 0)
 * @return void
 */
function stwatt_is_athlete_authorized( $athlete_id = 0 ) {
    if ( stwatt()->athletes_db->get_column_by( 'id', 'athlete_id', $athlete_id ) ) {
        return true;
    }

    return false;
}

/**
 * Gets an athlete.
 *
 * @access public
 * @param int $athlete_id (default: 0)
 * @return athlete object
 */
function stwatt_athlete( $athlete_id = 0 ) {
    $prefix = '_stwatt_';
    $athlete_id = intval( $athlete_id );

    if ( ! $athlete_id ) {
        $athlete_id = get_option( "{$prefix}athlete_id", 0 );
    }

    return new STWATT_Athlete( $athlete_id );
}

/**
 * Custom wrap string.
 *
 * @access public
 * @param string $str (default: '').
 * @param string $wrap_start (default: '<span>').
 * @param string $wrap_end (default: '</span>').
 * @param bool   $echo (default: true).
 * @return string
 */
function stwatt_str_wrap( $str = '', $wrap_start = '<span>', $wrap_end = '</span>', $echo = true ) {
    if ( empty( $str ) ) {
        return;
    }

    $wrapped = '';
    $str_arr = str_split( $str );

    foreach ( $str_arr as $char ) {
        $wrapped .= $wrap_start . $char . $wrap_end;
    }

    if ( $echo ) {
        echo $wrapped;
    }

    return $wrapped;
}

/**
 * Write to plugin log.
 *
 * @access public
 * @param string $message (default: '')
 * @return void
 */
function stwatt_log( $message = '' ) {
    if ( is_array( $message ) ) {
        $message = json_encode( $message );
    }
    
    if (!is_dir(STWATT_UPLOADS_PATH) ) {
        wp_mkdir_p( STWATT_UPLOADS_PATH );
    }
    
    $file = fopen( STWATT_UPLOADS_PATH . 'log.log', 'a' );
    
    echo fwrite( $file, "\n" . date( 'Y-m-d h:i:s' ) . ' :: ' . $message );
    
    fclose( $file );
}

/**
 * Cleaner output for WP Error Data from Strava API.
 *
 * @access public
 * @param string $data_obj (default: '').
 * @return string
 */
function stwatt_wp_error_data( $data_obj = '' ) {
    if ( empty( $data_obj ) || ! isset( $data_obj[0] ) ) {
        return;
    }

    $data_obj = $data_obj[0];
    $output = $data_obj->resource . ', ' . $data_obj->code . ' ' . $data_obj->field;

    return $output;
}

/**
 * Checks if activity exists.
 *
 * @access public
 * @param int $activity_id (default: 0)
 * @return void
 */
function stwatt_activity_exists( $activity_id = 0 ) {
    if ( stwatt()->athlete_activities_db->get_column_by( 'id', 'activity_id', $activity_id ) ) {
        return true;
    }

    return false;
}
