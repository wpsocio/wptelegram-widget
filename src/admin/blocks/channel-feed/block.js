//  Import CSS.
import './editor.scss';

const el =  wp.element.createElement;
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.editor;
const {
	PanelBody,
	RadioControl,
	TextControl,
	Dashicon,
} = wp.components;

registerBlockType( 'wptelegram/widget-channel-feed', {
	title: __( 'Telegram Channel Feed' ),
	icon: 'format-aside',
	category: 'widgets',
	attributes: {
		widget_width: {
			type: 'string',
			default: '100',
		},
		author_photo: {
			type: 'string',
			default: 'auto',
		},
		num_messages: {
			type: 'string',
			default: '5',
		},
	},
	edit: ({ attributes, setAttributes, className }) => {

		const {
			widget_width,
			author_photo,
			num_messages,
		} = attributes;

		const controls = [
			<InspectorControls>
				<PanelBody title={__( 'Widget Options' )}>
					<TextControl
						label={__( 'Widget Width' )}
						value={widget_width}
						onChange={newWidth => setAttributes({ widget_width: newWidth })}
						type="number"
						min="10"
						max="100"
					/>
					<RadioControl
						label={__( 'Author Photo' )}
						selected={author_photo}
						onChange={newStyle => setAttributes({ author_photo: newStyle })}
						options={[
							{ label: 'Auto', value: 'auto' },
							{ label: 'Always show', value: 'always_show' },
							{ label: 'Always hide', value: 'always_hide' }
						]}
					/>
					<TextControl
						label={__( 'Number of Messages' )}
						value={num_messages}
						onChange={newValue => setAttributes({ num_messages: newValue })}
						type="number"
						min="1"
						max="50"
					/>
				</PanelBody>
			</InspectorControls>
		];

		const label = (
			<label>
			<Dashicon icon="shortcode" />
				<span>{ __( 'Telegram Channel Feed' ) }</span>
			</label>
		);

		let text = '[wptelegram-widget';
		text += attributes.widget_width ? ` widget_width="${attributes.widget_width}"` : '';
		text += ` author_photo="${attributes.author_photo}" num_messages="${attributes.num_messages}"`;

		return [
			controls,
			<div className={className}>
				{label}
				<code className="widget-shortcode">{text}</code>
			</div>
		];
	},
	save: ({ attributes }) => {
		const {
			widget_width,
			author_photo,
			num_messages
		} = attributes;
		let text = `[wptelegram-widget author_photo="${author_photo}" num_messages="${num_messages}"`;
		text += widget_width ? ` widget_width="${widget_width}"]` : ']';

		return (
			<div>
				{text}
			</div>
		);
	}
} );
