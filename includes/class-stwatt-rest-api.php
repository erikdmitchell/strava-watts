<?php

function stwatt_athlete_rest_api_endpoints() {
    $namespace = 'stwatt/v1';

    register_rest_route(
        $namespace,
        '/athlete',
        array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => 'stwatt_rest_route_athlete',
            'permission_callback' => '__return_true',
        ),
    );

    // not supported.
    /*
    register_rest_route(
        $namespace,
        '/athlete/(?P<athlete_id>.+)',
        array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => 'stwatt_rest_route_athlete',
            'permission_callback' => '__return_true',
            'args' => array(
                'athlete_id' => array(
                    'required' => false,
                    'type' => 'int',
                ),
            ),
        )
    );
    */

    // http://bike.test/wp-json/stwatt/v1/athlete/4334/activities/?date=2021-09-02
    register_rest_route(
        $namespace,
        '/athlete/(?P<athlete_id>.+)/activities',
        array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => 'stwatt_rest_route_athlete_activities',
            'permission_callback' => '__return_true',
            'args' => array(
                'athlete_id' => array(
                    'required' => false,
                    'type' => 'int',
                ),
            ),
        )
    );

    // http://bike.test/wp-json/stwatt/v1/athlete/4334/summary/?date=2021-09-02
    register_rest_route(
        $namespace,
        '/athlete/(?P<athlete_id>.+)/summary',
        array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => 'stwatt_rest_route_athlete_summary',
            'permission_callback' => '__return_true',
            'args' => array(
                'athlete_id' => array(
                    'required' => false,
                    'type' => 'int',
                ),
            ),
        )
    );

}
add_action( 'rest_api_init', 'stwatt_athlete_rest_api_endpoints' );

function stwatt_rest_route_athlete( $request ) {
    $response = stwatt_athlete(); // need to add support for specific athlete id.

    return rest_ensure_response( $response );
}

function stwatt_rest_route_athlete_activities( $request ) {
    $args = array(
        'athlete_id' => intval( $request['athlete_id'] ),
        'date' => $request['date'], // optional
        'stats' => $request['stats'], // optional
    );

    $response = stwatt_athlete_activities( $args );

    return rest_ensure_response( $response );
}

function stwatt_rest_route_athlete_summary( $request ) {
    $args = array(
        'athlete_id' => intval( $request['athlete_id'] ),
        'date' => $request['date'], // optional
        'stats' => $request['stats'], // optional
    );

    $response = stwatt_athlete_activities_summary( $args );

    return rest_ensure_response( $response );
}


// --- Move to functions.php --- //
function stwatt_athlete_activities_summary( $args = array() ) {
    $prefix = '_stwatt_';

    $default_args = array(
        'athlete_id' => get_option( "{$prefix}athlete_id", 0 ),
    );
    $args = wp_parse_args( $args, $default_args );

    // activities db call.
    return stwatt()->athlete_activities_db->get_summary( $args );
}

function stwatt_athlete_activities( $args = array() ) {
    $prefix = '_stwatt_';

    $default_args = array(
        'athlete_id' => get_option( "{$prefix}athlete_id", 0 ),
    );
    $args = wp_parse_args( $args, $default_args );

    // activities db call.
    return stwatt()->athlete_activities_db->get_activities( $args );
}
