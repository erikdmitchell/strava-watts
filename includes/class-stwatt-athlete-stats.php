<?php

/**
 * STWATT_Athlete_Stats class.
 */
class STWATT_Athlete_Stats {

    /**
     * Construct class.
     *
     * @access public
     * @return void
     */
    public function __construct() {}

    // protect?
    public function stats( $athlete_id = 0 ) {
        return stwatt()->athlete_stats_db->get_stats( $athlete_id );
    }

}
