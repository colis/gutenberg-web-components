const template = document.createElement( 'template' );
template.innerHTML = `
	<style>
	:host {
		position: relative;
	}
	div {
		position: absolute;
		background: var(--tooltip-bg-colour, #f0f);
		color: var(--tooltip-text-colour, #fff);
		padding: 10px;
		z-index: 100;
	}
	</style>
	<slot>Default text from template</slot>
	<span> ?</span>
`;

class Tooltip extends HTMLElement {
	constructor() {
		super();

		this._tooltipVisible = false;
		this._icon;

		this.attachShadow( { mode: 'open' } );
		this.shadowRoot.appendChild( template.content.cloneNode( true ) );
	}

	static get is() {
		return 'hub-tooltip';
	}

	static get observedAttributes() {
		// return an array of attributes to observe
		return [ 'text' ];
	}

	connectedCallback() {
		if ( this.hasAttribute( 'text' ) ) {
			this._tooltipText = this.getAttribute( 'text' );
		}
		this._icon = this.shadowRoot.querySelector( 'span' );
		this._icon.addEventListener( 'mouseenter', this._showTooltip.bind( this ) );
		this._icon.addEventListener( 'mouseleave', this._hideTooltip.bind( this ) );
	}

	disconnectedCallback() {
		this._icon.removeEventListener( 'mouseenter', this._showTooltip );
		this._icon.removeEventListener( 'mouseleave', this._showTooltip );
	}

	attributeChangedCallback( name, oldValue, newValue ) {
		if ( oldValue === newValue ) {
			return;
		}

		if ( 'text' === name ) {
			this._tooltipText = newValue;
		}
	}

	_render() {
		let tooltipContainer = this.shadowRoot.querySelector( 'div' );
		if ( this._tooltipVisible ) {
			tooltipContainer = document.createElement( 'div' );
			tooltipContainer.textContent = this._tooltipText;
			this.shadowRoot.appendChild( tooltipContainer );
		} else {
			if ( tooltipContainer ) {
				this.shadowRoot.removeChild( tooltipContainer );
			}
		}
	}

	_showTooltip() {
		this._tooltipVisible = true;
		this._render();
	}

	_hideTooltip() {
		this._tooltipVisible = false;
		this._render();
	}
}

export default Tooltip;
