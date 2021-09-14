const { Component } = wp.element;

class Loading extends Component {
	render() {
		return <div>{ this.props.loadingText }</div>;
	}
}

export default Loading;
