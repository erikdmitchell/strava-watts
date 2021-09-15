const { Component } = wp.element;

class Buttons extends Component {
	constructor( props ) {
		super( props );
		
		this.state = {
    		prevBtnType: 'year',
    		nextBtnType: 'month',
		}
console.log(props);		
		this.buttonClick = this.buttonClick.bind( this );
		this.btnNext = this.btnNext.bind( this );		
		this.btnPrev = this.btnPrev.bind( this );
	}

    buttonClick(event) {
        const id = event.target.id;

        if (id == 'prev') {
            this.btnPrev();            
        } else if (id == 'next') {
            this.btnNext();
        }
    }
    
    btnNext() {
        console.log('btnNext');
        console.log(this.state);        
    }
    
    btnPrev() {
        console.log('btnPrev');
        console.log(this.state);
    }
    
//     		this.props.updateConnectors( newFilter ); // updates parent component (CategoriesFilter)

	render() {
		return (			
            <div className="buttons">
                <button id="prev" onClick={ this.buttonClick }>Year</button>
                <button id="next" onClick={ this.buttonClick }>Month</button>
            </div>
		);
	}
}

export default Buttons;

/**
   when we click a button we need to feed the new button to the api
   then we need to adjust the buttons so the proper buttons display
   the default is: year | week |  month - i'm sure somehow we can just rotate the elements
**/

/*

		this.updateConnectors = this.updateConnectors.bind( this );
	}

	updateConnectors( categories ) {
		this.props.filter( categories ); // updates parent component (App)
	}

	render() {
		return (
			<div className="categories-filter">
				<DisplayCategories
					categories={ this.props.categories }
					updateConnectors={ this.updateConnectors }
					showCategories={ this.props.showCategories }
				/>
			</div>
		);
	}
}
*/


/* global fetch */
/*

import Connectors from './Connectors';
import CategoriesFilter from './CategoriesFilter';
import Loading from './Loading';
import Search from './Search';

const { Component } = wp.element;
const categoriesURL = '/wp-json/boomici/v1/categories'; // this needs to be dependant on our connectors URL param

class App extends Component {
	constructor( props ) {
		super( props );

		this.state = {
			categories: {},
			gridType: '',
			initialItems: {},
			items: {},
			loaded: false,
			showCategories: false,
		};

		this.filter = this.filter.bind( this );
		this.getConnectorsURL = this.getConnectorsURL.bind( this );
		this.search = this.search.bind( this );
		this.toggleCategories = this.toggleCategories.bind( this );
	}

	componentDidMount() {
		Promise.all( [
			fetch( this.getConnectorsURL() ).then( ( res ) => res.json() ),
			fetch( categoriesURL ).then( ( res ) => res.json() ),
		] ).then( ( [ connectorsData, categoriesData ] ) => {
			this.setState( {
				initialItems: connectorsData,
				items: connectorsData,
				categories: categoriesData,
				loaded: true,
			} );
		} );
	}

	getConnectorsURL() {
		const params = [];

		let connectorsURL = '/wp-json/boomici/v1/connectors';
		let queryString = '';

		// check for limit.
		if ( this.props.options.limit > 0 ) {
			params.limit = parseInt( this.props.options.limit );
		}

		// explode params into ?param=value
		queryString = Object.keys( params )
			.map( ( key ) => key + '=' + params[ key ] )
			.join( '&' );

		if ( queryString.length > 0 ) {
			queryString = '?' + queryString;
		}

		if ( this.props.options.showDcp ) {
			connectorsURL =
				'/wp-json/boomici/v1/connectors/dcp';
			this.setState( { gridType: 'dcp' } );
		} else if ( this.props.options.showDcpFeatured ) {
			connectorsURL =
				'/wp-json/boomici/v1/connectors/dcp/featured';
			this.setState( { gridType: 'dcp-featured' } );
		} else if ( this.props.options.showFeatured ) {
			connectorsURL =
				'/wp-json/boomici/v1/connectors/featured';
			this.setState( { gridType: 'featured' } );
		}

		connectorsURL = connectorsURL + queryString;

		return connectorsURL;
	}

	toggleCategories() {
		const currentState = this.state.showCategories;

		this.setState( { showCategories: ! currentState } );
	}

	filter( categories ) {
		const initialItems = this.state.initialItems;

		let filteredItems = [];

		initialItems.forEach( ( item ) => {
			if (
				categories.every( ( cat ) => item.categories.includes( cat ) )
			) {
				filteredItems.push( item );
			}
		} );

		if ( filteredItems.length === 0 ) {
			filteredItems = this.state.initialItems;
		}

		this.setState( { items: filteredItems } );
	}

	search( string ) {
		const initialItems = this.state.initialItems;
		const lowercasedSearchString = string.toLowerCase();

		let filteredItems = [];

		if ( ! lowercasedSearchString === '' ) {
			initialItems.forEach( ( item ) => {
				if (
					item.name.toLowerCase().includes( lowercasedSearchString )
				) {
					filteredItems.push( item );
				}
			} );
		}

		if ( filteredItems.length === 0 ) {
			filteredItems = this.state.initialItems;
		}

		this.setState( { items: filteredItems } );
	}

	render() {
		return (
			<>
				{ ! this.state.loaded ? (
					<Loading loadingText={ this.props.options.loadingText } />
				) : (
					<>
						<div className="connectors-filters">
							{ this.props.options.showSearch && (
								<Search
									search={ this.search }
									searchPlaceholderText={
										this.props.options.searchPlaceholderText
									}
								/>
							) }

							{ this.props.options.showFilter && (
								<>
									<button
										className={
											this.state.showCategories
												? 'categories-btn show'
												: 'categories-btn'
										}
										onClick={ this.toggleCategories }
									>
										Categories
									</button>

									<CategoriesFilter
										categories={ this.state.categories }
										filter={ this.filter }
										showCategories={
											this.state.showCategories
										}
									/>
								</>
							) }
						</div>
						<Connectors
							items={ this.state.items }
							columns={ this.props.options.columns }
							gridType={ this.state.gridType }
						/>
					</>
				) }
			</>
		);
	}
}

export default App;
*/
