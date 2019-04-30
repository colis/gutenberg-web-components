import Tooltip from './tooltip.js';

const elements = [
	Tooltip
];

elements.forEach( element => {
	customElements.define( element.is, element );
} );
