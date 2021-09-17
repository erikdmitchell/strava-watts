import ComputerData from './computerdata';
import Buttons from './buttons';
import apiFetch from '@wordpress/api-fetch';

const { Component } = wp.element;
import { Spinner } from '@wordpress/components';

const assetsURL = '/wp-content/plugins/strava-watts/assets/';
const athleteId = 4334; // should not be hardcoded.

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
        this.getDates = this.getDates.bind( this );	
        this.formatDate = this.formatDate.bind( this );	
	}

	componentDidMount() {
		this.runApiFetch();
	}
	
	runApiFetch() {
		wp.apiFetch( {
			path: this.getApiUrl(),
		} ).then( ( data ) => {
console.log(data);    		
			this.setState( {
				athleteData: data,
				loading: false,
			} );
		} );
	}	
	
	getApiUrl() {
		const view = this.state.views.currentView;

		//let params = [];
		let apiURL = 'stwatt/v1/athlete/' + athleteId + '/summary/';
		
		const dates = this.getDates(view);
		
/*
        switch(view) {
            case 'year':
                apiURL = 'stwatt/v1/athlete/4334/activities/?date=' + dates.first + ',' + dates.last;
                break;
            case 'month':            
                apiURL = 'stwatt/v1/athlete/4334/activities/?date=' + dates.first + ',' + dates.last;      
                break;
            case 'week':
                apiURL = apiURL;
        }
*/

console.log('view: ' + view + ' url: ' + apiURL);

		return apiURL;
	}
	
	getDates(type) {
    	const currDate = new Date; // get current date.
        let monthNumber = (currDate.getMonth() + 1).toString();
        const yearNumber = currDate.getFullYear();
        const daysInMonth = new Date(yearNumber, monthNumber, 0).getDate();

        if (monthNumber.length < 2) {
            monthNumber = '0' + monthNumber;            
        }        
    	
    	let dates = [];
	
        switch(type) {
            case 'year':
                dates = {
                    'first': yearNumber + '-01-01',
                    'last': yearNumber + '-12-31',
                }                 
                break;
            case 'month':
                dates = {
                    'first': yearNumber + '-' + monthNumber + '-' + '01',
                    'last': yearNumber + '-' + monthNumber + '-' + daysInMonth,                   
                }               
                break;
            case 'week':
                let first = currDate.getDate() - currDate.getDay(); // First day is the day of the month - the day of the week.
                let last = first + 6; // last day is the first day + 6.
                
                first = new Date(currDate.setDate(first));
                last = new Date(currDate.setDate(last));
                
                dates = {
                    'first': this.formatDate(first),
                    'last': this.formatDate(last),
                }
        }

		return dates;    	
	}
	
	formatDate(date) {
        let d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();
    
        if (month.length < 2) 
            month = '0' + month;
        if (day.length < 2) 
            day = '0' + day;
    
        return [year, month, day].join('-');    	
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
            }, this.runApiFetch);
        } else if (viewDirection == 'next') {
            this.setState({
    			views: {
        			'currentView': next,
        			'prevView': current,
        			'nextView': prev,
    			},
    			viewDislpayText: this.getDisplayText(next),
            }, this.runApiFetch);         
        }
	}	
	
	render() {
		return (
			<div id="computer-wrapper" className="computer-wrapper">
			    <div id="computer" className="computer">
    				{ this.state.loading ? (
    					<Spinner />
    				) : (
    				    <ComputerData stats={ this.state.athleteData } displayText = { this.state.viewDislpayText } />
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