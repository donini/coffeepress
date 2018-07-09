<?php
/**
 * Cp_Message
 *
 * This class is responsible to show messages for the user in the front-end for the users.
 *
 * @package    CoffeePress
 * @subpackage Message
 * @link       https://donini.me/
 */
class Cp_Message {

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
		add_action( 'init', array( $this, 'message_custom_post' ) );
		$this->acf_fields(); 
	}

	/**
	 *  Load message CPT fields
	 */
	public function acf_fields() {
		include COFFEEPRESS_PATH . 'acf/message-fields.php';
	}

	/**
	 *  Register the CPT message
	 */
	public function message_custom_post() {
		register_post_type( 'message',
			array(
				'labels'            => array(
					'name'          => __( 'Messages' ),
					'singular_name' => __( 'Message' ),
				),
				'public'            => true,
				'has_archive'       => true,
				'publicy_queryable' => false,
				'supports'          => array( 'title', 'editor', 'page-attributes' ),
				'rewrite'           => array( 'slug' => 'message' ),
			)
		);
	}
}
Cp_Message::get_instance();
