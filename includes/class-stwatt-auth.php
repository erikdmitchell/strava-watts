<?php

class STWATT_Auth {
    /**
     * Construct class.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        add_action( 'admin_init', array( $this, 'token_exchange_check' ), 0 );
        add_action( 'stwatt_user_token_check', array($this, 'update_tokens' ) );
    }

    public function init() {}

    public function frontend_scripts_styles() {}

    // Authorize button.
    public function auth_button() {
        $prefix = '_stwatt_';
        $client_id = get_option( "{$prefix}client_id", '' );
        $redirect_uri = admin_url( 'options-general.php?page=stwatts-settings' );
        $auth_url = "http://www.strava.com/oauth/authorize?client_id={$client_id}&response_type=code&redirect_uri={$redirect_uri}&approval_prompt=force&scope=read";

        $html = '<a href="' . $auth_url . '" class="button">Authorize</a>';

        return $html;
    }

    public function token_exchange_check() {
        if ( ! isset( $_GET['page'] ) || 'stwatts-settings' != $_GET['page'] ) {
            return;
        }

        if ( ! isset( $_GET['code'] ) || '' == $_GET['code'] ) {
            return;
        }

        $this->token_exchange( $_GET['code'] );
    }

    private function token_exchange( $code = '' ) {
        $prefix = '_stwatt_';
        $client_id = get_option( "{$prefix}client_id", '' );
        $client_secret = get_option( "{$prefix}client_secret", '' );
        $redirect_uri = admin_url( 'options-general.php?page=stwatts-settings' );

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => "https://www.strava.com/oauth/token?client_id={$client_id}&client_secret={$client_secret}&code={$code}&grant_type=authorization_code",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_FAILONERROR => true, // Required for HTTP error codes to be reported via our call to curl_error($curl)
            )
        );

        $response = curl_exec( $curl );

        if ( curl_errno( $curl ) ) {
            $error_msg = curl_error( $curl );
        }

        curl_close( $curl );

        if ( isset( $error_msg ) ) {
            echo $error_msg;

            return;
        }

        $response_obj = json_decode( $response );

        $this->store_token_data( $response_obj );

        stwatt_add_athlete( $response_obj->athlete );
    }

    private function store_token_data( $data ) {
        $insert_data = array(
            'refresh_token' => $data->refresh_token,
            'access_token' => $data->access_token,
            'expires_at' => $data->expires_at,
        );

        // check if we already have the token data, then we just update the token info.
        if ( stwatt_is_athlete_authorized( $data->athlete->id ) ) {
            $row_id = stwatt_get_athlete_token_id( $data->athlete->id );

            stwatt()->tokens_db->update( $row_id, $insert_data );
        } else {
            $insert_data['athlete_id'] = $data->athlete->id;
            $insert_data['scope'] = 'read';

            stwatt()->tokens_db->insert( $insert_data, 'token' );
        }

        wp_redirect( admin_url( 'options-general.php?page=stwatts-settings' ) );
    }
    
    public function update_tokens() {
        // is token expiration in the past?
        $tokens = stwatt()->tokens_db->get_tokens();
print_r($tokens);        
    }

}
