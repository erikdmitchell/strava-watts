import ComputerData from './computerdata';
import Buttons from './buttons';
import apiFetch from '@wordpress/api-fetch';

const { Component } = wp.element;
import { Spinner } from '@wordpress/components';

const assetsURL = '/wp-content/plugins/strava-watts/assets/';

class App extends Component {
	constructor( props ) {
		super( props );
		this.state = {
			athleteData: [],
			loading: true,
			view: 'default',
			displayText: 'This Week',
		};
		
		this.getApiUrl = this.getApiUrl.bind( this );
	}

	componentDidMount() {
		this.runApiFetch();
	}
	
	runApiFetch() {
		wp.apiFetch( {
			path: this.getApiUrl(),
		} ).then( ( data ) => {
			this.setState( {
				athleteData: data,
				loading: false,
			} );
		} );
	}	
	
	getApiUrl() {
		const params = [];

		let apiURL = 'stwatt/v1/athlete';

console.log('getApiUrl()');
console.log('view: ' + this.state.view);
/*
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
*/

		return apiURL;
	}	



	render() {
		return (
			<div id="computer-wrapper" className="computer-wrapper">
			    <div id="computer" className="computer">
    				{ this.state.loading ? (
    					<Spinner />
    				) : (
    				    <ComputerData stats={ this.state.athleteData.stats } displayText = { this.state.displayText } />
    				) }
    				<Buttons view={this.state.view} />
                    <div className="powered-by">
        				<img
        					src={ assetsURL + 'images/pb-strava-horz-color.png' }
        					alt="powered by strava"
        				/>
        			</div>
    			</div>
            </div>
		);
	}
}

export default App;