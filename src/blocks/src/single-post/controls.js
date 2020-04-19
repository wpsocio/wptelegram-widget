const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { InspectorControls, BlockControls, BlockAlignmentToolbar } = wp.blockEditor;
const { PanelBody, Button, Toolbar, ToggleControl } = wp.components;

const AllControls = (props) => {
	const { userpic, toggleUserPic, showEditButton, switchBackToURLInput, alignment, changeAlignment } = props;

	return (
		<Fragment>
			<InspectorControls>
				<PanelBody title={__('Options')}>
					<ToggleControl label={__('Author Photo')} checked={userpic} onChange={toggleUserPic} />
				</PanelBody>
			</InspectorControls>
			<BlockControls>
				<BlockAlignmentToolbar value={alignment} onChange={changeAlignment} />
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
