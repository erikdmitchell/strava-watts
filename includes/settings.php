<?php

$token_data = stwatt()->auth->get_athlete_token_details( get_option( "{$prefix}athlete_id", 0 ) );
$cron_jobs  = _get_cron_array();

// stwatt_user_token_check
foreach ( $cron_jobs as $key => $cron_job ) {
    if ( array_key_exists( 'stwatt_user_token_check', $cron_job ) ) {
        foreach ( $cron_job as $details ) {
            // $schedule = $details['schedule'];
        }

        $next_run = get_date_from_gmt( date( 'Y-m-d H:i:s', $key ), 'Y-m-d H:i:s' );
    }
}

$expires_at = get_date_from_gmt( date( 'Y-m-d H:i:s', $token_data['expires_at'] ), 'Y-m-d H:i:s' );
?>

<div class="wrap">
    <h1>Strava API Settings</h1>

    <div class="stwatt-wrapper">
        <form method="post" action="">
            <?php wp_nonce_field( 'update_settings', 'stwatt_settings_nonce' ); ?>
            <div class="stwatt-row stwatt-cols">
                <div class="stwatt-col-2">
                    <label for="client-id">Client ID</label>
                </div>
                <div class="stwatt-col-8">
                    <input type="text" name="stwatt_settings[client_id]" class="code" id="client-id" value="<?php echo get_option( "{$prefix}client_id", '' ); ?>" />
                </div>
            </div>
            <div class="stwatt-row stwatt-cols">
                <div class="stwatt-col-2">
                    <label for="client-secret">Client Secret</label>
                </div>
                <div class="stwatt-col-8">
                    <input type="text" name="stwatt_settings[client_secret]" class="code" id="client-secret" value="<?php echo get_option( "{$prefix}client_secret", '' ); ?>" />
                </div>
            </div>
            <div class="stwatt-row stwatt-cols">
                <div class="stwatt-col-2">
                    <label for="athlete-id">Athlete ID</label>
                </div>
                <div class="stwatt-col-8">
                    <input type="text" name="stwatt_settings[athlete_id]" class="code" id="athlete-id" value="<?php echo get_option( "{$prefix}athlete_id", '' ); ?>" />
                </div>
            </div>  
            <div class="stwatt-row stwatt-cols">
                <div class="stwatt-col-2">
                    <label for="redirect-uri">Redirect URI</label>
                </div>
                <div class="stwatt-col-8">
                    <input type="text" name="stwatt_settings[redirect_uri]" class="code" id="redirect-uri" value="<?php echo get_option( "{$prefix}redirect_uri", $page_url ); ?>" />
                </div>
            </div>
    
            <div class="stwatt-row stwatt-cols">
                <div class="stwatt-col-2">
                    <label for="">Validate</label>
                </div>
                <div class="stwatt-col-4">
                    <?php echo stwatt()->auth->auth_button(); ?> 
                    <?php if ( stwatt_is_athlete_authorized( get_option( "{$prefix}athlete_id", '' ) ) ) : ?>
                        <span class="authorized">You are authorized</span>
                    <?php endif; ?>
                </div>
            </div>
    
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
        </form>
    </div>

    <div class="stwatt-wrapper">
        <h2>Token Info (<?php echo get_option( "{$prefix}athlete_id", 0 ); ?>)</h2>
        <div class="stwatt-layout token-details">
            <div>
                <div class="header-text">Expires</div>
                <div><?php echo $expires_at; ?></div>
                <?php echo time() >= $token_data['expires_at'] ? 'Expired' : ''; ?>
            </div>
            <div>
                <div class="header-text">Last Updated</div>
                <div><?php echo $token_data['last_updated']; ?></div>
            </div>
            <div>
                <div class="header-text">Next Run</div>
                <div><?php echo $next_run; ?></div>  
            </div>
        </div>    

        <p class="submit"><a href="<?php echo admin_url( 'options-general.php?page=stwatts-settings&action=force_tokens' ); ?>"><input type="button" name="update_tokens" id="update_tokens" class="button button-secondary" value="Update Tokens"></a></p>  
    </div>

    <div class="stwatt-wrapper">
        <h2>Logs</h2>
        <div class="stwatt-layout">
            <div>
    <?php
    foreach ( glob( STWATT_UPLOADS_PATH . '*.*' ) as $file ) {
        $file_url = str_replace( STWATT_UPLOADS_PATH, STWATT_UPLOADS_URL, $file );

        echo "$file_url<br>";
    }
    ?>
    
    <div id="log-viewer">  
        <?php
        $response = wp_remote_get( 'http://bike.test/wp-content/uploads/stwatts/log.log' );

        if ( is_array( $response ) && ! is_wp_error( $response ) ) {
            $headers = $response['headers']; // array of http header lines.
            $body    = $response['body']; // use the content.
        }
        ?>
        <pre><?php echo esc_html( $body ); ?></pre>
    </div>    
            </div>
        </div>
    </div>
    

</div>
