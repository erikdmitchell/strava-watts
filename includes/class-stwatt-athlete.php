<?php

/**
 * STWATT_Athlete class.
 */
class STWATT_Athlete {

    public $id = 0;

    public $activities = '';

    public $stats = '';

    private $token = '';

    /**
     * Construct class.
     *
     * @access public
     * @return void
     */
    public function __construct( $id = 0 ) {
        $this->id = $id;
        $this->token = $this->get_token();
        $this->activities = $this->get_activities();
        $this->stats = $this->get_stats();
        // $this->get_strava_activities();
        // testing
        /*
        $file = STWATT_ABSPATH . 'activities.json';
        $contents = file_get_contents( $file );
        $activities = json_decode( $contents ); // will be passed

        $this->add_activities($activities);
        */
    }

    protected function get_strava_activities( $id = 0, $params = array() ) {
        if ( $id ) {
            return $this->get_strava_activity( $id );
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

    protected function get_strava_activity( $id = 0 ) {
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

    protected function add_activities( $activities = '' ) {
        if ( empty( $activities ) ) {
            return;
        }

        foreach ( $activities as $activity ) {
            $this->add_activity( $activity );
        }

        return;
    }

    protected function add_activity( $activity = '' ) {
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

        // get just the data we need.
        $activity_details = array_intersect_key( get_object_vars( $activity ), array_flip( $keys_to_use ) );

        // get bike type from gear.
        $gear = $this->get_gear( $activity_details['gear_id'] );
        $activity_details['bike_type'] = $gear->bike_type;

        // clean up keys for our db.
        $data = array(
            'activity_id' => $activity_details['id'],
            'athlete_id' => $this->id,
            'name' => $activity_details['name'],
            'distance' => $activity_details['distance'],
            'time' => $activity_details['moving_time'],
            'elevation' => $activity_details['total_elevation_gain'],
            'date' => $activity_details['start_date_local'],
            'bike_type' => $activity_details['bike_type'],
        );

        // we need a check for dups.
        // add to db.
        stwatt()->athlete_activities_db->insert( $data, 'athlete_activity' );

        // add to athlete stats.
        stwatt()->athlete_stats_db->update_stats( $data['athlete_id'], $data['activity_id'] );

        return;
    }

    // non strava stuff.
    public function get_activities() {
        return stwatt()->athlete_activities_db->get_activities( $this->id );
    }

    public function get_stats() {
        $athlete_stats = new STWATT_Athlete_Stats();

        return $athlete_stats->stats( $this->id );
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
