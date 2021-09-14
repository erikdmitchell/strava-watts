const { Component } = wp.element;


class ComputerData extends Component {
	constructor( props ) {
		super( props );
	}

	render() {
		return (			
			<div id="computer-data">
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
		);
	}
}

export default ComputerData;