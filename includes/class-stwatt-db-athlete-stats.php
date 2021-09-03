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
            'time' => '%d',
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
            'time' => 0,
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

    // may not be nedded.
    public function get_row_id( $athlete_id = 0 ) {
        global $wpdb;

        return $wpdb->get_results( $wpdb->prepare( "SELECT id FROM $this->table_name WHERE athlete_id = %s", $athlete_id ) );
    }

    public function update_stats( $athlete_id = 0, $activity_id = 0 ) {
        // get activity.
        $activity = stwatt()->athlete_activities_db->get_activity( $activity_id );

        // setup data for db.
        $data = array(
            'elevation' => $activity->elevation,
            'distance' => $activity->distance,
            'time' => $activity->time,
            "distance_{$activity->bike_type}" => $activity->distance,
        );

        // check athlete exists and update, otherwise, insert.
        if ( $row_id = $this->athlete_stats_exist( $athlete_id ) ) {
            // update data values.
            foreach ( $data as $key => $value ) {
                $data[ $key ] = $this->calculate_stat( $key, $value, $athlete_id );
            }

            $this->update( $row_id, $data );
        } else {
            $data['athlete_id'] = $athlete_id;

            $this->insert( $data, 'athlete_stats' );
        }

        return;
    }

    protected function calculate_stat( $field = '', $value = '', $athlete_id = 0 ) {
        if ( empty( $field ) || empty( $value ) || ! $athlete_id ) {
            return;
        }

        $db_value = $this->get_column_by( $field, 'athlete_id', $athlete_id );
        $new_value = $value + $db_value;

        return $new_value;
    }

    public function athlete_stats_exist( $athlete_id = 0 ) {
        return $this->get_column_by( 'id', 'athlete_id', $athlete_id );
    }

    /**
     * Create the table
     *
     * @access  public
     * @since   0.1.0
     */
    public function create_table() {}
}
