import React, { useState, useEffect } from 'react';
import { Form, Button, Tabs, Tab } from 'react-bootstrap';

import { __ } from '../i18n';
import MemberCountResult from './MemberCountResult';
import TestMessageResult from './TestMessageResult';
import SectionCard from './SectionCard';
import FormField from './FormField';
import { getFieldLabel } from '../fields';
import { sendTestMessage, checkMemberCount } from '../utils/TelegramUtils';
import AjaxWidgetTab from './AjaxWidgetTab';
import LegacyWidgetTab from './LegacyWidgetTab';
import JoinLinkTab from './JoinLinkTab';

const FormRenderer = ({
	handleSubmit,
	submitting,
	pristine,
	submitSucceeded,
	submitFailed,
	submitError,
	values,
	errors,
	form: { getState },
	setFormState,
}) => {
	// The result string
	const [botTokenTestResult, setBotTokenTestResult] = useState('');

	const [sendingTestMessage, setSendingTestMessage] = useState(false);
	// The result string
	const [testMessageResult, setTestMessageResult] = useState('');
	// e.g. "success" or "danger"
	const [testMessageResultType, setTestMessageResultType] = useState('');

	const [checkingMemberCount, setCheckingMemberCount] = useState(false);
	// The result string
	const [memberCountResult, setMemberCountResult] = useState('');
	// e.g. "success" or "danger"
	const [memberCountResultType, setMemberCountResultType] = useState('');

	if ('could_not_connect' === botTokenTestResult) {
		setBotTokenTestResult(__('Could not connect'));
	}

	useEffect(() => {
		if (setFormState) {
			setFormState(getState());
		}
	}, [getState().values]);
	return (
		<Form onSubmit={handleSubmit}>
			<SectionCard title={__('Common Options')}>
				<FormField
					name="username"
					label={getFieldLabel('username')}
					controlProps={{
						id: 'username',
						type: 'text',
						onBlur: () =>
							values.bot_token &&
							values.username &&
							!errors.username &&
							!checkingMemberCount &&
							checkMemberCount({
								bot_token: values.bot_token,
								username: values.username,
								setInProgress: setCheckingMemberCount,
								setResult: setMemberCountResult,
								setResultType: setMemberCountResultType,
							}),
					}}
					after={
						<div>
							{values.bot_token &&
								values.username &&
								!checkingMemberCount && (
									<MemberCountResult
										result={memberCountResult}
										type={memberCountResultType}
									/>
								)}
							{values.bot_token &&
							values.username &&
							!sendingTestMessage ? (
								<TestMessageResult
									result={testMessageResult}
									type={testMessageResultType}
								/>
							) : null}
						</div>
					}
					inputGroupProps={{
						prepend: (InputGroup) => (
							<InputGroup.Text>@</InputGroup.Text>
						),
						append: () =>
							values.bot_token && (
								<Button
									variant="outline-secondary"
									size="sm"
									disabled={
										!values.username ||
										sendingTestMessage ||
										errors.username
									}
									onClick={(e) =>
										sendTestMessage(
											{
												bot_token: values.bot_token,
												username: values.username,
												setInProgress: setSendingTestMessage,
												setResult: setTestMessageResult,
												setResultType: setTestMessageResultType,
											},
											e
										)
									}
								>
									{sendingTestMessage
										? __('Please wait…')
										: __('Send Test')}
								</Button>
							),
						style: { maxWidth: '300px' },
					}}
				/>
				<FormField
					name="widget_width"
					label={getFieldLabel('widget_width')}
					controlProps={{
						placeholder: `300 ${__('or')} 100%`,
						id: 'widget_width',
						size: 'sm',
						onBlur: () => null, // avoid validation on blur
						style: { maxWidth: '100px' },
					}}
				/>
			</SectionCard>
			<div
				style={{
					borderWidth: '.2rem .2rem 0',
					border: '.2rem solid #ececec',
				}}
				className="mt-3 rounded-top p-3"
			>
				<Tabs defaultActiveKey="ajax">
					<Tab eventKey="ajax" title={__('Ajax Widget')}>
						<AjaxWidgetTab />
					</Tab>
					<Tab eventKey="legacy" title={__('Legacy Widget')}>
						<LegacyWidgetTab />
					</Tab>
					<Tab eventKey="join-link" title={__('Join Link')}>
						<JoinLinkTab />
					</Tab>
				</Tabs>
			</div>
			<SectionCard title={__('Google Script')}>
				<FormField
					name="telegram_blocked"
					label={getFieldLabel('telegram_blocked')}
					options={{
						yes: __('Yes'),
						no: __('No'),
					}}
					controlProps={{
						type: 'radio',
					}}
				/>

				{values.telegram_blocked === 'yes' && (
					<FormField
						name="google_script_url"
						label={getFieldLabel('google_script_url')}
						desc={__(
							'The requests to Telegram will be sent via your Google Script.'
						)}
						after={
							<small>
								<a
									href="https://gist.github.com/manzoorwanijk/7b1786ad69826d1a7acf20b8be83c5aa#how-to-deploy"
									target="_blank"
									rel="noopener noreferrer"
								>
									{__('See this tutorial')}
								</a>
							</small>
						}
						controlProps={{
							id: 'google_script_url',
							type: 'text',
							onBlur: () => null, // avoid validation on blur
							style: { maxWidth: '500px' },
						}}
					/>
				)}
			</SectionCard>
			<div className="mt-2">
				<Button type="submit" disabled={submitting}>
					{__('Save Changes')}
				</Button>
				{submitFailed ? (
					<span className="ml-2 text-danger">{submitError}</span>
				) : null}
				{pristine && submitSucceeded ? (
					<span className="ml-2 text-success">
						{__('Changes saved successfully.')}
					</span>
				) : null}
			</div>
		</Form>
	);
};

export default FormRenderer;
