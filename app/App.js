import ComputerData from './computerdata';
import apiFetch from '@wordpress/api-fetch';

const { Component } = wp.element;
import { Spinner } from '@wordpress/components';

const apiPath = 'stwatt/v1/athlete';
const assetsURL = '/wp-content/plugins/strava-watts/assets/';

class App extends Component {
	constructor( props ) {
		super( props );
		this.state = {
			athleteData: [],
			loading: true,
		};
	}

	componentDidMount() {
		this.runApiFetch();
	}

	runApiFetch() {
		wp.apiFetch( {
			path: apiPath,
		} ).then( ( data ) => {
			this.setState( {
				athleteData: data,
				loading: false,
			} );
		} );
	}

	render() {
		return (
			<div id="computer-wrapper" className="computer-wrapper">
			    <div id="computer" className="computer">
    				{ this.state.loading ? (
    					<Spinner />
    				) : (
    				    <ComputerData stats={ this.state.athleteData.stats } />
    				) }
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