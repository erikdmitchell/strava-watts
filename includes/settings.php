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
                    <label for="user-token">User Token</label> N/A?
                </div>
                <div class="stwatt-col-8">
                    <input type="text" name="stwatt_settings[user_token]" class="code" id="user-token" value="<?php echo get_option( "{$prefix}user_token", '' ); ?>" />
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
                    <?php echo stwatt()->auth->auth_button(); ?> ARE YOU AUTH?
                </div>
            </div>
    
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
        </form>
    </div>
</div>