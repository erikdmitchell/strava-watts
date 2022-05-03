<?php

/**
 * STWATT_Auth class.
 */
class STWATT_Auth {
    /**
     * Construct class.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        add_action( 'admin_init', array( $this, 'token_exchange_check' ), 0 );
        add_action( 'stwatt_user_token_check', array( $this, 'update_tokens' ) );
    }

    /**
     * Generate authorize button.
     *
     * @access public
     * @return void
     */
    public function auth_button() {
        $scope        = 'read,read_all,activity:read,activity:read_all';
        $prefix       = '_stwatt_';
        $client_id    = get_option( "{$prefix}client_id", '' );
        $redirect_uri = admin_url( 'options-general.php?page=stwatts-settings' );
        $auth_url     = "http://www.strava.com/oauth/authorize?client_id={$client_id}&response_type=code&redirect_uri={$redirect_uri}&approval_prompt=force&scope={$scope}";

        $html = '<a href="' . $auth_url . '" class="button">Authorize</a>';

        return $html;
    }

    /**
     * Check if we are getting data from Strava API.
     *
     * @access public
     * @return void
     */
    public function token_exchange_check() {
        if ( ! isset( $_GET['page'] ) || 'stwatts-settings' != $_GET['page'] ) {
            return;
        }

        if ( ! isset( $_GET['code'] ) || '' == $_GET['code'] ) {
            return;
        }

        $this->token_exchange( $_GET['code'] );
    }

    /**
     * Exchange token via Strava API.
     *
     * @access private
     * @param string $code (default: '')
     * @return void
     */
    private function token_exchange( $code = '' ) {
        $prefix        = '_stwatt_';
        $client_id     = get_option( "{$prefix}client_id", '' );
        $client_secret = get_option( "{$prefix}client_secret", '' );
        $redirect_uri  = admin_url( 'options-general.php?page=stwatts-settings' );
        $url           = "https://www.strava.com/oauth/token?client_id={$client_id}&client_secret={$client_secret}&code={$code}&grant_type=authorization_code";

        $response = wp_remote_post(
            $url,
            array(
                'method'      => 'POST',
                'timeout'     => 0,
                'redirection' => 10,
                'httpversion' => '1.1',
            )
        );

        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();

            stwatt_log( 'Token Exchange error: ' . $error_message );

            echo "Something went wrong: $error_message";

            return;
        }

        $response_obj = json_decode( $response['body'] );

        $this->store_token_data( $response_obj );

        // stwatt()->api_athlete->add_athlete( $response_obj->athlete ); NOT CALLED
    }

    /**
     * Store token info via Strava callback.
     *
     * @access private
     * @param mixed $data
     * @return void
     */
    private function store_token_data( $data ) {
        $scope = 'read,read_all,activity:read,activity:read_all';

        if ( empty( $data->refresh_token ) || empty( $data->access_token ) || empty( $data->expires_at ) ) {
            echo 'missing data'; // make wperror
            return;
        }

        $insert_data = array(
            'refresh_token' => $data->refresh_token,
            'access_token'  => $data->access_token,
            'expires_at'    => $data->expires_at,
            'last_updated'  => date( 'Y-m-d H:i:s' ),
        );

        // check if we already have the token data, then we just update the token info.
        if ( stwatt_is_athlete_authorized( $data->athlete->id ) || $this->athlete_token_id_exists( $data->athlete->id ) ) {
            $this->update_token( $data->athlete->id, $insert_data );
        } else {
            $insert_data['athlete_id'] = $data->athlete->id;
            $insert_data['scope']      = $scope;

            $insert_id = $this->add_token( $insert_data );

            if ( is_wp_error( $insert_id ) ) {
                echo $insert_id->get_error_message();
            }
        }

        wp_redirect( admin_url( 'options-general.php?page=stwatts-settings' ) ); // add success message.
    }

    private function athlete_token_id_exists( $athlete_id = 0 ) {
        if ( stwatt()->tokens_db->get_column_by( 'id', 'athlete_id', $athlete_id ) ) {
            return true;
        }

        return false;
    }

    /**
     * Add token to db.
     *
     * @access private
     * @param string $data (default: '')
     * @return void
     */
    private function add_token( $data = '' ) {
        return stwatt()->tokens_db->insert( $data, 'token' );
    }

    /**
     * Update token in db.
     *
     * @access private
     * @param int    $athlete_id (default: 0)
     * @param string $data (default: '')
     * @return void
     */
    private function update_token( $athlete_id = 0, $data = '' ) {
        $row_id = $this->get_athlete_token_id( $athlete_id );

        return stwatt()->tokens_db->update( $row_id, $data );
    }

    /**
     * Update expired tokens.
     *
     * @access public
     * @return void
     */
    public function update_tokens() {
        stwatt_log( 'Begin token updates' );

        // get tokens from db.
        $tokens = stwatt()->tokens_db->get_tokens();

        // is token expiration in the past?
        foreach ( $tokens as $token ) {
            // check for null.
            if ( null === $token->access_token ) {
                stwatt_log( "{$token->athlete_id} has a NULL token" );
                return;
            }

            if ( ! $this->is_token_valid( $token->expires_at ) ) {
                $this->refresh_token( $token->refresh_token, $token->athlete_id );
                stwatt_log( "{$token->athlete_id} token refreshed" );
            }
        }
    }

    /**
     * Refresh token via Strava API.
     *
     * @access private
     * @param string $refresh_token (default: '')
     * @param int    $athlete_id (default: 0)
     * @return void
     */
    private function refresh_token( $refresh_token = '', $athlete_id = 0 ) {
        $prefix        = '_stwatt_';
        $client_id     = get_option( "{$prefix}client_id", '' );
        $client_secret = get_option( "{$prefix}client_secret", '' );

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL            => "https://www.strava.com/api/v3/oauth/token?client_id={$client_id}&client_secret={$client_secret}&grant_type=refresh_token&refresh_token={$refresh_token}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => '',
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => 'POST',
            )
        );

        $response = curl_exec( $curl );

        if ( curl_errno( $curl ) ) {
            $error_msg = curl_error( $curl );
        }

        curl_close( $curl );

        if ( isset( $error_msg ) ) {
            stwatt_log( 'Refresh Token error: ' . $error_msg );

            echo $error_msg;

            return;
        }

        $response_obj = json_decode( $response );

        $token_data = array(
            'refresh_token' => $response_obj->refresh_token,
            'access_token'  => $response_obj->access_token,
            'expires_at'    => $response_obj->expires_at,
            'last_updated'  => date( 'Y-m-d H:i:s' ),
        );

        // update in db.
        return $this->update_token( $athlete_id, $token_data );
    }

    /**
     * Check for valid token.
     *
     * @access protected
     * @param string $expires_epoch (default: '')
     * @return void
     */
    protected function is_token_valid( $expires_epoch = '' ) {
        if ( time() >= $expires_epoch ) {
            return false;
        }

        return true;
    }

    /**
     * Retrieve athlete token row id.
     *
     * @access private
     * @param int $athlete_id (default: 0)
     * @return void
     */
    private function get_athlete_token_id( $athlete_id = 0 ) {
        $token_id = stwatt()->athletes_db->get_column_by( 'id', 'athlete_id', $athlete_id );

        if ( $token_id ) {
            return $token_id;
        }

        return 0;
    }

    /**
     * Get athlete token details.
     *
     * @access public
     * @param int $athlete_id (default: 0)
     * @return void
     */
    public function get_athlete_token_details( $athlete_id = 0 ) {
        return $this->athlete_token_details( $athlete_id );
    }

    /**
     * Query db for athlete token details.
     *
     * @access private
     * @param int $athlete_id (default: 0)
     * @return void
     */
    private function athlete_token_details( $athlete_id = 0 ) {
        return array(
            'expires_at'   => stwatt()->tokens_db->get_column_by( 'expires_at', 'athlete_id', $athlete_id ),
            'last_updated' => stwatt()->tokens_db->get_column_by( 'last_updated', 'athlete_id', $athlete_id ),
        );
    }
}
