import React from 'react';

import { __ } from '../i18n';

const MemberCountResult = ({ result, type }) =>
	result ? (
		<div className="mt-2">
			<span className="text-secondary">{__('Members Count:')}</span>{' '}
			<span className={`font-weight-bold text-${type}`}>{result}</span>
		</div>
	) : null;

export default MemberCountResult;
