import React from 'react';
import { Card, ListGroup } from 'react-bootstrap';
import { settings } from 'plugin-data';

import { __, sprintf } from '../i18n';
import Code from './Code';

const {
	assets: { admin_url },
} = settings;

const LegacyWidgetInfoCard = () => {
	return (
		<Card className="mw-100 mt-0 p-0 text-center border-top-1 border-right-0 border-left-0 border-bottom-0">
			<Card.Header as="h6" className="text-center">
				{__('Widget Info')}
			</Card.Header>
			<ListGroup variant="flush">
				<ListGroup.Item
					className="text-justify"
					dangerouslySetInnerHTML={{
						__html: sprintf(
							/* translators: 1, 2 Menu names */
							__(
								'Goto %1$s and click/drag %2$s and place it where you want it to be.'
							),
							`<b>${__(
								'Appearance'
							)}</b> &gt; <a href="${admin_url}/widgets.php">${__(
								'Widgets'
							)}</a>`,
							`<b>${__('WP Telegram Legacy Widget')}</b>`
						),
					}}
				></ListGroup.Item>
				<ListGroup.Item className="text-justify">
					{__(
						'Alternately, you can use the below shortCode or the block available in block editor.'
					)}
				</ListGroup.Item>
				<ListGroup.Item className="font-weight-bold text-secondary">
					{__('Inside page or post content:')}
				</ListGroup.Item>
				<ListGroup.Item
					variant="light"
					className="text-monospace text-left"
				>
					<Code>
						{
							'[wptelegram-widget num_messages="5" widget_width="100" author_photo="always_hide"]'
						}
					</Code>
				</ListGroup.Item>
				<ListGroup.Item className="font-weight-bold text-secondary">
					{__('Inside the theme templates')}
				</ListGroup.Item>
				<ListGroup.Item
					variant="light"
					className="text-monospace text-left"
				>
					<Code>
						{
							"<?php\nif ( function_exists( 'wptelegram_widget' ) ) {\n    $args = array(\n        // 'num_messages'    => 5,\n        // 'widget_width'    => 100,\n        // 'author_photo'    => 'auto',\n    );\n\n    wptelegram_widget( $args );\n}\n?>"
						}
					</Code>
					<br />
					<span className="font-weight-bold text-secondary">
						{__('or')}
					</span>
					<br />
					<Code>
						{
							'<?php\necho do_shortCode( \'[wptelegram-widget num_messages="5" widget_width="100" author_photo="always_show"]\' );\n?>'
						}
					</Code>
				</ListGroup.Item>
			</ListGroup>
		</Card>
	);
};

export default LegacyWidgetInfoCard;
