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
			viewDislpayText: this.getDisplayText('week'),
			views: {
    			'currentView': 'week',
    			'prevView': 'year',
    			'nextView': 'month',
			},
		};
		
		this.getApiUrl = this.getApiUrl.bind( this );
        this.getDisplayText = this.getDisplayText.bind( this );		
        this.changeScreen = this.changeScreen.bind( this );		
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
		const view = this.state.views.currentView;

		let apiURL = 'stwatt/v1/athlete';
		
        switch(view) {
            case 'year':
                // code block
                break;
            case 'month':
                // code block
                break;
            case 'week':
                this.setState( { 
                    displayText: 'This Week' 
                } );
                apiURL = apiURL;
        }		

//console.log('getApiUrl()');
//console.log('view: ' + view);

		return apiURL;
	}
	
	getDisplayText(currentView) {
		const viewText = {
			'week': 'This Week',
			'year': 'This Year',
			'month': 'This Month',
		};
		
        return viewText[currentView];
	}
	
	changeScreen(viewDirection) {
        // quick vars to help with moving things around.
        let prev = this.state.views.prevView
        let current = this.state.views.currentView;
        let next = this.state.views.nextView;
        
        if (viewDirection == 'prev') {
            this.setState({
    			views: {
        			'currentView': prev,
        			'prevView': next,
        			'nextView': current,
    			},
                viewDislpayText: this.getDisplayText(prev),                               
            });
        } else if (viewDirection == 'next') {
            this.setState({
    			views: {
        			'currentView': next,
        			'prevView': current,
        			'nextView': prev,
    			},
    			viewDislpayText: this.getDisplayText(next),                                
            });
        }
	}	
	
	render() {
		return (
			<div id="computer-wrapper" className="computer-wrapper">
			    <div id="computer" className="computer">
    				{ this.state.loading ? (
    					<Spinner />
    				) : (
    				    <ComputerData stats={ this.state.athleteData.stats } displayText = { this.state.viewDislpayText } />
    				) }
    				<Buttons changeScreen={ this.changeScreen } />
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