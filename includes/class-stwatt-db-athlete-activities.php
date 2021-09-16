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
            'athlete_id' => '%d',
            'name' => '%s',
            'distance' => '%s',
            'time' => '%d',
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
            'athlete_id' => 0,
            'name' => '',
            'distance' => 0,
            'time' => 0,
            'elevation' => 0,
            'date' => date( 'Y-m-d H:i:s' ),
            'bike_type' => '',
        );
    }

    public function get_activities( $args = array() ) {
        global $wpdb;
        
        $default_args = array(
            'athlete_id' => 0,
            'date' => '',
            'limit' => 10,
        );
        $args = wp_parse_args( $args, $default_args );
        $select = '*';
        $where_params = array(
            'athlete_id' => intval( $args['athlete_id'] ), // required
        );

        // add date.        
        if (isset($args['date']) && '' !== $args['date']) {
            $where_params['date'] = $args['date'];
        }
        
        // limit.
        $limit_num = intval( $args['limit'] );
        if ($limit_num >= 0) {
            $limit = " LIMIT {$limit_num}";
        } else {
            $limit = '';
        }
                
print_r($args);
print_r($where_params);
// IN THE DB DATE INCLUDES TIME

        $where = http_build_query($where_params, '', ' AND ');

$query = "SELECT {$select} FROM $this->table_name WHERE {$where}{$limit}";



echo "\n{$query}\n";

        //return $wpdb->get_results( $wpdb->prepare( "SELECT {$select} FROM $this->table_name WHERE %s", $where ) );
        return $wpdb->get_results($query);
    }

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
