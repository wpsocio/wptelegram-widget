//  Import CSS.
import './editor.scss';

import JoinButton from './JoinButton';
import AllControls from './controls';
import TelegramIcon from './TelegramIcon';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

// do not set defaults for link and text to avoid invalid block
const blockAttributes = {
	link: {
		type: 'string',
	},
	text: {
		type: 'string',
	},
	alignment: {
		type: 'string',
		default: 'center',
	},
};

registerBlockType('wptelegram/widget-join-channel', {
	title: __('Join Telegram Channel'),
	icon: <TelegramIcon fill="#555d66" />,
	category: 'widgets',
	attributes: blockAttributes,
	getEditWrapperProps: (attributes) => {
		const { alignment } = attributes;
		if (['left', 'center', 'right', 'wide', 'full'].includes(alignment)) {
			return { 'data-align': alignment };
		}
	},
	edit: ({ attributes, setAttributes, className }) => {
		return (
			<>
				<AllControls attributes={attributes} setAttributes={setAttributes} />
				<div className={className} key="preview">
					<JoinButton {...attributes} isEditing />
				</div>
			</>
		);
	},
	save: ({ attributes }) => {
		const { alignment } = attributes;

		return (
			<div className={'wp-block-wptelegram-widget-join-channel align' + alignment}>
				<JoinButton {...attributes} />
			</div>
		);
	},
});
