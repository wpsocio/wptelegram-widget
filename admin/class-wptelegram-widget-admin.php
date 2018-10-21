<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://t.me/manzoorwanijk
 * @since      1.0.0
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two hooks to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/admin
 * @author     Manzoor Wani 
 */
class WPTelegram_Widget_Admin {

	/**
	 * Title of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $title    Title of the plugin
	 */
	protected $title;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The suffix to be used for JS and CSS files
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $suffix    The suffix to be used for JS and CSS files
	 */
	private $suffix;
	
	/**
	 * Messages WP_List_Table object
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public $list_table;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since	1.0.0
	 * @param 	string    $title		Title of the plugin
	 * @param	string    $plugin_name	The name of the plugin.
	 * @param	string    $version		The version of this plugin.
	 */
	public function __construct( $title, $plugin_name, $version ) {

		$this->title = $title;
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Use minified libraries if SCRIPT_DEBUG is turned off
		$this->suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style(
			$this->plugin_name,
			WPTELEGRAM_WIDGET_URL . '/admin/css/wptelegram-widget-admin' . $this->suffix . '.css',
			array(),
			$this->version,
			'all'
		);
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script(
			$this->plugin_name,
			WPTELEGRAM_WIDGET_URL . '/admin/js/wptelegram-widget-admin' . $this->suffix . '.js',
			array( 'jquery' ),
			$this->version,
			false
		);

		// script localization
		$translation_array = array(
			'could_not_connect'		=> __( 'Could not connect', 'wptelegram-widget' ),
			'empty_bot_token'		=> __( 'Bot Token is empty', 'wptelegram-widget' ),
			'empty_username'		=> __( 'Username is empty', 'wptelegram-widget' ),
			'error'					=> __( 'Error:', 'wptelegram-widget' ),
			'success'				=> __( 'Success', 'wptelegram-widget' ),
			'failure'				=> __( 'Failure', 'wptelegram-widget' ),
			'test_message_prompt'	=> __( 'A message will be sent to the Channel/Group. You can modify the text below', 'wptelegram-widget' ),
			'test_message_text'		=> __( 'This is a test message', 'wptelegram-widget' ),
		);
		wp_localize_script(
			$this->plugin_name,
			'wptelegram_widget_I18n',
			$translation_array
		);
	}

	/**
	 * Register required plugins
	 * 
	 */
	public function register_required_plugins() {
		$plugins = array(
			array(
				'name'               => 'CMB2',
				'slug'               => 'cmb2',
				'required'           => true,
				'version'            => '2.3.0',
			),
		);

		$notice_singular = $notice_plural = sprintf( '%s requires the following to be installed and active:', $this->title ) . ' %1$s';
		$notice = _n_noop( $notice_singular, $notice_singular, 'wptelegram-widget' );

		$config = array(
			'id'           => 'wptelegram',
			'domain'       => 'wptelegram-widget',
			'default_path' => '',
			'menu'         => 'install-required-plugins',
			'parent_slug'  => 'wptelegram',
			'capability'   => 'manage_options',
			'has_notices'  => true,
			'dismissable'  => false,
	        'is_automatic' => true,
			'strings'      => array(
				'notice_can_install_required'	=> $notice,
				'notice_can_activate_required'	=> $notice,
				'nag_type'						=> 'notice-error',
			)
		);

		tgmpa( $plugins, $config );
	}

	/**
	 * Build Options page
	 *
	 * @since    1.0.0
	 */
	public function create_options_page() {
		
		$box = array(
			'id'			=> $this->plugin_name,
			'title'			=> esc_html__( $this->title ),
			'object_types'	=> array( 'options-page' ),
			'option_key'	=> $this->plugin_name,
			'icon_url'		=> WPTELEGRAM_WIDGET_URL . '/admin/icons/icon-16x16-white.svg',
			'capability'	=> 'manage_options',
			'message_cb'	=> array( $this, 'custom_settings_messages' ),
		);

		if ( defined( 'WPTELEGRAM_LOADED' ) && WPTELEGRAM_LOADED ) {
			$box['menu_title'] = esc_html__( 'Telegram Widget', 'wptelegram-widget' );
			$box['parent_slug'] = 'wptelegram';
		}
		$cmb2 = new_cmb2_box( $box );

		$fields = array(
			array(
				'name' => __( 'Telegram Options', 'wptelegram-widget' ),
				'type' => 'title',
				'id'   => 'tg_guide_title',
				'before_row' => array( $this, 'render_header' ),
				'after' => array( __CLASS__, 'get_telegram_guide' ),
			),
			array(
				'name'       => __( 'Bot Token', 'wptelegram-widget' ),
				'desc'       => self::get_button_html( 'bot_token' ) . '<br>' . __( 'Please read the instructions above', 'wptelegram-widget' ),
				'id'         => 'bot_token',
				'type'       => 'text_medium',
				'sanitization_cb'	=> array( $this, 'sanitize_values' ),
				'after_field' => array( __CLASS__, 'render_after_field' ),
				'attributes'  => array(
					'required'	=> 'required',
				),
			),
			array(
				'name'			=> __( 'Username', 'wptelegram-widget' ),
				'desc'			=> self::get_button_html( 'username' ) . '<br>' . sprintf( __( 'Telegram Channel or Group username (without %s)', 'wptelegram-widget' ), '<code>@</code>' ) ,
				'id'			=> 'username',
				'type'			=> 'text_medium',
				'sanitization_cb'	=> array( $this, 'sanitize_values' ),
				'before_field'	=> '<code>@</code>',
				'after_field'	=> array( __CLASS__, 'render_after_field' ),
				'after_row'	=> array( __CLASS__, 'render_messages_button' ),
				'attributes'	=> array(
					'required'	=> 'required',
				),
			),
			array(
				'name' => __( 'Widget Options', 'wptelegram-widget' ),
				'type' => 'title',
				'id'   => 'tg_options_widget',
			),
			array(
				'name'			=> __( 'Widget Width', 'wptelegram-widget' ),
				'id'			=> 'widget_width',
				'type'			=> 'text_small',
				'after_field'	=> '<code>%</code>',
				'attributes'	=> array(
					'placeholder' => '100',
					'pattern'	=> '[0-9]{1,2}',
					'maxlength'	=> 2,
				),
			),
			array(
				'name'			=> __( 'Author Photo', 'wptelegram-widget' ),
				'id'			=> 'author_photo',
				'type'			=> 'select',
				'default'		=> 'auto',
				'options'		=> array(
					'auto'			=> __( 'Auto', 'wptelegram-widget' ),
					'always_show'	=> __( 'Always show', 'wptelegram-widget' ),
					'always_hide'	=> __( 'Always hide', 'wptelegram-widget' ),
				),
			),
			array(
				'name'			=> __( 'Number of Messages', 'wptelegram-widget' ),
				'desc'       => __( 'Number of messages to display in the widget', 'wptelegram-widget' ),
				'id'			=> 'num_messages',
				'type'			=> 'text_small',
				'default'		=> 5,
				'attributes'	=> array(
					'type'			=> 'number',
					'placeholder'	=> '5',
					'pattern'		=> '[0-9]{1,2}',
					'maxlength'		=> 2,
				),
			),
			array(
				'name' => __( 'Widget Info', 'wptelegram-widget' ),
				'type' => 'title',
				'id'   => 'tg_shortcode_title',
				'after_row' => array( $this, 'render_shortcode_guide' ),
			),
		);
		foreach ( $fields as $field ) {
			$cmb2->add_field( $field );
		}

		// Messages List page
		$box = array(
			'id'			=> 'wptelegram_widget_messages',
			'title'			=> __( 'WP Telegram Widget Messages', 'wptelegram-widget' ),
			'object_types'	=> array( 'options-page' ),
			'option_key'	=> 'wptelegram_widget_messages',
			'capability'	=> 'manage_options',
			'parent_slug'	=> 'wptelegram_widget',
			'menu_title'	=> __( 'Widget Messages', 'wptelegram-widget' ),
			'save_fields'	=> false,
			'display_cb'	=> array( $this, 'render_messages_page' ),
			'save_button'	=> __( 'Pull Messages', 'wptelegram-widget' ),
		);

		$cmb2 = new_cmb2_box( $box );

		$cmb2->add_field( array(
			'name'			=> __( 'Latest Post Link', 'wptelegram-widget' ),
			'desc'			=> esc_html__( 'Goto your channel, Tap or Right-Click on the latest post and choose "Copy Post Link"', 'wptelegram-widget' ),
			'id'			=> 'post_url',
			'type'			=> 'text',
			'attributes'	=> array(
				'required'		=> 'required',
				'placeholder'	=> 'e.g. https://t.me/WPTelegram/61',
			),
		) );
		$cmb2->add_field( array(
			'name'			=> __( 'Number of messages', 'wptelegram-widget' ),
			'id'			=> 'num_messages',
			'type'			=> 'text_small',
			'default'		=> 5,
			'attributes'	=> array(
				'type'			=> 'number',
				'placeholder'	=> '5',
				'min'			=> 1,
				'pattern'		=> '[0-9]{1,2}',
				'maxlength'		=> 2,
			),
		) );
		$cmb2->add_field( array(
			'id'		=> 'ajax_nonce',
			'type'		=> 'hidden',
			'default'	=> wp_create_nonce( 'wptelegram-widget-ajax-nonce' ),
		) );
	}

	public function pull_messages(){
		if ( current_user_can( 'manage_options' ) && check_ajax_referer( 'wptelegram-widget-ajax-nonce', 'nonce' )  ) {

			if ( isset( $_REQUEST['post_url'], $_REQUEST['num_messages'] ) ) {

				$post_url = $_REQUEST['post_url'];

				$num_messages = absint( $_REQUEST['num_messages'] );

				if ( preg_match( '/\A(?:https?:\/\/)?t\.me\/[a-z]\w{3,30}[^\W_]\/(\d+)\Z/i', $post_url, $match ) ) {

					if ( $num_messages ) {
						$post_id = (int) $match[1];
						$messages = array();
						for ( $i = 0; $i < $num_messages ; $i++ ) { 
							$messages[] = $post_id--;
						}

						$messages = array_reverse( $messages );
						WPTG_Widget()->options()->set( 'messages', $messages );

						wp_send_json_success( __( 'Messages pulled successfully', 'wptelegram-widget' ) );
					}
					wp_send_json_error( __( 'Invalid number', 'wptelegram-widget' ), 400 );
				}
				wp_send_json_error( __( 'Invalid Post Link', 'wptelegram-widget' ), 400 );
			}
			wp_send_json_error( __( 'Bad request', 'wptelegram-widget' ), 400 );
		} else {
			wp_send_json_error( __( 'Invalid request', 'wptelegram-widget' ), 403 );
		}
	}

	/**
	 * Save Screen option
	 *
	 * @since    1.0.0
	 *
	 */
	public function save_screen_option( $status, $option, $value ) {
		return $value;
	}

	/**
	 * Screen options
	 *
	 * @since    1.0.0
	 *
     * @param WP_Screen $screen Current WP_Screen object.
	 */
	public function set_screen_options( $screen ) {
		/*
		 * Check if current screen is of Hr Track
		 */
		if ( false === strpos( $screen->id, 'wptelegram_widget_messages' ) ) {
			return;
		}

	    $option = 'per_page';
		$args = array(
			'label'		=> __( 'Messages', 'wptelegram-widget' ),
			'default'	=> 10,
			'option'	=> 'messages_per_page'
		);

		$screen->add_option( 'per_page', $args );

		$this->list_table = new WPTelegram_Widget_Messages_List();
	}

	public function render_messages_page( $hookup ) {
		
		$bot_token = WPTG_Widget()->options()->get( 'bot_token' );
		$username = WPTG_Widget()->options()->get( 'username' );
		$setup = false;
		if ( $bot_token && $username ) {
			$setup = true;
		}
		$this->list_table->prepare_items();
		// Output custom markup for the options-page.
		?>
		<div class="wrap cmb2-options-page option-<?php echo $hookup->option_key; ?>">
			<?php if ( $hookup->cmb->prop( 'title' ) ) : ?>
				<h2><?php echo wp_kses_post( $hookup->cmb->prop( 'title' ) ); ?></h2>
			<?php endif; ?>
			<?php if ( $hookup->cmb->prop( 'description' ) ) : ?>
				<h2><?php echo wp_kses_post( $hookup->cmb->prop( 'description' ) ); ?></h2>
			<?php endif; ?>
			<?php if ( $setup ) : ?>
			<?php $this->display_pull_message_fields( $hookup ); ?>
			<form method="POST">
				<?php $this->list_table->display(); ?>
			</form>
			<?php else : ?>
			<p><?php esc_html_e( 'Please enter the Bot Token and Username in Widget Settings page.', 'wptelegram-widget' ); ?></p>
			<?php endif; ?>
		</div>
		<?php
	}

	public function display_pull_message_fields( $hookup ) {
		?>
		<h2><?php esc_html_e( 'Manually Pull the messages from your channel', 'wptelegram-widget' ) ?></h2>
		<form class="cmb-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" id="<?php echo $hookup->cmb->cmb_id; ?>" enctype="multipart/form-data" encoding="multipart/form-data">
			<input type="hidden" name="action" value="<?php echo esc_attr( $hookup->option_key ); ?>">
			<?php $hookup->options_page_metabox(); ?>
			<?php submit_button( esc_attr( $hookup->cmb->prop( 'save_button' ) ), 'primary', 'submit-pull' ); ?>
		</form>
		<?php
	}

	/**
	 * Handles sanitization for the fields
	 *
	 * @param  mixed      $value      The unsanitized value from the form.
	 * @param  array      $field_args Array of field arguments.
	 * @param  CMB2_Field $field      The field object
	 *
	 * @return mixed                  Sanitized value to be stored.
	 */
	public function sanitize_values( $value, $field_args, $field ) {
		
		$status = '';
		$value = preg_replace( '/[@\s]/', '', sanitize_text_field( $value ) );
		switch ( $field->id() ) {
			case 'bot_token':
				if ( empty( $value ) ) {
					$status = 'empty';
				} elseif ( ! preg_match( '/\A\d{9}:[\w-]{35}\Z/', $value ) ) {
					$status = 'invalid';
				}
				break;
			case 'username':
				if ( empty( $value ) ) {
					$status = 'empty';
				} elseif ( ! preg_match( '/\A[a-z]\w{3,30}[^\W_]\Z/i', $value ) ) {
					$status = 'invalid';
				}
				break;
		}

		if ( ! empty( $status ) ) {
			$value = $field->value();
			$transient = 'wptelegram_widget_cmb2_invalid_fields';
			$invalid_fields = get_transient( $transient );
			/**
			 * avoid E_WARNING in latest PHP versions
			 * for inserting elements into string or boolean as array
			 */
			if ( empty( $invalid_fields ) ) {
				$invalid_fields = array();
			}
			$invalid_fields[ $field->id() ] = $status;
			set_transient( $transient, $invalid_fields, 30 );
		}
		return $value;
	}

	/**
	 * Callback to define the options-saved message.
	 *
	 * @param CMB2  $cmb The CMB2 object.
	 * @param array $args {
	 *     An array of message arguments
	 *
	 *     @type bool   $is_options_page Whether current page is this options page.
	 *     @type bool   $should_notify   Whether options were saved and we should be notified.
	 *     @type bool   $is_updated      Whether options were updated with save (or stayed the same).
	 *     @type string $setting         For add_settings_error(), Slug title of the setting to which
	 *                                   this error applies.
	 *     @type string $code            For add_settings_error(), Slug-name to identify the error.
	 *                                   Used as part of 'id' attribute in HTML output.
	 *     @type string $message         For add_settings_error(), The formatted message text to display
	 *                                   to the user (will be shown inside styled `<div>` and `<p>` tags).
	 *                                   Will be 'Settings updated.' if $is_updated is true, else 'Nothing to update.'
	 *     @type string $type            For add_settings_error(), Message type, controls HTML class.
	 *                                   Accepts 'error', 'updated', '', 'notice-warning', etc.
	 *                                   Will be 'updated' if $is_updated is true, else 'notice-warning'.
	 * }
	 */
	public function custom_settings_messages( $cmb, $args ){
		if ( ! empty( $args['should_notify'] ) ) {

			if ( $args['is_updated'] ) {

				// Modify the updated message.
				$args['message'] = esc_html__( 'Settings updated', 'wptelegram-widget' );
			}

			$transient = 'wptelegram_widget_cmb2_invalid_fields';
			$invalid_fields = get_transient( $transient );
			if ( ! empty( $invalid_fields ) ) {
				$args['type'] = 'error';
				foreach ( (array) $invalid_fields as $field => $status ) {
					$field_name = $cmb->get_field(
						array(
							'id' => $field,
							'cmb_id' => $cmb->prop( 'id' ),
						)
					)->args( 'name' );

					if ( 'empty' == $status ) {
						$args['message'] = sprintf( esc_html__( '%s is empty', 'wptelegram-widget' ), $field_name );
					} else {
						$args['message'] = sprintf( esc_html__( 'Invalid %s', 'wptelegram-widget' ), $field_name );
					}
					
					add_settings_error( $args['setting'], $args['code'], $args['message'], $args['type'] );
				}
			} else {
				add_settings_error( $args['setting'], $args['code'], $args['message'], $args['type'] );
			}
			delete_transient( $transient );
		}
	}
	
	/**
	 * Render the instructions related to shortcode
	 * @param  object $field_args Current field args
	 * @param  object $field      Current field object
	 */
	public function render_shortcode_guide( $field_args, $field ){
		?>
		<div class="cmb-row">
			<p><?php printf( __( 'Goto %s and click/drag %s and place it where you want it to be.', 'wptelegram-widget' ), '<b>' . __( 'Appearance' ) . ' &gt; <a href="' . admin_url( 'widgets.php' ) . '">' . __( 'Widgets' ) . '</a></b>', '<b>' . $this->title . '</b>' ); ?></p>
			<p><?php echo __( 'Alternately, you can use the below shortcode.', 'wptelegram-widget' ); ?></p>
			<h4><?php echo __( 'Inside page or post content:', 'wptelegram-widget' ); ?></h4>
			<p><code><?php echo esc_html( '[wptelegram-widget num_messages="5" widget_width="100" author_photo="always_hide"]' ); ?></code></p>
			<h4><?php echo __( 'Inside the theme templates', 'wptelegram-widget' ); ?></h4>
			<?php $pre = "<?php\nif ( function_exists( 'wptelegram_widget' ) ) {\n    \$args = array(\n        // 'num_messages'    => 5,\n        // 'widget_width'    => 100,\n        // 'author_photo'    => 'auto',\n    );\n\n    wptelegram_widget( \$args );\n}\n?>"; ?>
			<p><pre><?php echo esc_html( $pre ); ?></pre></p>
			<h5><?php _e( 'or', 'wptelegram-widget' ); ?></h5>
			<?php $code = '<?php echo do_shortcode( \'[wptelegram-widget num_messages="5" widget_width="100" author_photo="always_show"]\' ); ?>'; ?>
			<p><code><?php echo esc_html( $code ); ?></code></p>
		</div>
		<?php
	}
	
	/**
	 * Render the settings page header
	 * @param  object $field_args Current field args
	 * @param  object $field      Current field object
	 */
	public function render_header( $field_args, $field ){

		$plugin_url = WPTELEGRAM_WIDGET_URL;
		$text_domain = 'wptelegram-widget';

		include_once WPTELEGRAM_WIDGET_DIR . '/admin/partials/wptelegram-widget-admin-header.php';
		?>
		<div class="cmb-row wptelegram-header-desc">
			<p><?php echo __( 'With this plugin, you can display your public Telegram Channel or Group feed in a WordPress widget or anywhere else using a shortcode.', 'wptelegram-widget' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Output the Messages Button
	 * @param  object $field_args Current field args
	 * @param  object $field      Current field object
	 */
	public static function render_messages_button( $field_args, $field ) {
		
		$bot_token = WPTG_Widget()->options()->get( 'bot_token' );
		$username = WPTG_Widget()->options()->get( 'username' );

		if ( $bot_token && $username ) {
			?>
			<div class="cmb-row">
				<p><?php echo sprintf( __( 'You can view the messages from %s','wptelegram-widget' ), '<a href="' . esc_attr( admin_url( 'admin.php?page=wptelegram_widget_messages' ) ) . '">' . __( 'Widget Messages', 'wptelegram-widget' ) . '</a>' ); ?></p>
			</div>
			<?php
		}
	}

	/**
	 * Output the Telegram Instructions
	 * @param  object $field_args Current field args
	 * @param  object $field      Current field object
	 */
	public static function get_telegram_guide( $field_args, $field ) { ?>
		<p style="color:#f10e0e;"><b><?php echo __( 'INSTRUCTIONS!','wptelegram-widget'); ?></b></p>
		 <ol style="list-style-type: decimal;">
		 	<li><?php esc_html_e( 'Create a Channel/group/supergroup', 'wptelegram-widget' );?></a>&nbsp;(<?php esc_html_e( 'If you haven\'t', 'wptelegram-widget' );?>)</li>
		 	<li><?php echo sprintf( __( 'Create a Bot by sending %s command to %s', 'wptelegram-widget' ), '<code>/newbot</code>', '<a href="https://t.me/BotFather"  target="_blank">@BotFather</a>' );
            ?></li>
		 	<li><?php echo sprintf( __( 'After completing the steps %s will provide you the Bot Token.', 'wptelegram-widget' ), '@BotFather' );?></li>
		 	<li><?php esc_html_e( 'Copy the token and paste into the Bot Token field below.', 'wptelegram-widget' );?>&nbsp;<?php esc_html_e( 'For ease, use', 'wptelegram-widget' );?>&nbsp;<a href="<?php echo esc_url( 'https://web.telegram.org' ); ?>" target="_blank">Telegram Web</a></li>
		 	<li><?php echo __( 'Add the Bot as Administrator to your Channel/Group', 'wptelegram-widget' );?></li>
		 	<li><?php esc_html_e( 'Enter the Channel/Group Username in the field below', 'wptelegram-widget' );?>
		 	</li>
		 	<li><?php echo sprintf( __( 'Hit %s below', 'wptelegram-widget' ), '<b>' . __( 'Save Changes' ) . '</b>' );?></li>
		 	<li><?php esc_html_e( 'That\'s it. You are ready to rock :)', 'wptelegram-widget' );?></li>
		 </ol>
		 <p style="color:#396609;"><b><?php echo __( 'Tip!','wptelegram-widget'), '💡'; ?></b>&nbsp;<span><?php esc_html_e( 'Updates are pulled every five minutes if someone visits your website.', 'wptelegram-widget' );?></span>&nbsp;<span><?php esc_html_e( 'To make sure the updates are pulled in time, it is recommended to set up a cron on your hosting server that hits the below URL every five minutes or so.', 'wptelegram-widget' );?></span>
		 	<pre><?php echo esc_html( admin_url( 'admin-post.php?action=wptelegram_widget_pull_updates' ) ); ?></pre>
		 </p>
		 <p style="color:#f10e0e;"><b><?php echo __( 'Note!','wptelegram-widget'); ?></b>&nbsp;<span><?php esc_html_e( 'Do not use the same Bot Token that you use for sending messages to the channel. The messages sent with the same Bot Token will not appear in the widget.', 'wptelegram-widget' );?></span></p>
		 <?php
	}
	/**
	 * Output a the after field html
	 * @param  object $field_args Current field args
	 * @param  object $field      Current field object
	 */
	public static function render_after_field( $field_args, $field ){
		$id = $field->id(); ?>
		<br>
		<?php if ( 'bot_token' == $id ) : ?>

			<p><span id="bot_token-info" class="info"></span></p>
			<p><span id="bot_token-err" class="hidden wptelegram-err info">&nbsp;<?php esc_html_e('Invalid Bot Token', 'wptelegram-widget' ); ?></span></p>

		<?php elseif ( 'username' == $id ) : ?>
			<p><span id="username-err" class="hidden wptelegram-err info">&nbsp;<?php esc_html_e('Invalid Username', 'wptelegram-widget' ); ?></span></p>
			<p><span id="username-info" class="hidden info"><?php esc_html_e( "Members Count:", "wptelegram" ); ?></span></p>
			<table id="username-chat-table" class="hidden">
				<tbody>
					<tr>
						<th><?php esc_html_e( "Username", "wptelegram-widget" ); ?></th>
						<th><?php esc_html_e( "Name/Title", "wptelegram-widget" ); ?></th>
						<th><?php esc_html_e( "Type", "wptelegram-widget" ); ?></th>
						<th><?php esc_html_e( "Test Status", "wptelegram-widget" ); ?></th>
					</tr>
				</tbody>
			</table>
		<?php endif; ?>
	<?php
	}

	public static function get_button_html( $id ){
		$text = '';
		switch ( $id ) {
			case 'bot_token':
				$text = __( 'Test Token', 'wptelegram-widget' );
				break;
			case 'username':
				$text = __( 'Send Test', 'wptelegram-widget' );
				break;
		}
		$html = '<button type="button" id="button-' . $id . '" class="button-secondary" data-id="' . $id . '">' . $text . '</button>';
		return $html;
	}

 	/**
	 * Create our feed widget
	 *
	 * @since    1.0.0
	 */
	public function register_widgets() {
	    register_widget( 'WPTelegram_Widget_Widget' );
	}

	/**
	 * Pull updates from Telegram
	 *
	 * @since    1.0.0
	 */
	public function pull_updates() {

        /**
         * Fires before doing anything
         */
        do_action( 'wptelegram_widget_pull_updates_init' );

		$bot_token = WPTG_Widget()->options()->get( 'bot_token' );
		$username = WPTG_Widget()->options()->get( 'username' );

		if ( ! $bot_token || ! $username ) {
			return;
		}

		$params = $this->get_update_params();

		$bot_api = new WPTelegram_Bot_API( $bot_token );

		$res = $bot_api->getUpdates( $params );

		if ( ! $bot_api->is_success( $res ) ) {

			do_action( 'wptelegram_widget_getupdates_failed', $res, $bot_token );

			// Conflict: when webhook is active
            if ( ! is_wp_error( $res ) && 409 === $res->get_response_code() ) {
                $bot_api->deleteWebhook();
            }
			return;
		}

		$updates = $res->get_result();

		// for tests
		$updates = (array) apply_filters( 'wptelegram_widget_updates', $updates );

		do_action( 'wptelegram_widget_after_getupdates', $updates, $res );

		if ( ! empty( $updates ) ) {
			// Pass the updates to the handler
			$this->handle_updates( $updates );
		}

		/**
         * Fires after doing everything
         */
        do_action( 'wptelegram_widget_pull_updates_finish', $updates );

		exit( ':)' );
	}

	/**
	 * Get params for getUpdates
	 *
	 * @since    1.0.0
	 */
	private function get_update_params() {

		$update_id = (int) WPTG_Widget()->options()->get( 'last_update_id' );

		if ( $update_id ) {
			$offset = ++$update_id;
		}

		$allowed_updates = json_encode( $this->get_allowed_updates() );

		$update_params = compact( 'offset', 'allowed_updates' );

		return (array) apply_filters( 'wptelegram_widget_update_params', $update_params );
	}

	/**
	 * Get allowed_updates
	 *
	 * @since    1.0.0
	 */
	private function get_allowed_updates() {

		$allowed_updates = array(
			'channel_post',
			// 'edited_channel_post',
			'message',
			// 'edited_message',
		);

		return (array) apply_filters( 'wptelegram_widget_allowed_updates', $allowed_updates );
	}

	/**
	 * Handle updates
	 *
	 * @param array $updates Array of updates
	 *
	 * @since    1.0.0
	 */
	private function handle_updates( $updates ) {

        /**
         * Fires before doing anything
         */
        do_action( 'wptelegram_widget_handle_updates_init', $updates );

		$new_messages = $edited_messages = array();
		$messages = WPTG_Widget()->options()->get( 'messages', array() );

		foreach ( (array) $updates as $update ) {

			// $is_edited passed by reference
			$message_id = $this->process_update( $update, $is_edited );

			if ( $message_id ) {

				// if it exists in the existing message IDs
				if ( $is_edited && in_array( $message_id, $messages ) ) {
					$edited_messages[] = $message_id;
				} else {
					$new_messages[] = $message_id;
				}
			}
		}

		$update_id = $update['update_id'];
		WPTG_Widget()->options()->set( 'last_update_id', $update_id );

		if ( ! empty( $new_messages ) ) {

			$this->save_messages( $new_messages );
		}

		/*if ( ! empty( $edited_messages ) ) {

			$this->save_messages( $edited_messages, 'edited' );
		}*/

        /**
         * Fires after doing everything
         */
        do_action( 'wptelegram_widget_handle_updates_finish', $updates, $new_messages, $edited_messages );
	}

	/**
	 * Process an update
	 *
	 * @param array $update Update object
	 *
	 * @since    1.3.0
	 */
	private function process_update( $update, &$is_edited ) {

		/**
		 * Fires before doing anything
		 */
		do_action( 'wptelegram_widget_process_update_init', $update );

		$update_type = $this->get_update_type( $update );

		if ( ! $update_type ) {
			return false;
		}

		$message = $update[ $update_type ];

		$verified = $this->verify_username( $message );

		if ( ! $verified ) {
			return false;
		}

		$is_edited = ( 0 === strpos( $update_type, 'edited_' ) ) ? true : false;

		/**
		 * Fires after doing everything
		 */
		do_action( 'wptelegram_widget_process_update_finish', $update, $message, $verified, $is_edited );

		return $message['message_id'];
		
	}

	/**
	 * Get the update_type
	 *
	 * @since  1.0.0
	 *
	 */
	private function get_update_type( $update ) {

		$update_type = '';

		$allowed_types = $this->get_allowed_updates();

		foreach ( $allowed_types as $type ) {
			
			if ( isset( $update[ $type ] ) ) {
				
				$update_type = $type;
				break;
			}
		}

		return apply_filters( 'wptelegram_widget_update_type', $update_type, $update );
	}

	/**
	 * Verify that the update if from the saved channel
	 * Verify by comparing username 
	 *
	 * @since  1.0.0
	 *
	 */
	private function verify_username( $message ) {

		$verified = false;

		$username = false;

		if ( isset( $message['chat']['username'] ) ) {
			
			$username = $message['chat']['username'];
		}
		
		$saved_username = WPTG_Widget()->options()->get( 'username' );

		if ( ! empty( $saved_username ) && strtolower( $saved_username ) === strtolower( $username ) ) {
			$verified = true;
		}

		return (bool) apply_filters( 'wptelegram_widget_verify_username', $verified, $message );
	}

	/**
	 * Store the message_ids
	 *
	 * @since  1.0.0
	 *
	 */
	private function save_messages( array $messages, $type = '' ) {

		$type = $type ? "{$type}_" : $type;

		$saved_messages = WPTG_Widget()->options()->get( "{$type}messages", array() );
		$messages = array_unique( array_merge( $saved_messages, $messages ) );
		$messages = array_filter( $messages );

		// allow maximum 50 messages
		$limit = (int) apply_filters( 'wptelegram_widget_saved_messages_limit', 50 );

		while ( count( $messages ) > $limit ) {
			array_shift( $messages );
		}

		WPTG_Widget()->options()->set( "{$type}messages", $messages );
	}

	/**
	 * Render the HTML of the widget message
	 *
	 * @since  1.3.0
	 */
	public function render_widget_view() {
		if ( ! isset( $_GET['message_id'] ) ) {
			return;
		}

		$message_id = sanitize_text_field( $_GET['message_id'] );

		$username = WPTG_Widget()->options()->get( 'username' );

		if ( empty( $username ) ) {
			return;
		}

		$url = $this->get_embed_url( $username, $message_id );

		$response = wp_remote_get( $url );
		$code = wp_remote_retrieve_response_code( $response );
		$html = wp_remote_retrieve_body( $response );

		if ( 200 !== $code || empty( $html ) ) {
			return;
		}

		$dom = new DOMDocument;

	    @$dom->loadHTML( $html );

	    if ( $this->post_still_exists( $dom ) ) {
	    	echo $this->get_widget_html( $dom );
	    	return;
	    }

	    // remove the post from saved messages
	    $this->remove_post( $message_id );
	}

	/**
	 * Remove the post message from saved messages
	 *
	 * @since  1.3.0
	 */
	public function remove_post( $message_id ) {

		$messages = WPTG_Widget()->options()->get( 'messages', array() );

		// use array_keys() instead of array_search()
		$keys = array_keys( $messages, $message_id );
		unset( $messages[ reset( $keys ) ] );

		// destroy keys
		$messages = array_values( $messages );

		WPTG_Widget()->options()->set( 'messages', $messages );
	}

	/**
	 * Get the widget HTML after processing
	 *
	 * @since  1.3.0
	 */
	public function get_widget_html( $dom ) {

		/* Inject Override style */
	    $heads = $dom->getElementsByTagName( 'head' );
	    // for some weird PHP installations
	    if ( $heads->length ) {
	    	$head = $heads->item( 0 );
	    	$style_elm = $dom->createElement( 'style', 'body.body_widget_post { min-width: initial; }' );
			$elm_type_attr = $dom->createAttribute( 'type' );
			$elm_type_attr->value = 'text/css';
			$style_elm->appendChild( $elm_type_attr );
			$head->appendChild( $style_elm );
	    }
		/* Inject Override style */

	    /* Remove Google Analytics Code to avoid console errors */
	    $scripts = $dom->getElementsByTagName( 'script' );
	    foreach ( $scripts as $script ) {
	    	
	    	if ( false !== strpos( $script->nodeValue, 'GoogleAnalyticsObject' ) ) {
	    		$script->parentNode->removeChild ( $script );
	    		break;
	    	}
	    }
		/* Remove Google Analytics Code to avoid console errors */

		$html = $dom->saveHTML();

	    return (string) apply_filters( 'wptelegram_widget_widget_html', $html, $dom );
	}

	/**
	 * If the post is found - not deleted
	 *
	 * Searches for "tgme_widget_message_error" class
	 * in the widget HTML
	 *
	 * @since  1.3.0
	 */
	public function post_still_exists( $dom ) {
		$finder = new DomXPath( $dom );
		$classname = 'tgme_widget_message_error';
		$nodes = $finder->query( "//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]" );

		foreach ( $nodes as $node ) {

			if ( preg_match( '/not found/iu', $node->nodeValue ) ) {

				return false;
			}
		}

		return true;
	}

	/**
	 * Render the HTML of the widget message
	 *
	 * @since  1.3.0
	 */
	public function get_embed_url( $username, $message_id ) {

		$url = "https://t.me/{$username}/{$message_id}";
		$args = array(
			'embed'	=> true,
		);
		if ( isset( $_GET['userpic'] ) ) {
			$args['userpic'] = sanitize_text_field( $_GET['userpic'] );
		}

		$url = add_query_arg( $args, $url );

		return (string) apply_filters( 'wptelegram_widget_embed_url', $url, $username );
	}
}
