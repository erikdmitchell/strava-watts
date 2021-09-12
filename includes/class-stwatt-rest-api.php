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

}
add_action( 'rest_api_init', 'stwatt_athlete_rest_api_endpoints' );

function stwatt_rest_route_athlete( $request ) {
    $response = stwatt_athlete();

    return rest_ensure_response( $response );
}
