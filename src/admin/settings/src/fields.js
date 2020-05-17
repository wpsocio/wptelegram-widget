import * as yup from 'yup';
import { FORM_ERROR, setIn } from 'final-form';

import { __, sprintf } from './i18n';

export const validate = async (values) => {
	try {
		await validationSchema.validate(values, { abortEarly: false });
	} catch (err) {
		return err?.inner?.reduce(
			(formError, innerError) =>
				setIn(formError, innerError.path, innerError.message),
			{}
		);
	}
};

const validationSchema = yup.object({
	username: yup
		.string()
		.matches(/^[a-z][a-z0-9_]{3,30}[a-z0-9]$/i, {
			message: () => getErrorMessage('username', 'invalid'),
			excludeEmptyString: true,
		})
		.required(() => getErrorMessage('username', 'required')),
	widget_width: yup.string().matches(/^[1-9]*?[0-9]*?%?$/, {
		message: () => getErrorMessage('widget_width', 'invalid'),
		excludeEmptyString: true,
	}),
	widget_height: yup.string().matches(/^[1-9]*?[0-9]*?$/, {
		message: () => getErrorMessage('widget_height', 'invalid'),
		excludeEmptyString: true,
	}),
	bot_token: yup.string().matches(/^\d{9,11}:[a-z0-9_-]{35}$/i, {
		message: () => getErrorMessage('bot_token', 'invalid'),
		excludeEmptyString: true,
	}),
	author_photo: yup.mixed().oneOf(['auto', 'always_show', 'always_hide']),
	num_messages: yup.string().matches(/^[1-5]?[0-9]?$/, {
		message: () => getErrorMessage('num_messages', 'invalid'),
		excludeEmptyString: true,
	}),
	telegram_blocked: yup.string(),
	google_script_url: yup.string().url(),
	join_link_url: yup
		.string()
		.url(() => getErrorMessage('join_link_url', 'invalid')),
	join_link_post_types: yup.array().of(yup.string()),
	join_link_position: yup.mixed().oneOf(['before_content', 'after_content']),
});

export const getErrorMessage = (fieldName, errorType = 'invalid') => {
	let message;

	switch (errorType) {
		case 'invalid':
			/* translators: %s is field name */
			message = __('Invalid %s');
			break;
		case 'required':
			/* translators: %s is field name */
			message = __('%s is required.');
			break;

		default:
			return { [FORM_ERROR]: __('Changes could not be saved.') };
	}

	return sprintf(message, getFieldLabel(fieldName));
};

const fieldLabels = {
	username: () => __('Channel Username'),
	widget_width: () => __('Widget Width'),
	widget_height: () => __('Widget Height'),
	bot_token: () => __('Bot Token'),
	author_photo: () => __('Author Photo'),
	num_messages: () => __('Number of Messages'),
	telegram_blocked: () => __('Your host blocks Telegram?'),
	google_script_url: () => __('Google Script URL'),
	join_link_url: () => __('Channel Link'),
	join_link_text: () => __('Button text'),
	join_link_post_types: () => __('Add to post types'),
	join_link_position: () => __('Position'),
	join_link_priority: () => __('Priority'),
};

export const getFieldLabel = (name) => fieldLabels[name]();

export const formatValue = (val, name) => {
	switch (name) {
		case 'join_link_text':
			return shallowCleanUp(val);
		case 'bot_username':
			return sanitizeKey(val);
		default:
			return deepCleanUp(val);
	}
};

export const sanitizeKey = (val) => {
	if ('string' === typeof val) {
		return val.replace(/[^a-z0-9_]/gi, '');
	}
	return val;
};

export const shallowCleanUp = (val) => {
	if ('string' === typeof val) {
		return val.replace(/[\n\t\r]/g, '');
	}
	return val;
};

export const deepCleanUp = (val) => {
	if ('string' === typeof val) {
		return shallowCleanUp(val).replace(/\s/g, '').trim();
	}
	return val;
};
