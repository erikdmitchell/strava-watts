const { Component } = wp.element;
const { Spinner } = wp.components;
 
const assetsURL = '/wp-content/plugins/strava-watts/assets/';
const apiPath = 'stwatt/v1/athlete';

class BlockEdit extends Component {
	constructor(props) {
		super(props);
		this.state = {
			athleteData: [],
			loading: true
		}
	}

	componentDidMount() {
		this.runApiFetch();
	}
 
	runApiFetch() {
		wp.apiFetch({
			path: apiPath,
		}).then(data => {
			this.setState({
				athleteData: data,
				loading: false
			});
		});
	}
 
	render() {
  	
		return(
			<div>
				{this.state.loading ? (
					<Spinner />
				) : (
    				<div className= {this.props.className}>
    					<Computer stats={this.state.athleteData.stats} />
    				</div>
				)}
			</div>
		);
 
	}
}
export default BlockEdit;


class Computer extends Component {
	constructor( props ) {
		super( props );
	}

	render() {     	  	
		return (
            <div id="computer-wrapper" className="computer-wrapper">
                <div className="computer">
                    <div className="computer-row">
                        <div className="data align-center text-uppercase">2021 Stats</div>
                    </div>
                    <div className="computer-row">
                        <div className="data-wrap">
                            <div className="data-label">Time</div>
                            <div className="data">{this.props.stats.time}</div>
                        </div>
                    </div>
                    <div className="computer-row">
                        <div className="data-wrap">
                            <div className="data-label">Miles</div>
                            <div className="data">{this.props.stats.distance}</div>
                        </div>
                    </div> 
            
                    <div className="computer-row">
                        <div className="data-wrap">
                            <div className="data-label">Elev</div>
                            <div className="data">{this.props.stats.elevation}</div>
                        </div>
                    </div> 
                </div>
                <div className="powered-by">
                    <img 
                        src={assetsURL + 'images/pb-strava-horz-color.png'}
                        alt="powered by strava"
                    />
                </div>  
            </div>
		);
	}
}