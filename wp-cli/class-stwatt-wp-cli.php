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
    // add param to add to db
    /**
     * List athlete activities
     *
     * ## OPTIONS
     *
     * [--id=<id>]
     * : The athlete id
     *
     * [--insert]
     * : Activities will be inserted into the db.
     *
     * [--<field>=<value>]
     * : Associative args for the event.
     *
     * ## EXAMPLES
     *
     *  # Get athlete activitites
     *  $ wp stwatt get_athlete_activities
     *  $ wp stwatt get_athlete_activities --id=4334
     *
     *  # Schedule new cron event and pass associative arguments.
     *  $ wp stwatt get_athlete_activities --id=4334 --before=1632355200 --per_page=50
     */
    public function get_athlete_activities( $args, $assoc_args ) {
        $prefix       = '_stwatt_';
        $current_year = date( 'Y' );

        $assoc_args = array_merge(
            array(
                'id'       => get_option( "{$prefix}athlete_id", 0 ),
                'before'   => strtotime( date( 'Y-m-d' ) ), // today
                'after'    => strtotime( $current_year . '-01-01' ), // first of the year.
                'page'     => 1, // default
                'per_page' => 50, // default
                'insert'   => 0,
            ),
            $assoc_args
        );

        extract( $assoc_args );

        $display_arr        = array();
        $all_activities     = array();
        $inserted_activites = 0;
        $params             = array(
            'before'   => $before,
            'after'    => $after,
            'page'     => $page,
            'per_page' => $per_page,
        );

        while ( true ) {
            $params['page'] = $page;

            $activities = stwatt()->api_athlete->get_activities( $id, 0, $params );

            if ( is_wp_error( $activities ) ) {
                break;
            }

            if ( empty( $activities ) ) {
                break;
            }

            $all_activities = array_merge( $all_activities, $activities );

            $page++;
        }

        if ( is_wp_error( $activities ) ) {
            WP_CLI::error( $activities->get_error_message() . ': ' . stwatt_wp_error_data( $activities->get_error_data() ) );
        }

        foreach ( $all_activities as $activity ) {
            $in_db       = 'no';
            $keys_to_use = array(
                'name',
                'id',
                'start_date_local',
            );

            // get just the data we need.
            $activity_details = array_intersect_key( get_object_vars( $activity ), array_flip( $keys_to_use ) );

            if ( stwatt_activity_exists( $activity_details['id'] ) ) {
                $in_db = 'yes';
            } elseif ( $insert ) {
                $inserted = stwatt()->api_athlete->add_activity_to_db( $activity );
                $inserted_activites++;
            }

            $display_arr[] = array(
                'date'  => $activity_details['start_date_local'],
                'name'  => $activity_details['name'],
                'id'    => $activity_details['id'],
                'in_db' => $in_db,
            );
        }

        WP_CLI\Utils\format_items( 'table', $display_arr, array( 'date', 'name', 'id', 'in_db' ) );
        WP_CLI::success( $inserted_activites . ' inserted into the db.' );
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
