<?php

/**
 * STWATT_Athlete class.
 */
class STWATT_Athlete {

    /**
     * id
     * 
     * (default value: 0)
     * 
     * @var int
     * @access public
     */
    public $id = 0;

    /**
     * activities
     * 
     * (default value: '')
     * 
     * @var string
     * @access public
     */
    public $activities = '';

    /**
     * stats
     * 
     * (default value: '')
     * 
     * @var string
     * @access public
     */
    public $stats = '';

    /**
     * Construct class.
     *
     * @access public
     * @return void
     */
    public function __construct( $id = 0 ) {
        $this->id = $id;
        $this->activities = $this->get_activities();
        $this->stats = $this->get_stats();
    }

    /**
     * Get athlete activities.
     * 
     * @access public
     * @return object
     */
    public function get_activities() {
        return stwatt()->athlete_activities_db->get_activities( $this->id );
    }

    /**
     * Get athlete stats.
     * 
     * @access public
     * @return object
     */
    public function get_stats() {
        $athlete_stats = new STWATT_Athlete_Stats();

        return $athlete_stats->stats( $this->id );
    }

}
