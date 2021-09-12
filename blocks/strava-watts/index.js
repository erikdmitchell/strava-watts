import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import {
	RichText,
	MediaUpload,
	InspectorControls,
	ColorPalette,
} from '@wordpress/block-editor';
import { Button } from '@wordpress/components';
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
	attributes: {

	},
	edit: ( props ) => {
		const {
			className,
			attributes: { title, mediaID, mediaURL, bg_color },
			setAttributes,
		} = props;

		const onChangeTitle = ( value ) => {
			setAttributes( { title: value } );
		};

		const onSelectImage = ( media ) => {
			setAttributes( {
				mediaURL: media.url,
				mediaID: media.id,
			} );
		};

		const onChangeBGColor = ( hexColor ) => {
			setAttributes( { bg_color: hexColor } );
		};

		return (
			<>
				<InspectorControls key="setting">
					<div id="digiwatt-tagline-controls">
						<fieldset>
							<legend className="blocks-base-control__label">
								{ __( 'Background color', 'gutenpride' ) }
							</legend>
						</fieldset>						
					</div>
				</InspectorControls>

				<div
					className={ className }
					style={ { backgroundColor: bg_color } }
				>
				    <div className="tagline-wrapper">
						<MediaUpload
							onSelect={ onSelectImage }
							allowedTypes="image"
							value={ mediaID }
							render={ ( { open } ) => (
								<Button
									className={
										mediaID
											? 'image-button'
											: 'button button-large'
									}
									onClick={ open }
								>
									{ ! mediaID ? (
										__( 'Upload Image', 'dwb' )
									) : (
    									<div className="image-wrapper">
    										<img
    										    className="tagline-image"
    											src={ mediaURL }
    											alt={ __( 'tagline image', 'dwb' ) }
    										/>
										</div>
									) }
								</Button>
							) }
						/>
                        <div className="title-wrap">
        					<RichText
        						tagName="h1"
        						placeholder={ __( 'Tagline', 'dwb' ) }
        						value={ title }
        						onChange={ onChangeTitle }
        					/>
                        </div>
                    </div>
				</div>
			</>
		);
	},
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
