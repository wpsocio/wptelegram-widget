import React from 'react';

import TabCard from './TabCard';
import FormField from './FormField';
import AjaxWidgetInfoCard from './AjaxWidgetInfoCard';
import { getFieldLabel } from '../fields';

const AjaxWidgetTab = () => {
	return (
		<TabCard>
			<FormField
				name="widget_height"
				label={getFieldLabel('widget_height')}
				controlProps={{
					placeholder: '600',
					id: 'widget_height',
					size: 'sm',
					onBlur: () => null, // avoid validation on blur
					style: { maxWidth: '100px' },
				}}
			/>
			<AjaxWidgetInfoCard />
		</TabCard>
	);
};

export default AjaxWidgetTab;
