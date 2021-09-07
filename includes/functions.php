<?php
// roll into class?
function stwatt_add_athlete( $data = '' ) {
    if ( empty( $data ) ) {
        return;
    }

    if ( stwatt_athlete_exists( $data->id ) ) {
        return;
    }
    /*
    stdClass Object
        (
            [profile_medium] => https://dgalywyr863hv.cloudfront.net/pictures/athletes/4334/84512/1/medium.jpg
            [profile] => https://dgalywyr863hv.cloudfront.net/pictures/athletes/4334/84512/1/large.jpg
        )
    */
    $insert_data = array(
        'age' => '',
        'athlete_id' => $data->id,
        'first_name' => $data->firstname,
        'gender' => $data->sex,
        'last_name' => $data->lastname,
    );

    stwatt()->athletes_db->insert( $insert_data, 'athlete' );
}

/**
 * Check if athlete exists.
 * 
 * @access public
 * @param int $athlete_id (default: 0)
 * @return void
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
 * @return void
 */
function stwatt_athlete( $athlete_id = 0 ) {
    $prefix = '_stwatt_';
    $athlete_id = intval( $athlete_id );

    if ( ! $athlete_id ) {
        $athlete_id = get_option( "{$prefix}athlete_id", 0 );
    }

    return new STWATT_Athlete( $athlete_id );
}

function stwatt_str_wrap($str = '', $wrap_start = '<span>', $wrap_end = '</span>') {
    if (empty($str))
        return;
        
    $wrapped = '';
    $str_arr = str_split($str);

    foreach ($str_arr as $char) {
        $wrapped .= $wrap_start.$char.$wrap_end;
    }
    
    echo $wrapped;        
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
    $file = fopen( STWATT_PATH . 'log.log', 'a' );
    echo fwrite( $file, "\n" . date( 'Y-m-d h:i:s' ) . ' :: ' . $message );
    fclose( $file );
}
