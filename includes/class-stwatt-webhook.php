<?php

/**
 * STWATT_Webhook class.
 */
class STWATT_Webhook {

    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        add_action( 'admin_init', array( $this, 'request' ), 0 );
    }

    /**
     * Webhook setup button.
     *
     * @access public
     * @static
     * @param bool $echo (default: true)
     * @return void
     */
    public static function button( $echo = true ) {
        $html = '<a href="' . admin_url( 'options-general.php?page=stwatts-settings&action=request_webhook' ) . '" class="button">Setup Webhook</a>';

        if ( $echo ) {
            echo $html;
        }

        return $html;
    }

    /**
     * Is there a webhook id.
     *
     * @access public
     * @static
     * @return void
     */
    public static function has_id() {
        $id = self::get_id();

        if ( $id ) {
            return true;
        }

        return false;
    }

    /**
     * Get webhook id.
     *
     * @access public
     * @static
     * @return void
     */
    public static function get_id() {
        $prefix = '_stwatt_';

        return get_option( "{$prefix}webhook_id", 0 );
    }

    /**
     * Request webhook.
     *
     * @access public
     * @return void
     */
    public function request() {
        if ( ! isset( $_GET['action'] ) || 'request_webhook' != $_GET['action'] ) {
            return;
        }

        $prefix = '_stwatt_';
        $client_id = get_option( "{$prefix}client_id", '' );
        $client_secret = get_option( "{$prefix}client_secret", '' );
        $callback_url = STWATT_URL . 'includes/webhook/request.php';
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

        $response_obj = json_decode( $response );

        // check webhook response.
        if ( isset( $response_obj->id ) ) {
            $this->store_id( $response_obj->id );
            $message_type = 'success';
            $message = urlencode( 'Received webhook id' );
        } else {
            $message_type = urlencode( 'error' );
            $message = urlencode( $response_obj->message );
        }

        // redirect to admin url
        wp_redirect( admin_url( "options-general.php?page=stwatts-settings&message_type={$message_type}&message={$message}" ) );
        exit;
    }

    /**
     * Store Webhook id.
     *
     * @access private
     * @param mixed $id
     * @return void
     */
    private function store_id( $id ) {
        $prefix = '_stwatt_';

        return update_option( "{$prefix}webhook_id", $id );
    }

}

new STWATT_Webhook();
