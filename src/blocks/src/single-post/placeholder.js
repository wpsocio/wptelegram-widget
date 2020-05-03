import { __ } from '@wordpress/i18n';
import { Button, Placeholder } from '@wordpress/components';

const EmbedPlaceholder = ({ error, label, onChangeURL, onSubmit, url }) => {
	const style = error ? { border: '2px solid #f71717' } : {};
	return (
		<Placeholder
			icon="wordpress-alt"
			label={label}
			className="wp-block-embed-telegram"
		>
			<form onSubmit={onSubmit}>
				<input
					aria-label={label}
					className="components-placeholder__input"
					onChange={onChangeURL}
					placeholder="https://t.me/WPTelegram/102"
					style={style}
					type="url"
					value={url || ''}
				/>
				<Button isLarge type="submit">
					{__('Embed')}
				</Button>
			</form>
		</Placeholder>
	);
};

export default EmbedPlaceholder;
