import React, { useState, useEffect } from 'react';
import { Button } from 'react-bootstrap';

import { __ } from '../i18n';
import BotTestResult from './BotTestResult';
import TabCard from './TabCard';
import FormField from './FormField';
import Instructions from './Instructions';
import LegacyWidgetInfoCard from './LegacyWidgetInfoCard';
import { getFieldLabel } from '../fields';
import { testBotToken } from '../utils/TelegramUtils';
import { useForm } from 'react-final-form';

const LegacyWidgetTab = () => {
	const [testingBotToken, setTestingBotToken] = useState(false);
	// The result string
	const [botTokenTestResult, setBotTokenTestResult] = useState('');
	// e.g. "succes" or "danger"
	const [botTokenTestResultType, setBotTokenTestResultType] = useState('');

	const { getState } = useForm();

	const { values, errors } = getState();

	useEffect(() => {
		if ('could_not_connect' === botTokenTestResult) {
			setBotTokenTestResult(__('Could not connect'));
		}
	}, [botTokenTestResult]);

	return (
		<TabCard>
			<Instructions />
			<FormField
				name="bot_token"
				label={getFieldLabel('bot_token')}
				desc={__('Please read the instructions above')}
				after={
					<BotTestResult
						result={botTokenTestResult}
						type={botTokenTestResultType}
					/>
				}
				controlProps={{
					id: 'bot_token',
					type: 'text',
				}}
				inputGroupProps={{
					append: () => (
						<Button
							variant="outline-secondary"
							size="sm"
							disabled={
								!values.bot_token ||
								testingBotToken ||
								errors.bot_token
							}
							onClick={(e) =>
								testBotToken(
									{
										bot_token: values.bot_token,
										setInProgress: setTestingBotToken,
										setResult: setBotTokenTestResult,
										setResultType: setBotTokenTestResultType,
									},
									e
								)
							}
						>
							{testingBotToken
								? __('Please wait…')
								: __('Test Token')}
						</Button>
					),
					style: { maxWidth: '400px' },
				}}
			/>
			<FormField
				name="author_photo"
				label={getFieldLabel('author_photo')}
				options={{
					auto: __('Auto'),
					always_show: __('Always show'),
					always_hide: __('Always hide'),
				}}
				controlProps={{
					id: 'author_photo',
					type: 'select',
					style: { width: 'auto' },
				}}
			/>
			<FormField
				name="num_messages"
				label={getFieldLabel('num_messages')}
				desc={__('Number of messages to display in the widget')}
				controlProps={{
					id: 'num_messages',
					type: 'number',
					placeholder: 5,
					min: 1,
					max: 50,
					size: 'sm',
					onBlur: () => null, // avoid validation on blur
					style: { maxWidth: '100px' },
				}}
			/>
			<LegacyWidgetInfoCard />
		</TabCard>
	);
};

export default LegacyWidgetTab;
