import React from 'react';
import { Card, ListGroup } from 'react-bootstrap';
import { title } from 'plugin-data';

import { __, sprintf } from '../i18n';
import Donate from './Donate';

const PluginInfoCard = () => {
	return (
		<Card border="info" className="mw-100 p-0 text-center">
			<Card.Header as="h6" className="text-center">
				{title}
			</Card.Header>
			<Card.Body>
				<Card.Text className="text-justify">
					{__(
						'Display the Telegram Public Channel or Group Feed in a WordPress widget or anywhere you want using a shortcode.'
					)}
				</Card.Text>
			</Card.Body>
			<ListGroup variant="flush">
				<ListGroup.Item>
					<div>
						<span>
							{sprintf(
								/* translators: %s Plugin name */ __(
									'Do you like %s?'
								),
								title
							)}
						</span>
					</div>
					<div>
						<a
							href="https://wordpress.org/support/plugin/wptelegram-widget/reviews/#new-post"
							rel="noopener noreferrer"
							target="_blank"
							className="text-center text-info ml-1"
							style={{ textDecoration: 'none' }}
						>
							<span
								style={{ color: 'orange', fontSize: '1.5rem' }}
							>
								★★★★★
							</span>
						</a>
					</div>
				</ListGroup.Item>
				<ListGroup.Item>
					<Donate />
				</ListGroup.Item>
				<ListGroup.Item>
					<div>
						<span>{__('Need help?')}</span>
					</div>
					<div>
						<span style={{ fontWeight: '600' }}>
							{__('Get LIVE support on Telegram')}
						</span>
					</div>
				</ListGroup.Item>
				<ListGroup.Item
					action
					href="https://t.me/WPTelegramChat"
					target="_blank"
					variant="primary"
				>
					<span className="font-weight-bold font-italic">
						@WPTelegramChat
					</span>
				</ListGroup.Item>
			</ListGroup>
			<Card.Footer>
				<span role="img" aria-label="Smile">
					🙂
				</span>
			</Card.Footer>
		</Card>
	);
};

export default PluginInfoCard;
