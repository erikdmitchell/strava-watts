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
        $assoc_args = array_merge(
            array(
                'id' => // get option,
            ),
            $assoc_args
        );

        extract( $assoc_args );

        // $activities = $this->get_strava_activities();

        //$this->add_activities($activities);

        if ( ! isset( $activities ) ) {
            WP_CLI::error( 'No activities found.' );
        }

        // success output? some sort of count?
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
