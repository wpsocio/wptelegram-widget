/**
 * External dependencies
 */
import React from 'react';
import { Card } from 'react-bootstrap';
/**
 * Internal dependencies
 */
import { __ } from '../i18n';
import SocialIcons from './SocialIcons';

export default () => {
	const { wptelegram_widget: { title, version, settings: { assets } } } = window;

	return (
		<Card border="info" className="mw-100 p-0">
			<Card.Header className="text-nowrap">
				<img
					src={ assets.logo_url }
					width="30"
					height="30"
					className="d-inline-block align-middle mr-2"
					alt={ title }
				/>
				<div className="d-inline-block">
					<h6 className="d-inline-block">{ title }</h6>
					{ ' ' }
					<small className="text-secondary font-italic">v{ version }</small>
				</div>
			</Card.Header>
			<Card.Body className="pb-1">
				<Card.Text className="text-secondary font-italic text-justify">
					{ __( 'With this plugin, you can display your public Telegram Channel or Group feed in a WordPress widget or anywhere else using a shortcode.' ) }
				</Card.Text>
			</Card.Body>
			<Card.Body className="pb-1">
				<SocialIcons />
			</Card.Body>
		</Card>
	);
};
