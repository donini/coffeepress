
<?php
/**
 * Cp_Message
 *
 * This class is responsible to add new Bulk Actions in the HTML dropdown of the post lists.
 *
 * @package    CoffeePress
 * @subpackage Bulk-Actions
 * @link       https://donini.me/
 */

class Cp_Bulk_Action {

	/**
	 * Load the class instance.
	 *
	 * @var class Instance.
	 */
	private static $instance = null;

	/**
	 * Set the plugin instance
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Initialize and load hooks
	 */
	public function __construct() {
		add_filter( 'bulk_actions-edit-post', array( $this, 'send_by_email_bulk_action' ) );
		add_filter( 'handle_bulk_actions-edit-post', array( $this, 'send_by_email_bulk_action_handler' ), 10, 3 );
		add_action( 'admin_notices', array( $this, 'send_by_email_bulk_action_admin_notice' ) );
	}

	/**
	 * Add the bulk action into the dropdown list
	 * 
	 * @param array $bulk_actions Current bulk action selected. 
	 */
	function send_by_email_bulk_action( $bulk_actions ) {
		$bulk_actions['send_by_email'] = __( 'Send by Email', 'send_by_email');
		return $bulk_actions;
	}

	/**
	 * Handler for the builk action.
	 * 
	 * @param string $redirect_to Url to redirect after finish the handler.
	 * @param string $doaction Action selected in the dropdown.
	 * @param string $post_ids Post IDs selected to perform the handler.
	 */
	function send_by_email_bulk_action_handler( $redirect_to, $doaction, $post_ids ) {
		if ( $doaction !== 'send_by_email' ) {
			return $redirect_to;
		}
		foreach ( $post_ids as $post_id ) {

			$post        = get_post( $post_id );
			$post_author = get_the_author_meta( 'user_email', $post->post_author );
			$post_title  = $post->post_title;
			$post_link   = get_permalink( $$post_id );

			$to      = $post_author;
			$subject = 'Post Review';
			$message = sprintf( 'Please review this post. <br /> <a href="%s">%s</a>', $post_link, $post_title );
			$headers = array('Content-Type: text/html; charset=UTF-8');

			wp_mail( $to, $subject, $message, $headers );
		}
		$redirect_to = add_query_arg( 'bulk_emailed_posts', count( $post_ids ), $redirect_to );
		return $redirect_to;
	}

	/**
	 * Show the message in the front-end.
	 */
	function send_by_email_bulk_action_admin_notice() {
	  if ( ! empty( $_REQUEST['bulk_emailed_posts'] ) ) {
		$emailed_count = intval( $_REQUEST['bulk_emailed_posts'] );
		printf( '<div id="message" class="updated fade">' .
		  _n( 'Emailed %s post.',
			'Emailed %s posts.',
			$emailed_count,
			'send_by_email'
		  ) . '</div>', $emailed_count );
	  }
	}
}
Cp_Bulk_Action::get_instance();
