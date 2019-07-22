//  Import CSS.
import './editor.scss';

/**
 * Internal dependencies
 */
import edit from './edit';
/**
 * WordPress dependencies
 */
const el = wp.element.createElement;
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType( 'wptelegram/widget-single-post', {
	title: __( 'Telegram Single Post' ),
	icon: 'format-aside',
	category: 'widgets',
	getEditWrapperProps: ( attributes ) => {
		const { alignment } = attributes;
		if ( [ 'left', 'center', 'right', 'wide', 'full' ].includes( alignment ) ) {
			return { 'data-align': alignment };
		}
	},
	attributes: {
		url: {
			type: 'string',
			default: '',
		},
		iframe_src: {
			type: 'string',
			default: '',
		},
		userpic: {
			type: 'boolean',
			default: true,
		},
		alignment: {
				type: 'string',
				default: 'center',
		},
	},
	
	edit: edit,
	
	save: ({ attributes }) => {
		const { alignment, iframe_src } = attributes;

		return (
			<div className={ 'wp-block-wptelegram-widget-single-post wptelegram-widget-message align' + alignment }>
				<iframe
					frameBorder="0"
					scrolling="no"
					src={ iframe_src }
				>
					Your Browser Does Not Support iframes!
				</iframe>
			</div>
		);
	},
} );
