import Computer from './computer';
import apiFetch from '@wordpress/api-fetch';

const { Component } = wp.element;
import { Spinner } from '@wordpress/components';

const apiPath = 'stwatt/v1/athlete';

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
			<div>
				{ this.state.loading ? (
					<Spinner />
				) : (
					<div className={ this.props.className }>
						<Computer stats={ this.state.athleteData.stats } />
					</div>
				) }
			</div>
		);
	}
}

export default App;