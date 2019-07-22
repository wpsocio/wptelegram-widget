import { sendAjaxRequest } from './ajax';

const { wptelegram_widget: { api: { rest }, settings: { info } } } = window;

const ApiClient = function( bot_token ) {

  this.bot_token = bot_token;

  this.getSettings = ( api_method, api_params ) => {

    // if holding shift key while testing
    if ( info.event && info.event.shiftKey ) {
      // use browser, not server
      info.use = 'browser';
    }

    let data, url, settings = {};

    if ('browser' === info.use) {
      
      url = this.buildUrl( api_method );
      data = api_params;
      settings.crossDomain = true;
    } else {

      url = this.buildUrl();
      data = {
        bot_token   : this.bot_token,
        api_method  : api_method,
        api_params  : api_params
      };
    }
    settings.url = url;
    settings.data = JSON.stringify(data);
    return settings;
  };

  this.buildUrl = ( api_method ) => {

    if ( 'browser' === info.use ) {
      this.base_url = 'https://api.telegram.org';

      return `${this.base_url}/bot${this.bot_token}/${api_method}`;
    }
    return this.base_url = `${rest.url}/bot-api`;
  };

  this.sendRequest = ( api_method, api_params, ajaxOverrides = {} ) => {
    if ( ! this.bot_token ) {
      console.error( 'Bot token is empty' );
      return;
    }
    const settings = this.getSettings( api_method, api_params );
    Object.assign(settings, ajaxOverrides );
    const { complete } = settings;

    settings.complete = ( ...rest ) => {
      // reset the value
      info.use = 'server';
      info.event = {};

      complete && complete(...rest);
    };

    return sendAjaxRequest( settings );
  };
};

// dynamic method to make api calls
const botApi = new window.Proxy( new ApiClient(), {
  get : ( client, prop ) => {
    if ( 'undefined' === typeof client[prop] ) {
      return ( api_params, ajaxOverrides ) => {
        return client.sendRequest( prop, api_params, ajaxOverrides );
      };
    } else if ( 'function' !== typeof client[prop] ) {
      return client[prop];
    }
    return false;
  },
  set: ( client, prop, value ) => {
    // do not allow certain things to be changed
    if ( 'function' === typeof client[prop] || 'base_url' == prop )
      return false;
    client[prop] = value;
    return true;
  }
});

export default botApi;