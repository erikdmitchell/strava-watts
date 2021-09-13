<?php
/**
 * WP CLI class
 *
 * @package stwatt
 * @since   0.1.0
 */


/**
 * STWATT_WP_CLI class.
 */
class STWATT_WP_CLI {

    /**
     * Run GraphQL query
     *
     * ## OPTIONS
     *
     * [--endpoint=<endpoint>]
     * : The API endpoint URL
     *
     * [--query=<query>]
     * : The query to run
     *
     * [--auth_code=<auth_code>]
     * : Encoded auth code string
     *
     * [--user_id=<user_id>]
     * : User id
     *
     * [--show=<show>]
     * : Show data (connectors)
     *
     * ## EXAMPLES
     *
     * wp bconn graphql
     * wp bconn graphql --query='{ listingEntries { listingEntryId name isFeatured shortDescription internalID type } }'
     * wp bconn graphql --query='{ listingEntryCategories (listingEntryId: ["listingEntryId1", "listingEntryId2"]) { clistingEntryId { categoryName categoryId categoryIconUrl } } }'
     * wp bconn graphql --query='{ listingCategories { id name iconUrl } }'
     * wp bconn graphql --endpoint=https://boomi.com/graphql --query='{ listingEntries { listingEntryId name isFeatured shortDescription internalID learnMoreUrl } }' --auth_code=FOGENSMO4sdT789NOiNt --user_id=sampleuser-1234
     */
    public function graphql( $args, $assoc_args ) {
        $assoc_args = array_merge(
            array(
                'endpoint' => boomi_connectors()->settings['graphql_url'],
                'query' => '{ listingCategories{ id name } }',
                'auth_code' => boomi_connectors_auth_code(),
                'user_id' => boomi_connectors()->settings['id'],
                'show' => true,
            ),
            $assoc_args
        );

        extract( $assoc_args );

        $data = boomi_connectors_graphql()->query( $endpoint, $query, $auth_code, $user_id );

        if ( ! isset( $data['data'] ) ) {
            WP_CLI::error( 'No data found.' );
        }

        $data = $data['data'];

        // sort through data and clean output.
        if ( $show ) {
            foreach ( $data as $key => $arr ) {
                $formatted_array = array();
                WP_CLI::log( $key . ' (' . count( $arr ) . ')' );
                $keys = array();

                foreach ( $arr as $values ) {
                    $formatted_array[] = $values;

                    if ( empty( $keys ) ) {
                        $keys = array_keys( $values );
                    }
                }
            }

            WP_CLI\Utils\format_items( 'table', $formatted_array, $keys );
        }
    }

    /**
     * Run main connectors import function
     *
     * ## EXAMPLES
     *
     * wp bconn import
     */
    public function import( $args, $assoc_args ) {
        $assoc_args = array_merge(
            array(),
            $assoc_args
        );

        extract( $assoc_args );

        WP_CLI::log( 'Importing...' );

        $data = boomi_connectors()->import->all();
        // print_r( $data ); // needs cli output.

        WP_CLI::success( 'Imported!' );
    }

}

/**
 * Register WP CLI class.
 * 
 * @access public
 * @return void
 */
function stwatt_register_commands() {
    WP_CLI::add_command( 'stwatt', 'STWATT_WP_CLI' );
}

add_action( 'cli_init', 'stwatt_register_commands' );
