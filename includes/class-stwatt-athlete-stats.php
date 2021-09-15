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

    /**
     * Load stats from stats db.
     * 
     * @access public
     * @param int $athlete_id (default: 0)
     * @param array $fields (default: array())
     * @param bool $format (default: true)
     * @return void
     */
    public function stats( $athlete_id = 0, $fields = array(), $format = true ) {
        $default_fields = array( 'elevation', 'distance', 'time', 'distance_road', 'distance_cross', 'distance_mtb', 'distance_tt', 'distance_gravel' );
        $fields = wp_parse_args( $fields, $default_fields );
        $stats = stwatt()->athlete_stats_db->get_stats( $athlete_id, $fields );

        if ( $format ) {
            foreach ( $stats as $stat => $value ) {
                if ( 'time' == $stat ) {
                    $type = 'time';
                } elseif ('distance' == $stat) {
                    $type = 'distance_mi';
                } else {
                    $type = 'distance';
                }

                $stats->$stat = $this->format( $value, $type );
            }
        }

        return $stats;
    }

    /**
     * Format stat.
     *
     * @access protected
     * @param string $stat (default: '')
     * @param string $type (default: 'distance')
     * @return void
     */
    protected function format( $stat = '', $type = 'distance' ) {
        if ( empty( $stat ) ) {
            return;
        }

        switch ( $type ) {
            case 'distance':
                $formatted = round( $stat * 3.288 ); // meters to feet.
                break;
            case 'distance_mi':
                $formatted = round( $stat * 3.288 ); // meters to feet.
                $formatted = round( $formatted * 0.000189394); // feet to miles
                break;
            case 'time':
                $seconds = $stat;
                $hours = floor( $seconds / 3600 );
                $mins = floor( $seconds / 60 % 60 );
                $secs = floor( $seconds % 60 );

                $formatted = sprintf( '%02d:%02d:%02d', $hours, $mins, $secs );
                break;
            default:
                $formatted = 0;
        }

        return $formatted;
    }

}
