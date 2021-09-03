<?php

class STWATT_DB_Athlete_Stats extends STWATT_DB {

    /**
     * Get things started
     *
     * @access  public
     * @since   0.1.0
     */
    public function __construct() {
        $this->table_name  = 'stwatt_athlete_stats';
        $this->primary_key = 'id';
        $this->version     = '0.1.0';
    }

    /**
     * Get columns and formats
     *
     * @access  public
     * @since   0.1.0
     */
    public function get_columns() {
        return array(
            'id' => '%d',
            'athlete_id' => '%d',
            'elevation' => '%d',
            'distance' => '%s',
            'distance_road' => '%s',
            'distance_cross' => '%s',
            'distance_mtb' => '%s',
            'distance_tt' => '%s',
            'distance_gravel' => '%s',
        );
    }

    /**
     * Get default column values
     *
     * @access  public
     * @since   0.1.0
     */
    public function get_column_defaults() {
        return array(
            'athlete_id' => 0,
            'elevation' => 0,
            'distance' => 0,
            'distance_road' => 0,
            'distance_cross' => 0,
            'distance_mtb' => 0,
            'distance_tt' => 0,
            'distance_gravel' => 0,
        );
    }

    public function get_stats( $athlete_id = 0 ) {
        global $wpdb;

        return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $this->table_name WHERE athlete_id = %s", $athlete_id ) );
    }

    /**
     * Create the table
     *
     * @access  public
     * @since   0.1.0
     */
    public function create_table() {}
}
