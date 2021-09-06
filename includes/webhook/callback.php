<?php
/**
 * This fires when the Strava API Webhook request is sent.
 * It validates the URL and the request.
 */
if ( isset( $_GET['hub_challenge'] ) ) {
    $data = array( 'hub.challenge' => $_GET['hub_challenge'] );
    header( 'HTTP/1.1 200 OK' );
    header( 'Content-Type: application/json; charset=utf-8' );
    echo json_encode( $data );

    exit;
}
