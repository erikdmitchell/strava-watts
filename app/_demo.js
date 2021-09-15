const { Component } = wp.element;
const defaultIcon =
	'https://boomi.com/wp-content/plugins/boomi-cms/assets/images/connectivity-icon.png';

class Connector extends Component {
	constructor( props ) {
		super( props );

		this.addClasses = this.addClasses.bind( this );
		this.connectorID = this.connectorID.bind( this );
		this.connectorCategories = this.connectorCategories.bind( this );
		this.setupDescription = this.setupDescription.bind( this );
		this.displayDescription = this.displayDescription.bind( this );
		this.hideDescription = this.hideDescription.bind( this );
	}

	addClasses( slug, featured ) {
		// slug: lower, spaces to -
		slug = slug.replace( /\s+/g, '-' ).toLowerCase();

		const classes = [ 'connector', slug ];

		if ( featured ) {
			classes.push( 'featured' );
		}

		return classes.join( ' ' );
	}

	connectorID( id ) {
		return 'connector-' + id;
	}

	connectorCategories( categoriesArr ) {
		return categoriesArr.join( ' ' );
	}

	setupDescription( description, max = 180, suffix = '...' ) {
		// max will eventually be shortcode opt.
		let desc = description;

		if ( description === null || description.length < 1 ) {
			return desc;
		} else if ( description.length > max ) {
			desc =
				description.substr(
					0,
					description
						.substr( 0, max - suffix.length )
						.lastIndexOf( ' ' )
				) + suffix;
		}

		return desc;
	}

	displayDescription( event ) {
		const connectorId = event.currentTarget.id;
		const connectorDescription = document
			.getElementById( connectorId )
			.getElementsByClassName( 'connector-description' )[ 0 ];

		connectorDescription.style.display = 'block';
	}

	hideDescription( event ) {
		const connectorId = event.currentTarget.id;
		const connectorDescription = document
			.getElementById( connectorId )
			.getElementsByClassName( 'connector-description' )[ 0 ];

		connectorDescription.style.display = 'none';
	}

	render() {
		return (
			<div
				id={ this.connectorID( this.props.connector.id ) }
				className={ this.addClasses(
					this.props.connector.name,
					this.props.connector.is_featured
				) }
				data-listing-id={ this.props.connector.listing_id }
				data-categories={ this.connectorCategories(
					this.props.connector.categories
				) }
				onMouseEnter={ this.displayDescription }
				onMouseLeave={ this.hideDescription }
			>
				<div className="connector-icon-wrap">
					<img
						className="connector-icon"
						src={
							this.props.connector.icon_path
								? this.props.connector.icon_path
								: defaultIcon
						}
						alt={ this.props.connector.name }
					/>
				</div>
				<div className="connector-description">
					{ this.setupDescription(
						this.props.connector.short_description
					) }

					{ this.props.connector.learn_more_url &&
						this.props.connector.learn_more_url.length > 0 && (
							<div className="connector-link">
								<a
									href={ this.props.connector.learn_more_url }
									target="_blank"
									rel="noreferrer"
								>
									Learn More
								</a>
							</div>
						) }
				</div>
			</div>
		);
	}
}

export default Connector;
