import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import {
	BlockAlignmentToolbar,
	BlockControls,
	InspectorControls,
} from '@wordpress/block-editor';
import {
	Button,
	PanelBody,
	ToggleControl,
	Toolbar,
} from '@wordpress/components';

const AllControls = (props) => {
	const {
		userpic,
		toggleUserPic,
		showEditButton,
		switchBackToURLInput,
		alignment,
		changeAlignment,
	} = props;

	return (
		<Fragment>
			<InspectorControls>
				<PanelBody title={__('Options')}>
					<ToggleControl
						label={__('Author Photo')}
						checked={userpic}
						onChange={toggleUserPic}
					/>
				</PanelBody>
			</InspectorControls>
			<BlockControls>
				<BlockAlignmentToolbar
					value={alignment}
					onChange={changeAlignment}
				/>
				<Toolbar>
					{showEditButton && (
						<Button
							className="components-toolbar__control"
							label={__('Edit URL')}
							icon="edit"
							onClick={switchBackToURLInput}
						/>
					)}
				</Toolbar>
			</BlockControls>
		</Fragment>
	);
};

export default AllControls;
