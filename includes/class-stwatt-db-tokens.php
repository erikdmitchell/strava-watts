<?php

class STWATT_DB_Tokens extends STWATT_DB {

    /**
     * Get things started
     *
     * @access  public
     * @since   0.1.0
     */
    public function __construct() {
        $this->table_name  = 'stwatt_tokens';
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
            'id'            => '%d',
            'athlete_id'    => '%d',
            'scope'         => '%s',
            'refresh_token' => '%s',
            'access_token'  => '%s',
            'expires_at'    => '%d',
            'last_updated'  => '%s',
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
            'athlete_id'    => 0,
            'scope'         => '',
            'refresh_token' => '',
            'access_token'  => '',
            'expires_at'    => 0,
            'last_updated'  => date( 'Y-m-d H:i:s' ),
        );
    }

    public function get_tokens() {
        global $wpdb;

        return $wpdb->get_results( "SELECT * FROM $this->table_name" );
    }

    /**
     * Create the table
     *
     * @access  public
     * @since   0.1.0
     */
    public function create_table() {}
}
