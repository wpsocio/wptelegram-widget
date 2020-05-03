import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls } from '@wordpress/block-editor';
import { Dashicon, PanelBody, TextControl } from '@wordpress/components';
//  Import CSS.
import './editor.scss';

const getShortcodeFromAttrs = (attributes) => {
	const atts = ['widget_width', 'widget_height']
		.filter((att) => attributes[att])
		.map((att) => `${att}="${attributes[att]}"`);

	let text = '[wptelegram-ajax-widget';
	if (atts.length) {
		text += ' ' + atts.join(' ');
	}
	text += ']';

	return text;
};

const blockAttributes = {
	widget_width: {
		type: 'string',
		default: '100%',
	},
	widget_height: {
		type: 'string',
		default: '600',
	},
};

registerBlockType('wptelegram/widget-ajax-channel-feed', {
	title: __('Telegram Channel Ajax Feed'),
	icon: 'format-aside',
	category: 'wptelegram',
	attributes: blockAttributes,
	edit: ({ attributes, setAttributes, className }) => {
		const { widget_width, widget_height } = attributes;

		const controls = [
			<InspectorControls key="controls">
				<PanelBody title={__('Widget Options')}>
					<TextControl
						label={__('Widget Width')}
						value={widget_width}
						onChange={(newWidth) => {
							setAttributes({ widget_width: newWidth });
						}}
					/>
					<TextControl
						label={__('Widget Height')}
						value={widget_height}
						onChange={(newHeight) => {
							setAttributes({ widget_height: newHeight });
						}}
						type="number"
					/>
				</PanelBody>
			</InspectorControls>,
		];

		const label = (
			// eslint-disable-next-line jsx-a11y/label-has-for
			<label>
				<Dashicon icon="shortcode" />
				<span>{__('Telegram Channel Ajax Feed')}</span>
			</label>
		);

		const text = getShortcodeFromAttrs(attributes);

		return [
			controls,
			<div className={className} key="shortcode">
				{label}
				<code className="widget-shortcode">{text}</code>
			</div>,
		];
	},
	save: ({ attributes }) => {
		const text = getShortcodeFromAttrs(attributes);

		return <div>{text}</div>;
	},
});