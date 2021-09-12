import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import {
	RichText,
	MediaUpload,
	InspectorControls,
	ColorPalette,
} from '@wordpress/block-editor';
import { Button } from '@wordpress/components';
import edit from './edit';

// Import the element creator function (React abstraction layer)
const el = wp.element.createElement;

/**
 * Example of a custom SVG path taken from fontastic
*/
const iconEl = el('svg', { width: 20, height: 20 },
    el('path', { d: "M158.4 0L7 292h89.2l62.2-116.1L220.1 292h88.5zm150.2 292l-43.9 88.2-44.6-88.2h-67.6l112.2 220 111.5-220z" } )
);

registerBlockType( 'stwatt/strava-watts', {
	title: __( 'Strava Watts', 'stwatt' ),
	icon: iconEl,
	category: 'text',
	attributes: {},
    edit,
	save: ( props ) => {
		const {
			className,
			attributes: { title, mediaURL, bg_color },
		} = props;
		return (
			<div
				className={ className }
				style={ { backgroundColor: bg_color } }
			>
				<div className="tagline-wrapper">
					{ mediaURL && (
						<div className="image-wrapper">
							<img
								className="tagline-image"
								src={ mediaURL }
								alt={ __( 'tagline image', 'dwb' ) }
							/>
						</div>
					) }
					<div className="title-wrap">
						<RichText.Content tagName="h1" value={ title } />
					</div>
				</div>
			</div>
		);
	},
} );
