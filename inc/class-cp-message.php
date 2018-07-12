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
class Cp_Message extends Cp_Helper {

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
		add_action( 'wp_ajax_insert_message', array( $this, 'insert_message' ) );
		add_action( 'wp_ajax_nopriv_insert_message', array( $this, 'insert_message' ) );
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
	/**
	 *  Get the last message inserted
	 */
	public function get_message() {
		return get_posts(
			array(
				'nunberposts' => 1,
				'orderby'     => 'DESC',
				'order'       => 'menu_order',
				'post_type'   => 'message',
				'post_status' => 'publish',
			)
		);
	}

	/**
	 * Insert message at the header
	 */
	public function insert_message() {
		$messsage = $this->get_message();
		$agreed = $this->get_param( 'agreed', null );
		$post_id = $this->get_param( 'post_id', null );

		if ( empty( $agreed ) ) {
			if ( is_array( $messsage ) ) {
				$current = $messsage[0];
			}
			if ( ! empty( $current ) ) {
				$message_content          = $current->post_content;
				$message_show_where       = get_field( 'show_where', $current->ID );
				$message_what_page        = get_field( 'what_page', $current->ID );
				$message_background_color = get_field( 'background_color', $current->ID );
				$message_text_color       = get_field( 'text_color', $current->ID );
				$message_text_padding     = get_field( 'text_padding', $current->ID );

				if ( 'specific_page' === $message_show_where ) {
					if ( ! empty( $post_id ) ) {
						if ( $message_what_page === (int) $post_id ) {
							echo $this->print_message(
								$message_content,
								esc_attr( $message_background_color ),
								esc_attr( $message_text_color ),
								esc_attr( $message_text_padding )
							);
						}
					}
				} else if ( 'all_pages' === $message_show_where ) {
					echo $this->print_message(
						$message_content,
						esc_attr( $message_background_color ),
						esc_attr( $message_text_color ),
						esc_attr( $message_text_padding )
					);
				}
			}
		}
	}

	/**
	 * Print the message code on screen.
	 *
	 * @param string $content Content of the message.
	 * @param string $background_color Color of the background.
	 * @param string $text_color Color of the text.
	 * @param string $text_padding Size padding of the box.
	 */
	public function print_message( $content, $background_color, $text_color, $text_padding ) {
		ob_start();
		include COFFEEPRESS_PATH . 'tpl/template-message.php';
		$message_html = ob_get_contents();
		ob_end_clean();
		return $message_html;
	}
}
Cp_Message::get_instance();
