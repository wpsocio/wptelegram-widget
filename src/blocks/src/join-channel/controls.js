import { __ } from '@wordpress/i18n';
import {
	BlockAlignmentToolbar,
	BlockControls,
	InspectorControls,
} from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { Fragment, useEffect } from '@wordpress/element';
import { blocks } from 'plugin-data';

const default_link = blocks?.join_link_url || '';
const default_text = blocks?.join_link_text || '';

const AllControls = ({ setAttributes, attributes }) => {
	const { alignment, link, text } = attributes;

	useEffect(() => {
		if (!link) {
			setAttributes({ link: default_link });
		}
		if (!text) {
			setAttributes({ text: default_text });
		}
	}, []);

	return (
		<Fragment>
			<InspectorControls key="controls">
				<PanelBody title={__('Button details')}>
					<TextControl
						label={__('Channel Link')}
						value={link || ''}
						onChange={(newValue) =>
							setAttributes({ link: newValue })
						}
						type="url"
					/>
					<TextControl
						label={__('Button text')}
						value={text || ''}
						onChange={(newValue) =>
							setAttributes({ text: newValue })
						}
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
