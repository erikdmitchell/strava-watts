<?php

class STWATT_DB_Athlete_Activities extends STWATT_DB {

    /**
     * Get things started
     *
     * @access  public
     * @since   0.1.0
     */
    public function __construct() {
        $this->table_name  = 'stwatt_athlete_activities';
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
            'activity_id' => '%d',
            'name' => '%s',
            'distance' => '%d',
            'moving_time' => '%d',
            'elevation' => '%d',
            'date' => '%s',
            'bike_type' => '%s',
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
            'activity_id' => 0,
            'name' => '',
            'distance' => 0,
            'moving_time' => 0,
            'elevation' => 0,
            'date' => date( 'Y-m-d H:i:s' ),
            'bike_type' => '',            
        );
    }

    /**
     * Create the table
     *
     * @access  public
     * @since   0.1.0
     */
    public function create_table() {}
}
