import apiFetch from '@wordpress/api-fetch';

const { Component } = wp.element;
const assetsURL = '/wp-content/plugins/strava-watts/assets/';
const apiPath = 'stwatt/v1/athlete';

class Computer extends Component {
	constructor( props ) {
		super( props );
	}

	render() {
		return (
			<div id="computer-wrapper" className="computer-wrapper">
				<div className="computer">
					<div className="computer-row">
						<div className="data align-center text-uppercase">
							2021 Stats
						</div>
					</div>
					<div className="computer-row">
						<div className="data-wrap">
							<div className="data-label">Time</div>
							<div className="data">
								{ this.props.stats.time }
							</div>
						</div>
					</div>
					<div className="computer-row">
						<div className="data-wrap">
							<div className="data-label">Miles</div>
							<div className="data">
								{ this.props.stats.distance }
							</div>
						</div>
					</div>

					<div className="computer-row">
						<div className="data-wrap">
							<div className="data-label">Elev</div>
							<div className="data">
								{ this.props.stats.elevation }
							</div>
						</div>
					</div>
				</div>
				<div className="powered-by">
					<img
						src={ assetsURL + 'images/pb-strava-horz-color.png' }
						alt="powered by strava"
					/>
				</div>
			</div>
		);
	}
}

export default Computer;