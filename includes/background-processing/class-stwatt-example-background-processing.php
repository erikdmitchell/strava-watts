<?php

class STWATT_Example_Background_Processing {

    /**
     * @var WP_Example_Request
     */
    protected $process_single;

    /**
     * @var WP_Example_Process
     */
    protected $process_all;

    /**
     * Example_Background_Processing constructor.
     */
    public function __construct() {
        add_action( 'plugins_loaded', array( $this, 'init' ) );
        add_action( 'init', array( $this, 'process_handler' ) );
    }

    /**
     * Init
     */
    public function init() {
        include_once( STWATT_ABSPATH . 'includes/background-processing/class-stwatt-example-request.php' );
        include_once( STWATT_ABSPATH . 'includes/background-processing/class-stwatt-example-process.php' );

        $this->process_single = new STWATT_Example_Request();
        $this->process_all = new STWATT_Example_Process();
    }

    /**
     * Process handler
     */
    public function process_handler() {
        if ( ! isset( $_GET['process'] ) || ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'process' ) ) {
            return;
        }

        if ( 'single' === $_GET['process'] ) {
            $this->handle_single();
        }

        if ( 'all' === $_GET['process'] ) {
            $this->handle_all();
        }
    }

    /**
     * Handle single
     */
    protected function handle_single() {
        $data = array(
            'var1' => 'value1',
            'var2' => 'value2',
        );

        $this->process_single->data( array( 'data' => $data ) )->dispatch();
    }

    /**
     * Handle all
     */
    protected function handle_all() {
        $names = array(
            'Kristyn Lile',
            'Laure Lupercio',
            'Marcel Holden',
            'Sharyl Hargreaves',
            'Pamila Dolce',
            'Argelia Beeman',
            'Doloris Reinert',
            'Christoper Haughton',
            'Malisa Salvaggio',
            'Katelynn Mccaw',
        );

        foreach ( $names as $name ) {
            $this->process_all->push_to_queue( $name );
        }

        $this->process_all->save()->dispatch();
    }

}

new STWATT_Example_Background_Processing();
