<?php
/**
 * STWATT class
 *
 * @package stwatt
 * @since   0.1.0
 */

/**
 * Final STWATT class.
 *
 * @final
 */
final class STWATT {

    /**
     * Version
     *
     * (default value: '0.1.0')
     *
     * @var string
     * @access public
     */
    public $version = '0.1.0';

    /**
     * _instance
     *
     * (default value: null)
     *
     * @var mixed
     * @access protected
     * @static
     */
    protected static $_instance = null;

    /**
     * Instance function.
     *
     * @access public
     * @static
     * @return instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Construct class.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Define constants.
     *
     * @access private
     * @return void
     */
    private function define_constants() {
        $this->define( 'STWATT_ABSPATH', dirname( STWATT_PLUGIN_FILE ) . '/' );
        $this->define( 'STWATT_VERSION', $this->version );
        $this->define( 'STWATT_PATH', plugin_dir_path( __FILE__ ) );
        $this->define( 'STWATT_URL', plugin_dir_url( __FILE__ ) );
        $this->define( 'STWATT_ASSETS_URL', plugin_dir_url( __DIR__ ) . 'assets/' );
        $this->define( 'STWATT_DB_VERSION', '0.1.0' ); // may move to db class.
    }

    /**
     * Custom define function.
     *
     * @access private
     * @param mixed $name string.
     * @param mixed $value string.
     * @return void
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    /**
     * Include plugin files.
     *
     * @access public
     * @return void
     */
    public function includes() {
        include_once( STWATT_ABSPATH . 'includes/class-stwatt-admin.php' );
        include_once( STWATT_ABSPATH . 'includes/class-stwatt-auth.php' );
        include_once( STWATT_ABSPATH . 'includes/class-stwatt-db.php' );
        include_once( STWATT_ABSPATH . 'includes/class-stwatt-db-athletes.php' );
        include_once( STWATT_ABSPATH . 'includes/class-stwatt-db-tokens.php' );
        include_once( STWATT_ABSPATH . 'includes/class-stwatt-install.php' );
        include_once( STWATT_ABSPATH . 'includes/functions.php' );
                
        // eventually rolled into api class
        $this->auth = new STWATT_Auth();
        
        // db
        $this->athletes_db = new STWATT_DB_Athletes();
        $this->tokens_db = new STWATT_DB_Tokens();
    }

    /**
     * Init hooks for plugin.
     *
     * @access private
     * @return void
     */
    private function init_hooks() {
        register_activation_hook( STWATT_PLUGIN_FILE, array( 'STWATT_Install', 'install' ) );
        
        add_action( 'init', array( $this, 'init' ), 0 );
        add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts_styles' ) );
    }
    
    public function init() {}
    
    public function frontend_scripts_styles() {}
    
    private function stwatt_store_athlete_data($data) {} 

}

/**
 * STWATT function.
 *
 * @access public
 * @return instance
 */
function stwatt() {
    return STWATT::instance();
}

// Global for backwards compatibility.
$GLOBALS['stwatt'] = stwatt();
