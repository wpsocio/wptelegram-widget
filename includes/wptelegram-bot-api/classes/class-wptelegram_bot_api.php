<?php

/**
 * Class WPTelegram_Bot_API.
 *
 * 
 */
if ( ! class_exists( 'WPTelegram_Bot_API' ) ) :
class WPTelegram_Bot_API {

    /**
     * All the instances of the WPTelegram_Bot_API object
     *
     * @var WPTelegram_Bot_API
     */
    public static $instances;

    /**
     * @var string Telegram Bot API Access Token.
     */
    private $bot_token;

    /**
     * @var WPTelegram_Bot_API_Client The Telegram client
     */
    protected $client;

    /**
     * @since  1.0.0
     *
     * @var WPTelegram_Bot_API_Request The original request
     */
    protected $request;

    /**
     * @var WPTelegram_Bot_API_Response|null Stores the last request made to Telegram Bot API.
     */
    protected $last_response;

    /**
     * Instantiates a new WPTelegram_Bot_API object.
     *
     *
     * @param string    $bot_token   The Telegram Bot API Access Token.
     *
     */
    public function __construct( $bot_token = null ) {
        $this->bot_token = $bot_token;

        $this->client = new WPTelegram_Bot_API_Client();
    }

    /**
     * Creates/returns the single instance WPTelegram_Bot_API object for the specific plugin to avoid multiple instances for that plugin
     *
     * @since  1.0.0
     *
     * @param string    $id   The ID of the instance, usually the plugin slug
     * @param string    $bot_token   The Telegram Bot API Access Token.
     *
     * @return WPTelegram_Bot_API_Loader_100 Single instance object
     */
    public static function get_instance( $id = '', $bot_token = null ) {
        if ( ! isset( self::$instances[ $id ] ) ) {
            self::$instances[ $id ] = new self( $bot_token );
        }
        return self::$instances[ $id ];
    }

    /**
     * Magic Method to handle all API calls.
     *
     * @param $method
     * @param $args
     *
     * @return mixed|string
     */
    public function __call( $method, $args ) {
        if ( ! empty( $args ) ) {
            $args = $args[0];
        }
        return $this->sendRequest( $method, $args );
    }

    /**
     * Set the bot token for this request.
     *
     * @since  1.0.0
     *
     * @param string    $bot_token  The Telegram Bot API Access Token.
     *
     */
    public function set_bot_token( $bot_token ) {
        $this->bot_token = $bot_token;
    }

    /**
     * Returns Telegram Bot API Access Token.
     *
     * @return string
     */
    public function get_bot_token() {
        return $this->bot_token;
    }

    /**
     *
     * @return WPTelegram_Bot_API_Client
     */
    public function get_client() {
        return $this->client;
    }

    /**
     * Return the original request 
     *
     * @since   1.0.0
     *
     * @return WPTelegram_Bot_API_Request
     */
    public function get_request() {
        return $this->request;
    }

    /**
     * Returns the last response returned from API request.
     *
     * @return WPTelegram_Bot_API_Response
     */
    public function get_last_response() {
        return $this->last_response;
    }

    /**
     * Send Message
     *
     * @since  1.0.0
     */
    public function sendMessage( $params ) {
        
        if ( mb_strlen( $params['text'], 'UTF-8' ) > 4096 ) {
            // break text after every 4096th character and preserve words
            preg_match_all( '/.{1,4095}(?:\s|$)/su', $params['text'], $matches );
            foreach ( $matches[0] as $text ) {
                $params['text'] = $text;
                $res = $this->sendRequest( __FUNCTION__, $params );
                $params['reply_to_message_id'] = null;
            }
            return $res;
        }
        return $this->sendRequest( __FUNCTION__, $params );
    }

    /**
     * sendRequest
     *
     * @since  1.0.0
     */
    private function sendRequest( $api_method, $params ) {
        
        if ( null == $this->get_bot_token() ) {
            return new WP_Error( 'invalid_bot_token', __( 'Bot Token is required to make a request', 'wptelegram' ) );
        }

        $this->request = $this->request( $api_method, $params );;

        $log_enabled = (bool) apply_filters( 'wptelegram_bot_api_enable_log', false );
        if ( $log_enabled ) {
            // override the remote post blocking arg
            add_filter( 'wptelegram_bot_api_request_arg_blocking', '__return_true', 20 );
        }
        
        do_action( 'wptelegram_bot_api_before_request', $this->get_request() );

        $this->last_response = $this->get_client()->sendRequest( $this->get_request() );
        
        if ( $log_enabled ) {
            $this->api_log();
        }

        do_action( 'wptelegram_bot_api_debug', $this->last_response, $this );

        return $this->last_response;
    }

    /**
     * Check if the response is successful
     *
     * @return bool
     */
    public function is_success( $res = NULL ) {

        if ( empty( $res ) ) {
            $res = $this->last_response;
        }

        if ( ! is_wp_error( $res ) && $res instanceof WPTelegram_Bot_API_Response && 200 == $res->get_response_code() ) {
            return true;
        }
        return false;
    }

    /**
     * Instantiates a new WPTelegram_Bot_API_Request
     *
     * @param string $api_method
     * @param array  $params
     *
     * @return WPTelegram_Bot_API_Request
     */
    private function request( $api_method, array $params = array() ) {
        return new WPTelegram_Bot_API_Request(
            $this->get_bot_token(),
            $api_method,
            $params
        );
    }

    /**
     * Create a log of the API requests
     *
     * @since 1.0.0
     *
     */
    private function api_log() {
        $res = $this->get_last_response();
        // add the method and request params
        $text = 'Method: ' . $this->get_request()->get_api_method() . PHP_EOL . 'Params: ' . json_encode( $this->get_request()->get_params() ) . PHP_EOL . '--------------------------------' . PHP_EOL;

        // add the response
        if ( is_wp_error( $res ) ) {
            $text .= 'WP_Error: ' . $res->get_error_code() . ' ' . $res->get_error_message();
        } else{
            $text .= 'Response: ' . $res->get_body();
        }

        $filename = WP_CONTENT_DIR . '/wptelegram-bot-api.log';
        $filename = apply_filters( 'wptelegram_bot_api_log_filename', $filename );

        $data = PHP_EOL . '[' . current_time( 'mysql' ) . ']' . PHP_EOL . $text . PHP_EOL . PHP_EOL;

        file_put_contents( $filename, $data, FILE_APPEND );
    }
}
endif;