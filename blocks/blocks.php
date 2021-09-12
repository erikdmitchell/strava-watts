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
                'athleteId' => array(
        			'type' => 'number',
                    'default' => 3,
                ),                              
            ),
            'render_callback' => 'render_block_stwatt_strava_watts',
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

function render_block_stwatt_strava_watts($attributes) {
    global $post;
   
    $athlete = stwatt_athlete();
    $html = '';
    $wrapper_attributes = 'class="wp-block-stwatt-strava-watts"';
    
    $html .= '
        <div id="computer-wrapper" class="computer-wrapper">
            <div class="computer">
                <div class="computer-row">
                    <div class="data align-center text-uppercase">2021 Stats</div>
                </div>
                <div class="computer-row">
                    <div class="data-wrap">
                        <div class="data-label">Time</div>
                        <div class="data">'.stwatt_str_wrap($athlete->stats->time, '<span>', '</span>', false).'</div>
                    </div>
                </div>
                <div class="computer-row">
                    <div class="data-wrap">
                        <div class="data-label">Miles</div>
                        <div class="data">'.stwatt_str_wrap($athlete->stats->distance, '<span>', '</span>', false).'</div>
                    </div>
                </div> 
        
                <div class="computer-row">
                    <div class="data-wrap">
                        <div class="data-label">Elev</div>
                        <div class="data">'.stwatt_str_wrap($athlete->stats->elevation, '<span>', '</span>', false).'</div>
                    </div>
                </div> 
            </div>
            <div class="powered-by">
                <img src="'.STWATT_ASSETS_URL .'/images/pb-strava-horz-color.png" alt="powered by strava" />
            </div>  
        </div> 
    ';
    
	return sprintf(
		'<div %1$s>%2$s</div>',
		$wrapper_attributes,
		$html
	);     

}
add_action('init', 'render_block_stwatt_strava_watts');