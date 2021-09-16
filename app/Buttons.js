const { Component } = wp.element;

class Buttons extends Component {
	constructor( props ) {
		super( props );

		this.buttonClick = this.buttonClick.bind( this );		
	}

    buttonClick(event) {
        const id = event.target.id;

		this.props.changeScreen(id);        
    }
    
	render() {
		return (			
            <div className="buttons">
                <button id="prev" onClick={ this.buttonClick }>Prev</button>
                <button id="next" onClick={ this.buttonClick }>Next</button>
            </div>
		);
	}
}

export default Buttons;

