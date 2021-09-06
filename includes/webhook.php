<?php

if ( isset( $_GET['hub_challenge'] ) ) {
    $data = array( 'hub.challenge' => $_GET['hub_challenge'] );
    header( 'HTTP/1.1 200 OK' );
    header( 'Content-Type: application/json; charset=utf-8' );
    echo json_encode( $data );

    exit;
}

$client_id = '54562';
$client_secret = '378d02856996b61155a46e1474f38a0156e25d04';
$callback_url = 'https://erikmitchell.net/stva/';
$verify_token = 'STRAVA';

$curl = curl_init();

curl_setopt_array(
    $curl,
    array(
        CURLOPT_URL => "https://www.strava.com/api/v3/push_subscriptions?client_id={$client_id}&client_secret={$client_secret}&callback_url={$callback_url}&verify_token={$verify_token}",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
    )
);

$response = curl_exec( $curl );

curl_close( $curl );

echo $response;
