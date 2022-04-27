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
    $asset_file = include STWATT_ASSETS_PATH . 'build/blocks.asset.php';

    $block_slug = 'strava-watts';
    register_block_type(
        'stwatt/' . $block_slug,
        array(
            'attributes'    => array(
                'athleteId' => array(
                    'type'    => 'number',
                    'default' => 3,
                ),
            ),
            'editor_script' => "stwatt-{$block_slug}-block-script",
            'editor_style'  => "stwatt-{$block_slug}-block-editor",
            'style'         => "stwatt-{$block_slug}-block-style",
        )
    );

    wp_register_script(
        "stwatt-{$block_slug}-block-script",
        STWATT_ASSETS_URL . 'build/blocks.js',
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
