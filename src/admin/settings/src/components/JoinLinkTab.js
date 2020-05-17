import React from 'react';
import { settings } from 'plugin-data';

import TabCard from './TabCard';
import FormField from './FormField';
import AjaxWidgetInfoCard from './AjaxWidgetInfoCard';
import { getFieldLabel } from '../fields';
import { __ } from '../i18n';

const username = settings?.saved_opts?.username || '';
const post_types = settings?.select_opts?.post_types || [];

const JoinLinkTab = () => {
	return (
		<TabCard>
			<span className="my-1 mx-3 d-block">
				{__('Join link can be automatically added to posts.')}
			</span>
			<FormField
				name="join_link_url"
				label={getFieldLabel('join_link_url')}
				defaultValue={username ? `https://t.me/${username}` : null}
				controlProps={{
					id: 'join_link_url',
					type: 'text',
					// onBlur: () => null, // avoid validation on blur
					style: { maxWidth: '450px' },
					placeholder: 'https://t.me/WPTelegram',
				}}
			/>
			<FormField
				name="join_link_text"
				label={getFieldLabel('join_link_text')}
				defaultValue={username ? `Join @${username} on Telegram` : null}
				controlProps={{
					id: 'join_link_text',
					type: 'text',
					style: { maxWidth: '300px' },
					placeholder: 'Join @WPTelegram on Telegram',
				}}
			/>
			<FormField
				name="join_link_post_types"
				label={getFieldLabel('join_link_post_types')}
				controlProps={{
					id: 'join_link_post_types',
					type: 'multicheck',
				}}
				options={post_types}
			/>
			<FormField
				name="join_link_position"
				label={getFieldLabel('join_link_position')}
				options={{
					before_content: __('Before content'),
					after_content: __('After content'),
				}}
				controlProps={{
					type: 'radio',
				}}
			/>
			<FormField
				name="join_link_priority"
				label={getFieldLabel('join_link_priority')}
				desc={__('Priority with respect to adjacent items.')}
				defaultValue={'10'}
				controlProps={{
					id: 'join_link_priority',
					type: 'number',
					placeholder: 10,
					min: 1,
					max: 1000,
					size: 'sm',
					onBlur: () => null, // avoid validation on blur
					style: { maxWidth: '100px' },
				}}
			/>
			<AjaxWidgetInfoCard />
		</TabCard>
	);
};

export default JoinLinkTab;
