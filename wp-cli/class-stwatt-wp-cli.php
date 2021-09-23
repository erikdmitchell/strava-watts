<?php
/**
 * WP CLI class
 *
 * @package stwatt
 * @since   0.1.0
 */


/**
 * STWATT_WP_CLI class.
 */
class STWATT_WP_CLI {

    /**
     * Run GraphQL query
     *
     * ## OPTIONS
     *
     * [--id=<id>]
     * : The athlete id
     *
     * ## EXAMPLES
     *
     * wp stwatt import_athlete_activities
     * wp stwatt import_athlete_activities --id=4334
     */
    public function import_athlete_activities( $args, $assoc_args ) {
        $prefix = '_stwatt_';

        $assoc_args = array_merge(
            array(
                'id' => get_option( "{$prefix}athlete_id", 0 ),
            ),
            $assoc_args
        );

        extract( $assoc_args );

        $return = stwatt()->api_athlete->import_strava_activities( $id );

        if ( $return ) {
            WP_CLI::success( 'Activities imported!' );
        } else {
            WP_CLI::error( 'There was an error importing activities.' );
        }
    }
// wp stwatt get_athlete_activities --id=4334
    public function get_athlete_activities( $args, $assoc_args ) {
        $prefix = '_stwatt_';

        $assoc_args = array_merge(
            array(
                'id' => get_option( "{$prefix}athlete_id", 0 ),
            ),
            $assoc_args
        );

        extract( $assoc_args );
        
        $display_arr = array();
        $params = array(
            'before' => strtotime('2021-09-01'),
            'after' => '',
            //'page' => 1, // strava default.
            'per_page' => 100, // may be max
        );
        $activities = stwatt()->api_athlete->get_activities( $id, 0, $params );

        if( is_wp_error( $activities ) ) {
            WP_CLI::Error($activities->get_error_message());
        }
        
        foreach ( $activities as $activity ) {    
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

            $display_arr[] = array(
                'date' => $activity_details['start_date_local'],
                'name' => $activity_details['name'],
                'id' => $activity_details['id'],
            );
        }

        //WP_CLI::log(count($activities));
        WP_CLI\Utils\format_items( 'table', $display_arr, array( 'date', 'name', 'id' ) );
    }
    
}

/**
 * Register WP CLI class.
 *
 * @access public
 * @return void
 */
function stwatt_register_commands() {
    WP_CLI::add_command( 'stwatt', 'STWATT_WP_CLI' );
}

add_action( 'cli_init', 'stwatt_register_commands' );
