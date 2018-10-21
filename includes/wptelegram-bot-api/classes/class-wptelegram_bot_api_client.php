<?php

/**
 * Class WPTelegram_Bot_API_Client.
 *
 * 
 */
if ( ! class_exists( 'WPTelegram_Bot_API_Client' ) ) :
class WPTelegram_Bot_API_Client {
    /**
     * @const string Telegram Bot API URL.
     *
     * @since  1.0.0
     */
    const BASE_URL = 'https://api.telegram.org/bot';

    /**
     * Returns the base URL of the Bot API.
     *
     * @since  1.0.0
     *
     * @return string
     */
    public function get_base_url() {
        return self::BASE_URL;
    }

    /**
     * @var array The proxy configuration
     * array(
     *  'host'      => '',
     *  'port'      => '',
     *  'username'  => '',
     *  'password'  => '',
     * )
     */
    private $proxy;

    /**
     * Prepares the API request for sending to the client
     *
     * @since  1.0.0
     *
     * @param WPTelegram_Bot_API_Request $request
     *
     * @return array
     */
    public function prepare_request( $request ) {
        $url = $this->get_base_url() . $request->get_bot_token() . '/' . $request->get_api_method();

        return array(
            $url,
            $request->get_params(),
        );
    }

    /**
     * Set the bot token for this request.
     *
     * @since  1.0.0
     *
     * @param array    $proxy  The proxy options
     *
     */
    public function set_proxy( $proxy ) {
        $this->proxy = (array) $proxy;
    }

    /**
     * Returns The proxy options
     *
     * @return array
     */
    public function get_proxy() {
        return (array) apply_filters( 'wptelegram_bot_api_curl_proxy', $this->proxy );
    }

    /**
     * Send an API request and process the result.
     *
     * @since  1.0.0
     *
     * @param WPTelegram_Bot_API_Request $request
     *
     * @return WP_Error|WPTelegram_Bot_API_Response
     */
    public function sendRequest( $request ) {
        list( $url, $params ) = $this->prepare_request( $request );

        $args = array(
            'timeout'   => 20, //seconds
            'blocking'  => true,
            'headers'   => array( 'wptelegram_bot' => true ),
            'body'      => $params,
            'sslverify' => true,
        );

        foreach ( $args as $argument => $value ) {
            $args[ $argument ] = apply_filters( "wptelegram_bot_api_request_arg_{$argument}", $value, $request );
        }

        $url = apply_filters( 'wptelegram_bot_api_request_url', $url );

        $args = apply_filters( 'wptelegram_bot_api_remote_post_args', $args, $request );

        /* If curl handle should be modified*/
        if ( (bool) apply_filters( 'wptelegram_bot_api_modify_curl_handle', false ) ) {
            // modify curl
            add_action( 'http_api_curl', array( $this, 'modify_http_api_curl' ), 10, 3 );
        }

        // send the request
        $raw_response = wp_remote_post( $url, $args );

        if ( ! is_wp_error( $raw_response ) ) {
            return $this->get_response( $request, $raw_response );
        }

        return $raw_response;
    }

    /**
     * Modify cURL handle
     * The method is not used by default
     * but can be used to modify
     * the behavior of cURL requests
     *
     * @since 1.0.0
     *
     * @param resource $handle  The cURL handle (passed by reference).
     * @param array    $r       The HTTP request arguments.
     * @param string   $url     The request URL.
     *
     * @return string
     */
    public function modify_http_api_curl( &$handle, $r, $url ) {

        $to_telegram = ( 0 === strpos( $url, 'https://api.telegram.org/bot' ) );

        $by_wptelegram = ( isset( $r['headers']['wptelegram_bot'] ) && $r['headers']['wptelegram_bot'] );
        
        // if the request is sent to Telegram by WP Telegram
        if ( $to_telegram && $by_wptelegram ) {

            /**
             * Modify for SSL
             * NOT RECOMMENDED
             */
            if ( ! (bool) apply_filters( 'wptelegram_bot_api_request_arg_sslverify', true ) ) {
                curl_setopt( $handle, CURLOPT_SSL_VERIFYPEER, false );
                curl_setopt( $handle, CURLOPT_SSL_VERIFYHOST, false );
            }

            /**
             * If proxy enabled
             */
            if ( (bool) apply_filters( 'wptelegram_bot_api_use_proxy', false ) ) {

                $proxy_options = array(
                    'host'      => '',
                    'port'      => '',
                    'type'      => '',
                    'username'  => '',
                    'password'  => '',
                );

                $proxy_options = wp_parse_args( $this->get_proxy(), $proxy_options );

                foreach ( $proxy_options as $option => $value ) {
                    ${'proxy_' . $option} = apply_filters( "wptelegram_bot_api_curl_proxy_{$option}", $value );
                }

                if ( ! empty( $proxy_host ) && ! empty( $proxy_port ) ) {
                    
                    curl_setopt( $handle, CURLOPT_PROXYTYPE, constant( $proxy_type ) );
                    curl_setopt( $handle, CURLOPT_PROXY, $proxy_host );
                    curl_setopt( $handle, CURLOPT_PROXYPORT, $proxy_port );

                    if ( ! empty( $proxy_username ) && ! empty( $proxy_password ) ) {
                        $authentication = $proxy_username . ':' . $proxy_password;
                        curl_setopt( $handle, CURLOPT_PROXYAUTH, CURLAUTH_ANY );

                        curl_setopt( $handle, CURLOPT_PROXYUSERPWD, $authentication );
                    }
                }
            }
        }
    }

    /**
     * Creates response object.
     *
     * @since  1.0.0
     *
     * @param WPTelegram_Bot_API_Request   $request
     * @param array                         $raw_response
     *
     * @return WPTelegram_Bot_API_Response
     */
    protected function get_response( $request, $raw_response ) {
        return new WPTelegram_Bot_API_Response( $request, $raw_response );
    }
}
endif;