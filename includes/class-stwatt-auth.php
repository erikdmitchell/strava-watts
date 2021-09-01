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
    }
    
    public function init() {}
    
    public function frontend_scripts_styles() {}
    
    // Authorize button.
    public function auth_button() {
        $prefix = '_stwatt_';
        $client_id = get_option( "{$prefix}client_id", '' );
        $redirect_uri = admin_url( 'options-general.php?page=stwatts-settings');
        $auth_url = "http://www.strava.com/oauth/authorize?client_id={$client_id}&response_type=code&redirect_uri={$redirect_uri}&approval_prompt=force&scope=read";
        
        $html = '<a href="'.$auth_url.'" class="button">Authorize</a>';
        
        return $html;
    }

    public function token_exchange_check() {
        if (!isset($_GET['page']) || 'strava-api-settings' != $_GET['page'])
            return;
    
        if (!isset($_GET['code']) || '' == $_GET['code'])
            return;
            
        $this->token_exchange($_GET['code']);
    }

    private function token_exchange($code = '') {   
        $prefix = '_stwatt_';
        $client_id = get_option( "{$prefix}client_id", '' );
        $client_secret = get_option( "{$prefix}client_secret", '' );
        $redirect_uri = admin_url( 'options-general.php?page=stwatts-settings');
    
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.strava.com/oauth/token?client_id={$client_id}&client_secret={$client_secret}&code={$code}&grant_type=authorization_code",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_FAILONERROR => true, // Required for HTTP error codes to be reported via our call to curl_error($curl)
        ));
        
        $response = curl_exec($curl);
    
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        
        curl_close($curl);
        
        if (isset($error_msg)) {
            echo $error_msg;
            
            return;
        }
    
        $response_obj = json_decode($response);
        
        $this->store_token_data($response_obj->refresh_token, $response_obj->access_token, $response_obj->expires_at);
        
        //$this->stwatt_store_athlete_data($response_obj->athlete);   
    }
    
    private function store_token_data($refresh, $access, $expires) {}

}
