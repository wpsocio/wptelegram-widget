const { __ } = wp.i18n;
const { Button, Placeholder } = wp.components;

const EmbedPlaceholder = (props) => {
	const { label, url, onSubmit, onChangeURL, error } = props;
	const style = error ? { border: '2px solid #f71717' } : {};
	return (
		<Placeholder icon="wordpress-alt" label={label} className="wp-block-embed-telegram">
			<form onSubmit={onSubmit}>
				<input
					style={style}
					type="url"
					value={url || ''}
					className="components-placeholder__input"
					aria-label={label}
					placeholder="https://t.me/WPTelegram/102"
					onChange={onChangeURL}
				/>
				<Button isLarge type="submit">
					{__('Embed')}
				</Button>
			</form>
		</Placeholder>
	);
};

export default EmbedPlaceholder;
