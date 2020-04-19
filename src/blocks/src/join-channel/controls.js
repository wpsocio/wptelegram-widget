import { blocks } from 'plugin-data';

const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { InspectorControls, BlockControls, BlockAlignmentToolbar } = wp.blockEditor;
const { PanelBody, TextControl } = wp.components;
const { useEffect } = wp.element;

const username = blocks?.username || 'WPTelegram';

const AllControls = ({ setAttributes, attributes }) => {
	const { alignment, link, text } = attributes;

	useEffect(() => {
		if (!link) {
			setAttributes({ link: `https://t.me/${username}` });
		}
		if (!text) {
			setAttributes({ text: `Join @${username} on Telegram` });
		}
	}, []);

	return (
		<Fragment>
			<InspectorControls key="controls">
				<PanelBody title={__('Button details')}>
					<TextControl
						label={__('Channel Link')}
						value={link || ''}
						onChange={(newValue) => setAttributes({ link: newValue })}
						type="url"
					/>
					<TextControl
						label={__('Button text')}
						value={text || ''}
						onChange={(newValue) => setAttributes({ text: newValue })}
					/>
				</PanelBody>
			</InspectorControls>
			<BlockControls>
				<BlockAlignmentToolbar
					value={alignment}
					onChange={(align) => {
						setAttributes({ alignment: align });
					}}
				/>
			</BlockControls>
		</Fragment>
	);
};

export default AllControls;
