<?php
/**
 * List Table API: WPTelegram_Widget_Messages_List class
 *
 * @link       https://t.me/manzoorwanijk
 * @since      1.0.0
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/admin
 */

/**
 * Class used to implement displaying employees in a list table.
 *
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/admin
 * @author     Manzoor Wani 
 * @since 1.0.0
 *
 * @see WP_List_Table
 */

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WPTelegram_Widget_Messages_List extends WP_List_Table {

	public $messages;

	/** Class constructor */
	public function __construct() {

		parent::__construct( array(
			'singular'	=> __( 'Post', 'wptelegram-widget' ), //singular name of the listed records
			'plural'	=> __( 'Posts', 'wptelegram-widget' ), //plural name of the listed records
			'ajax'		=> false //does this table support ajax?
		) );

		$this->messages = WPTG_Widget()->options()->get( 'messages', array() );
	}

	/**
	 * Retrieve messages
	 *
	 * @return array
	 */
	public function get_messages() {
		return $this->messages;
	}


	/**
	 * Delete a message.
	 *
	 * @param int $message_id Message ID
	 */
	public function delete_message( $message_id ) {
		if ( ( $key = array_search( $message_id, (array) $this->messages ) ) !== false ) {
		    unset( $this->messages[ $key ] );
		}
		$this->update_messages();
	}

	private function update_messages() {
		WPTG_Widget()->options()->set( 'messages', $this->messages );
	}

	/**
	 * Returns the count of messages
	 *
	 * @return null|string
	 */
	public function record_count() {
		return count( $this->messages );
	}


	/** Text displayed when no messages are found */
	public function no_items() {
		_e( 'No Messages found.', 'wptelegram-widget' );
	}

	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'link':
				return $this->get_message_link( $item );
			case 'widget':
				return $this->get_message_widget( $item );
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	/**
	 * Get the message link
	 *
	 * @param int $message_id
	 *
	 * @return string
	 */
	private function get_message_link( $message_id ){
		
		$username = WPTG_Widget()->options()->get( 'username' );
		$url = "https://t.me/{$username}/{$message_id}";
		return '<a href="' . esc_attr( $url ) . '" target="_blank">' . $url . '</a>';
	}

	/**
	 * Get the message widget
	 *
	 * @param int $message_id
	 *
	 * @return string
	 */
	private function get_message_widget( $message_id ){

		$username = WPTG_Widget()->options()->get( 'username' );
		$url = WPTelegram_Widget_Public::get_message_view_url( $username, $message_id );
		
		$html = '<div class="wptelegram_widget_list-message" style="max-height:300px;"><iframe frameborder="0" scrolling="no" width="100%" src="' . esc_attr( $url ) . '">Your Browser Does Not Support iframes!</iframe><div>';
		return $html;
	}

	/**
	 * Render the bulk action checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item
		);
	}


	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_message_id( $item ) {

		$delete_nonce = wp_create_nonce( 'wptelegram_widget_delete_messages' );

		$title = '<strong>' . $item . '</strong>';
		$delete_query_args = array(
			'action' => 'delete',
			'message_id'  => $item,
		);

		$actions = array(
			'delete' => sprintf(
				'<a href="%1$s">%2$s</a>',
				esc_url( wp_nonce_url( add_query_arg( $delete_query_args ), 'wptelegram_widget_delete_messages' ) ),
				_x( 'Delete', 'List table row action', 'wptelegram-widget' )
			),
		);

		return $title . $this->row_actions( $actions );
	}


	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = array(
			'cb'			=> '<input type="checkbox" />',
			'message_id'	=> __( 'Message ID', 'wptelegram-widget' ),
			'link'			=> __( 'Link', 'wptelegram-widget' ),
			'widget'		=> __( 'Widget', 'wptelegram-widget' ),
		);
		return $columns;
	}


	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'message_id'	=> array( 'message_id', true ),
		);

		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = array(
			'bulk-delete' => __( 'Delete', 'wptelegram-widget' )
		);

		return $actions;
	}

	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {
		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page = $this->get_items_per_page( 'messages_per_page', 5 );
		$pagenum = $this->get_pagenum();

		$items = $this->get_messages();

		// If orderby is set, use this as the sort column
        if( isset( $_GET['orderby'], $_GET['order'] ) && 'desc' == $_GET['order'] ) {
            $items = array_reverse( $items );
        }

		$this->items = array_slice( $items, ( ( $pagenum - 1 ) * $per_page ), $per_page );

		$total_items = $this->record_count();

		$this->set_pagination_args( array(
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		) );
	}

	public function process_bulk_action() {
		
		//Detect when a bulk action is being triggered...
		if ( 'delete' == $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'wptelegram_widget_delete_messages' ) ) {
				die('No script kiddies');
			} else {
				$this->delete_message( absint( $_GET['message_id'] ) );
				$this->may_be_redirect();
			}
		}

		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {

			$delete_ids = array_map( 'sanitize_text_field', $_POST['bulk-delete'] );

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $message_id ) {
				$this->delete_message( $message_id );
			}
			$this->may_be_redirect();
		}
	}

	public function may_be_redirect() {
		$url = remove_query_arg( array( 'action', '_wpnonce' ) );
		if( ! headers_sent() ){
			wp_safe_redirect( esc_url_raw( $url ), 302 );
			exit;
		} else {
			$destination = $url == false ? 'location.reload();' : 'window.location.href="' . $url . '";';
			die( '<script>' . $destination . '</script>' );
	    }
	}
}