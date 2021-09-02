<?php

class STWATT_Athlete {
    /**
     * Construct class.
     *
     * @access public
     * @return void
     */
    public function __construct( $id = 0 ) {
        $this->id = $id;
        $this->token = $this->get_token();
        // $this->activities = $this->get_activities();
        $this->act = $this->process_activities();
    }

    protected function get_activities( $id = 0, $params = array() ) {
        if ( $id ) {
            return $this->get_activity( $id );
        }

        $default_params = array(
            'before' => '',
            'after' => '',
            'page' => 1, // strava default.
            'per_page' => 30, // strava default.
            'before' => '',
        );
        $params = wp_parse_args( $params, $default_params );
        $param_query = $this->build_query_params( $params );

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => "https://www.strava.com/api/v3/athlete/activities?{$param_query}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer {$this->token}",
                ),
            )
        );

        $response = curl_exec( $curl );

        curl_close( $curl );

        return json_decode( $response );
    }

    protected function get_activity( $id = 0 ) {
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => "https://www.strava.com/api/v3/activities/{$id}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer {$this->token}",
                ),
            )
        );

        $response = curl_exec( $curl );

        curl_close( $curl );

        return json_decode( $response );
    }

    private function get_token() {
        return stwatt()->tokens_db->get_column_by( 'access_token', 'athlete_id', $this->id );
    }

    protected function build_query_params( $params = array() ) {
        $clean_params = array_filter( $params );

        return http_build_query( $clean_params );
    }

    protected function process_activities() {
        $file = STWATT_ABSPATH . 'activities.json';
        $contents = file_get_contents( $file );
        $activities = json_decode( $contents ); // will be passed

        foreach ( $activities as $activity ) {
            $this->process_activity( $activity );
        }

        return;
    }

    protected function process_activity( $activity = '' ) {
        if ( empty( $activity ) ) {
            return;
        }

        $keys_to_use = array(
            'name',
            'distance',
            'moving_time',
            'total_elevation_gain',
            'id',
            'start_date_local',
            'gear_id',
        );
        $activity_details = array_intersect_key( get_object_vars( $activity ), array_flip( $keys_to_use ) );

        // get bike type from gear.
        $gear = $this->get_gear( $activity_details['gear_id'] );
        $activity_details['bike_type'] = $gear->bike_type;
        /*
        Array
        (
        [name] => I’m beginning to regret this block
        [distance] => 39964.1
        [moving_time] => 5173
        [total_elevation_gain] => 576
        [id] => 5894343616
        [start_date_local] => 2021-09-02T11:53:28Z
        [gear_id] => b6540497
        [bike_type] => road
        )
        */
    }

    // move to gear class?

    public function get_gear( $id = 0 ) {
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => "https://www.strava.com/api/v3/gear/{$id}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer {$this->token}",
                ),
            )
        );

        $response = curl_exec( $curl );

        curl_close( $curl );

        $response_obj = json_decode( $response );

        // add bike type.
        $response_obj->bike_type = $this->get_bike_type( $response_obj->frame_type );

        return $response_obj;
    }

    // type set by strava
    public function get_bike_type( $id = 0 ) {
        switch ( $id ) {
            case 1:
                $type = 'mtb';
                break;
            case 2:
                $type = 'cross';
                break;
            case 3:
                $type = 'road';
                break;
            case 4:
                $type = 'tt';
                break;
            case 5:
                $type = 'gravel';
                break;
            default:
                $type = 'road';
        }

        return $type;
    }

}
