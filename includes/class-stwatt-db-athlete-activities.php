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
            'id'          => '%d',
            'activity_id' => '%d',
            'athlete_id'  => '%d',
            'name'        => '%s',
            'distance'    => '%s',
            'time'        => '%d',
            'elevation'   => '%d',
            'date'        => '%s',
            'bike_type'   => '%s',
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
            'athlete_id'  => 0,
            'name'        => '',
            'distance'    => 0,
            'time'        => 0,
            'elevation'   => 0,
            'date'        => date( 'Y-m-d H:i:s' ),
            'bike_type'   => '',
        );
    }

    public function get_activities( $args = array() ) {
        global $wpdb;

        $default_args = array(
            'athlete_id' => 0,
            'date'       => '',
            'stats'      => false,
            'limit'      => 10,
        );
        $args         = wp_parse_args( $args, $default_args );
        $select       = '*';
        $where_params = array(
            'athlete_id = ' . intval( $args['athlete_id'] ), // required.
        );

        // add date.
        if ( isset( $args['date'] ) && '' !== $args['date'] ) {
            $min_time    = '00:00:00';
            $max_time    = '23:59:59';
            $date_params = explode( ',', $args['date'] );
            sort( $date_params ); // sort so dates are in correct order.

            if ( count( $date_params ) > 1 ) {
                $start_date = $date_params[0];
                $end_date   = $date_params[1];
            } else {
                $start_date = $date_params[0];
                $end_date   = $date_params[0];
            }

            $where_params[] = "date >='{$start_date} {$min_time}' AND date <'{$end_date} {$max_time}'";
        }

        // limit.
        $limit_num = intval( $args['limit'] );
        if ( $limit_num >= 0 ) {
            $limit = " LIMIT {$limit_num}";
        } else {
            $limit = '';
        }

        $where = implode( ' AND ', $where_params );

        $query = "SELECT {$select} FROM $this->table_name WHERE {$where}{$limit}";

        return $wpdb->get_results( $query );
    }

    public function get_summary( $args = array() ) {
        $summary_data = array(
            'time'      => 0,
            'distance'  => 0,
            'elevation' => 0,
        );
        $activities   = $this->get_activities( $args );

        foreach ( $activities as $activity ) {
            $summary_data['time']      = $summary_data['time'] + $activity->time;
            $summary_data['distance']  = $summary_data['distance'] + $activity->distance;
            $summary_data['elevation'] = $summary_data['elevation'] + $activity->elevation;
        }

        // format.
        $seconds = $summary_data['time'];
        $hours   = floor( $seconds / 3600 );
        $mins    = floor( $seconds / 60 % 60 );
        $secs    = floor( $seconds % 60 );

        $summary_data['time']      = sprintf( '%02d:%02d:%02d', $hours, $mins, $secs );
        $summary_data['distance']  = round( round( $summary_data['distance'] * 3.288 ) * 0.000189394 ); // meters to feet, then feet to miles.
        $summary_data['elevation'] = round( $summary_data['elevation'] * 3.288 ); // meters to feet.

        return $summary_data;
    }

    /**
     * Get a single activity.
     *
     * @access public
     * @param int $activity_id (default: 0).
     * @return db object
     */
    public function get_activity( $activity_id = 0 ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->table_name WHERE activity_id = %s LIMIT 1", $activity_id ) );
    }

    /**
     * Create the table
     *
     * @access  public
     * @since   0.1.0
     */
    public function create_table() {}
}
