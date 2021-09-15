const { Component } = wp.element;

class Buttons extends Component {
	constructor( props ) {
		super( props );

		this.btnNext = this.btnNext.bind( this );		
		this.btnPrev = this.btnPrev.bind( this );			
	}
    
    btnNext() {
        console.log('btnNext');
    }
    
    btnPrev() {
        console.log('btnPrev');
    }   

	render() {
		return (			
            <div className="buttons">
                <button id="prev" onClick={ this.btnPrev }>Prev</button>
                <button id="next" onClick={ this.btnNext }>Next</button>
            </div>
		);
	}
}

export default Buttons;