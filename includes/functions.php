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

function stwatt_athlete_exists( $athlete_id = 0 ) {
    if ( stwatt()->athletes_db->get_column_by( 'id', 'athlete_id', $athlete_id ) ) {
        return true;
    }

    return false;
}

function stwatt_is_athlete_authorized( $athlete_id = 0 ) {
    if ( stwatt()->athletes_db->get_column_by( 'id', 'athlete_id', $athlete_id ) ) {
        return true;
    }

    return false;
}

function stwatt_athlete( $athlete_id = 0 ) {
    $prefix = '_stwatt_';
    $athlete_id = intval( $athlete_id );

    if ( ! $athlete_id ) {
        $athlete_id = get_option( "{$prefix}athlete_id", 0 );
    }

    return new STWATT_Athlete( $athlete_id );
}

function stwatt_format_time() {

}

function stwatt_format_elevation() {

}

function stwatt_format_distance() {

}
