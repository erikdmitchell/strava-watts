<?php

/**
 * Register blocks.
 * 
 * @access public
 * @return void
 */
function stwatt_register_blocks() {
    // Fail if block editor is not supported
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	    
    // automatically load dependencies and version
    $asset_file = include( STWATT_ABSPATH . 'build/index.asset.php');
    
    $block_slug = 'strava-watts';
    register_block_type(
        'stwatt/'.$block_slug,
        array(
            'attributes' => array(
                'postsToShow' => array(
        			'type' => 'number',
                    'default' => 3,
                ),
                'excerptLength' => array(
        			'type' => 'number',
                    'default' => 35,
                ),
                'columns' => array(
        			'type' => 'number',
                    'default' => 2,
                ),
                'order' => array(
                    'type' => 'string',
                    'default' => 'desc',
                ),
                'orderBy' => array(
                    'type' => 'string',
                    'default' => 'date',
                ), 
        		'featuredImageSizeSlug' => array(
        			'type' => 'string',
        			'default' => 'home-grid',
        		),
        		'featuredImageSizeWidth' => array(
        			'type' => 'number',
        			'default' => null
        		),
        		'featuredImageSizeHeight' => array(
        			'type' => 'number',
        			'default' => null
        		),                              
            ),
//             'render_callback' => 'render_block_stwatt_strava_watts',
            'editor_script' => "stwatt-{$block_slug}-block-script",
            'editor_style' => "stwatt-{$block_slug}-block-editor",
            'style' => "stwatt-{$block_slug}-block-style",
        )
    );
    
    wp_register_script(
        "stwatt-{$block_slug}-block-script",
        STWATT_ABSURL . 'build/index.js',
        $asset_file['dependencies'],
        $asset_file['version']
    );     

    $filename = 'style';
    wp_register_style(
        "stwatt-{$block_slug}-block-{$filename}",
        STWATT_ABSURL . "blocks/{$block_slug}/{$filename}.css",
        array(),
        filemtime( STWATT_ABSPATH . "blocks/{$block_slug}/{$filename}.css" )
    );    

    $filename = 'editor';
    wp_register_style(
        "stwatt-{$block_slug}-block-{$filename}",
        STWATT_ABSURL . "blocks/{$block_slug}/{$filename}.css",
        array(),
        filemtime( STWATT_ABSPATH . "blocks/{$block_slug}/{$filename}.css" )
    );
}
add_action( 'init', 'stwatt_register_blocks' );