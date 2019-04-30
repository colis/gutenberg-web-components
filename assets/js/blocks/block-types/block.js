/**
 * BLOCK: pwl-tooltip
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

const { __ } = wp.i18n; // Import __() from wp.i18n
const { InspectorControls } = wp.editor; // eslint-disable-line no-unused-vars
const {
	PanelBody, // eslint-disable-line no-unused-vars
	PanelRow, // eslint-disable-line no-unused-vars
	TextControl // eslint-disable-line no-unused-vars
} = wp.components;

const name = 'pwl/tooltip';

const settings = {
	title: __( 'Tooltip (Web Components)', 'gutenberg-web-components' ),
	description: __( 'Display a tooltip when you mouse over the tooltip container.', 'web-components-in-gutenberg' ),
	icon: 'shield',
	category: 'common',
	keywords: [ __( 'tooltip', 'gutenberg-web-components' ) ],
	supports: {
		align: true,
		html: false,
	},
	attributes: {
		text: {
			type: 'string',
			default: 'this is a test attribute'
		}
	},

	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	edit: props => {
		const { attributes: { text }, className, setAttributes } = props;

		return [
			<InspectorControls>
				<PanelBody title={ __( 'Tooltip text', 'gutenberg-web-components' ) }>
					<TextControl
						label={ __( 'Text Control', 'gutenberg-web-components' ) }
						help={ __( 'Text control help text', 'gutenberg-web-components' ) }
						value={ text }
						onChange={ text => setAttributes( { text } ) }
					/>
				</PanelBody>
			</InspectorControls>,
			<div className={ className }>
				<hub-tooltip text={ text }>Web Components</hub-tooltip>
			</div>
		];
	},

	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by Gutenberg into post_content.
	 *
	 * The "save" property must be specified and must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	save: props => {
		const { attributes: { text }, className } = props;

		return (
			<div className={ className }>
				<hub-tooltip text={ text }>Web Components</hub-tooltip>
			</div>
		);
	},
};

export default { name, settings };