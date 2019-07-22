import React, { useState } from 'react';
import { __, sprintf } from '../i18n';
import { Form, Button, Tabs, Tab } from 'react-bootstrap';
import BotTestResult from './BotTestResult';
import MemberCountResult from './MemberCountResult';
import TestMessageResult from './TestMessageResult';
import SectionCard from './SectionCard';
import TabCard from './TabCard';
import FormField from './FormField';
import Instructions from './Instructions';
import AjaxWidgetInfoCard from './AjaxWidgetInfoCard';
import LegacyWidgetInfoCard from './LegacyWidgetInfoCard';
import { Form as ReactFinalForm } from 'react-final-form';
import { validate, getFieldLabel } from '../fields';
import { submitForm } from '../utils/FormUtils';
import { testBotToken, sendTestMessage, checkMemberCount } from '../utils/TelegramUtils';

export default ({setFormState}) => {

  const [testingBotToken, setTestingBotToken] = useState(false);
  // The result string
  const [botTokenTestResult, setBotTokenTestResult] = useState('');
  // e.g. "succes" or "danger"
  const [botTokenTestResultType, setBotTokenTestResultType] = useState('');

  const [sendingTestMessage, setSendingTestMessage] = useState(false);
  // The result string
  const [testMessageResult, setTestMessageResult] = useState('');
  // e.g. "succes" or "danger"
  const [testMessageResultType, setTestMessageResultType] = useState('');

  const [checkingMemberCount, setCheckingMemberCount] = useState(false);
  // The result string
  const [memberCountResult, setMemberCountResult] = useState('');
  // e.g. "succes" or "danger"
  const [memberCountResultType, setMemberCountResultType] = useState('');

  const { wptelegram_widget: { settings: {saved_opts: initialValues} } } = window;

  return (
    <ReactFinalForm
      initialValues={initialValues}
      onSubmit={submitForm}
      validate={validate}
      render={(props) => {
        const { handleSubmit, submitting, pristine, submitSucceeded, submitFailed, submitError, values, errors, form : { getState } } = props;
        setFormState && setFormState(getState());
        return (
          <Form onSubmit={handleSubmit}>
            <SectionCard title={__('Common Options')}>
              <FormField
                name="username"
                label={getFieldLabel('username')}
                controlProps={{
                  id: 'username',
                  type: 'text',
                  onBlur: () => values.bot_token && values.username && !checkingMemberCount && checkMemberCount({
                    bot_token: values.bot_token,
                    username: values.username,
                    setInProgress: setCheckingMemberCount,
                    setResult: setMemberCountResult,
                    setResultType: setMemberCountResultType,
                  }),
                }}
                after={<div>{values.bot_token && values.username && !checkingMemberCount ? <MemberCountResult result={memberCountResult} type={memberCountResultType}/> : null}{values.bot_token && values.username && !sendingTestMessage ? <TestMessageResult result={testMessageResult} type={testMessageResultType}/> : null}</div>}
                inputGroupProps={{
                  prepend: (InputGroup) => <InputGroup.Text>@</InputGroup.Text>,
                  append: () => (
                    values.bot_token && <Button
                      variant="outline-secondary"
                      size="sm"
                      disabled={!values.username || sendingTestMessage || errors.username}
                      onClick={(e) => sendTestMessage({
                        bot_token: values.bot_token,
                        username: values.username,
                        setInProgress: setSendingTestMessage,
                        setResult: setTestMessageResult,
                        setResultType: setTestMessageResultType
                      }, e)}
                    >
                      {sendingTestMessage ? __( 'Please wait...') : __('Send Test')}
                    </Button>
                  ),
                  style: {maxWidth:'300px'},
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
                  style: {maxWidth:'100px'},
                }}
              />
            </SectionCard>
            <div style={{borderWidth: '.2rem .2rem 0', border: '.2rem solid #ececec'}} className="mt-3 rounded-top p-3">
              <Tabs defaultActiveKey="ajax">
                <Tab eventKey="ajax" title={__('Ajax Widget')}>
                  <TabCard>
                    <FormField
                      name="widget_height"
                      label={getFieldLabel('widget_height')}
                      controlProps={{
                        placeholder: '600',
                        id: 'widget_height',
                        size: 'sm',
                        onBlur: () => null, // avoid validation on blur
                        style: {maxWidth:'100px'},
                      }}
                    />
                    <AjaxWidgetInfoCard />
                  </TabCard>
                </Tab>
                <Tab eventKey="legacy" title={__('Legacy Widget')}>
                  <TabCard>
                    <Instructions />
                    <FormField
                      name="bot_token"
                      label={getFieldLabel('bot_token')}
                      desc={__('Please read the instructions above')}
                      after={<BotTestResult result={botTokenTestResult} type={botTokenTestResultType}/>}
                      controlProps={{
                        id: 'bot_token',
                        type: 'text',
                      }}
                      inputGroupProps={{
                        append: () => (
                          <Button
                            variant="outline-secondary"
                            size="sm"
                            disabled={!values.bot_token || testingBotToken || errors.bot_token}
                            onClick={(e) => testBotToken({
                              bot_token: values.bot_token,
                              setInProgress: setTestingBotToken,
                              setResult: setBotTokenTestResult,
                              setResultType: setBotTokenTestResultType
                            }, e)}
                          >
                            {testingBotToken ? __( 'Please wait...') : __('Test Token')}
                          </Button>
                        ),
                        style: {maxWidth:'400px'}
                      }}
                    />
                    <FormField
                      name="author_photo"
                      label={getFieldLabel('author_photo')}
                      options={{
                        'auto': __( 'Auto' ),
                        'always_show': __( 'Always show' ),
                        'always_hide': __( 'Always hide' ),
                      }}
                      controlProps={{
                        id: 'author_photo',
                        type: 'select',
                        style: {width:'auto'},
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
                        style: {maxWidth:'100px'},
                      }}
                    />
                    <LegacyWidgetInfoCard />
                  </TabCard>
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

              {values.telegram_blocked === 'yes' && <FormField
                name="google_script_url"
                label={getFieldLabel('google_script_url')}
                desc={__( 'The requests to Telegram will be sent via your Google Script.' )}
                after={<small><a href="https://gist.github.com/manzoorwanijk/7b1786ad69826d1a7acf20b8be83c5aa#how-to-deploy" target="_blank">{__( 'See this tutorial' )}</a></small>}
                controlProps={{
                  id: 'google_script_url',
                  type: 'text',
                  onBlur: () => null, // avoid validation on blur
                  style: {maxWidth:'500px'},
                }}
              />}              
            </SectionCard>
            <div className="mt-2">
              <Button type="submit" disabled={submitting}>{__('Save Changes')}</Button>
              {submitFailed ? <span className="ml-2 text-danger">{submitError}</span> : null }
              {pristine && submitSucceeded ? <span className="ml-2 text-success">{__('Changes saved successfully.')}</span> : null }
            </div>
          </Form>
        );
      }}
    />
  );
}