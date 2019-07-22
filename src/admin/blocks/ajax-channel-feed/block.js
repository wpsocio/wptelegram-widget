//  Import CSS.
import './editor.scss';

const el =  wp.element.createElement;
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.editor;
const {
	PanelBody,
	TextControl,
	Dashicon,
} = wp.components;

const getShortcodeFromAttrs = attributes => {
	const atts = ['widget_width','widget_height'].filter(att => attributes[att]).map(att => `${att}="${attributes[att]}"`);

		let text = '[wptelegram-ajax-widget';
		if (atts.length) {
			text += ' ' + atts.join(' ');
		}
		text += ']';

		return text;
}

registerBlockType( 'wptelegram/widget-ajax-channel-feed', {
	title: __( 'Telegram Channel Ajax Feed' ),
	icon: 'format-aside',
	category: 'widgets',
	attributes: {
		widget_width: {
			type: 'string',
			default: '100%',
		},
		widget_height: {
			type: 'string',
			default: '600',
		},
	},
	edit: ({ attributes, setAttributes, className }) => {

		const {
			widget_width,
			widget_height,
		} = attributes;

		const controls = [
			<InspectorControls>
				<PanelBody title={__( 'Widget Options' )}>
					<TextControl
						label={__( 'Widget Width' )}
						value={widget_width}
						onChange={newWidth => setAttributes({ widget_width: newWidth })}
					/>
					<TextControl
						label={__( 'Widget Height' )}
						value={widget_height}
						onChange={newHeight => setAttributes({ widget_height: newHeight })}
						type="number"
					/>
				</PanelBody>
			</InspectorControls>
		];

		const label = (
			<label>
			<Dashicon icon="shortcode" />
				<span>{ __( 'Telegram Channel Ajax Feed' ) }</span>
			</label>
		);

		const text = getShortcodeFromAttrs(attributes);

		return [
			controls,
			<div className={className}>
				{label}
				<code className="widget-shortcode">{text}</code>
			</div>
		];
	},
	save: ({ attributes }) => {
		
		const text = getShortcodeFromAttrs(attributes);

		return (
			<div>
				{text}
			</div>
		);
	}
} );
