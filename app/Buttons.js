const { Component } = wp.element;

class Buttons extends Component {
	constructor( props ) {
		super( props );
		
		this.buttonClick = this.buttonClick.bind( this );
	}

    buttonClick() {
console.log('btn click');        
    }

	render() {
		return (			
            <div className="buttons">
                <button onClick={ this.buttonClick }>Year</button>
                <button onClick={ this.buttonClick }>Month</button>
            </div>
		);
	}
}

export default Buttons;