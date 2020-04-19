import TelegramIcon from './TelegramIcon';

const { Button } = wp.components;

const JoinButton = ({ link, text, isEditing }) => {
	return (
		<Button
			isLarge
			href={link}
			className="join-link"
			icon={<TelegramIcon />}
			target={isEditing ? '_blank' : null}
			rel="noopener noreferrer"
		>
			{text}
		</Button>
	);
};

export default JoinButton;
