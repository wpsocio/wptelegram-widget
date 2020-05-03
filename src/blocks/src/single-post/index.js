import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
//  Import CSS.
import './editor.scss';

import edit from './edit';

const blockAttributes = {
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
};

registerBlockType('wptelegram/widget-single-post', {
	title: __('Telegram Single Post'),
	icon: 'format-aside',
	category: 'wptelegram',
	getEditWrapperProps: (attributes) => {
		const { alignment } = attributes;
		if (['left', 'center', 'right', 'wide', 'full'].includes(alignment)) {
			return { 'data-align': alignment };
		}
	},
	attributes: blockAttributes,

	edit,

	save: ({ attributes }) => {
		const { alignment, iframe_src } = attributes;

		return (
			<div
				className={
					'wp-block-wptelegram-widget-single-post wptelegram-widget-message align' +
					alignment
				}
			>
				<iframe
					title="Telegram post"
					frameBorder="0"
					scrolling="no"
					src={iframe_src}
				>
					Your Browser Does Not Support iframes!
				</iframe>
			</div>
		);
	},
	deprecated: [
		{
			attributes: blockAttributes,
			save: ({ attributes }) => {
				const { alignment, iframe_src } = attributes;

				return (
					<div
						className={
							'wp-block-wptelegram-widget-single-post wptelegram-widget-message align' +
							alignment
						}
					>
						{
							// eslint-disable-next-line jsx-a11y/iframe-has-title
							<iframe
								frameBorder="0"
								scrolling="no"
								src={iframe_src}
							>
								Your Browser Does Not Support iframes!
							</iframe>
						}
					</div>
				);
			},
		},
	],
});
