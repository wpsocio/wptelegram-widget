/**
 * Internal dependencies
 */
import { __ } from '../i18n';
import botApi from './TelegramAPI';
import { getErrorResultFromXHR } from './ajax';
import { setEvent } from './FormUtils';

export const checkMemberCount = ( args ) => {
	const {
		bot_token,
		username,
		setInProgress,
		setResult,
		setResultType,
	} = args;

	setInProgress( true );

	const options = {
		error: ( jqXHR ) => {
			console.log( 'ERROR', jqXHR );

			setResultType( 'danger' );

			setResult( getErrorResultFromXHR( jqXHR ) );
		},
		success: ( { result } ) => {
			setResultType( 'success' );
			setResult( result );
		},
		complete: () => setInProgress( false ),
	};

	botApi.bot_token = bot_token;
	return botApi.getChatMembersCount( { chat_id: `@${username}` }, options );
};

export const sendTestMessage = ( args, event ) => {
	setEvent( event );

	const {
		bot_token,
		username,
		setInProgress,
		setResult,
		setResultType,
	} = args;

	const text = window.prompt( __( 'A message will be sent to the Channel/Group. You can modify the text below' ), __( 'This is a test message' ) );
	if ( ! text ) {
		return;
	}

	setInProgress( true );

	const options = {
		error: ( jqXHR ) => {
			console.log( 'ERROR', jqXHR );

			setResultType( 'danger' );

			setResult( getErrorResultFromXHR( jqXHR ) );
		},
		success: () => {
			setResultType( 'success' );
			setResult( __( 'Success' ) );
		},
		complete: () => setInProgress( false ),
	};

	botApi.bot_token = bot_token;
	return botApi.sendMessage( { chat_id: `@${username}`, text }, options );
};

export const testBotToken = ( args, event ) => {
	setEvent( event );

	const {
		bot_token,
		setInProgress,
		setResult,
		setResultType,
	} = args;

	setInProgress( true );

	const options = {
		error: ( jqXHR ) => {
			console.log( 'ERROR', jqXHR );

			setResultType( 'danger' );

			setResult( getErrorResultFromXHR( jqXHR ) );
		},
		success: ( { result } ) => {
			setResultType( 'success' );

			setResult( `${result.first_name} (${result.username})` );
		},
		complete: () => setInProgress( false ),
	};

	botApi.bot_token = bot_token;
	return botApi.getMe( {}, options );
};
