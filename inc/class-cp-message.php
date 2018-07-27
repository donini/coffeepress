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
		add_filter( 'body_class', array( $this, 'add_page_id' ) );
		add_action( 'wp_ajax_insert_message', array( $this, 'insert_message' ) );
		add_action( 'wp_ajax_nopriv_insert_message', array( $this, 'insert_message' ) );
		$this->acf_fields();
	}

	/**
	 * Add the page id into the class attribute
	 *
	 * @param string $classes get the current classes of the body element.
	 */
	public function add_page_id( $classes ) {
		global $post;
		if ( is_array( $classes ) ) {
			$classes[] = 'page-id-' . $post->ID;
		}
		return $classes;
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
				'numberposts' => 1,
				'orderby'     => 'menu_order',
				'order'       => 'DESC',
				'post_type'   => 'message',
				'post_status' => 'publish',
			)
		);
	}

	/**
	 * Insert message at the header
	 */
	public function insert_message() {
		$message = $this->get_message();
		$agreed  = $this->get_param( 'agreed', null );
		$post_id = $this->get_param( 'post_id', null );
		if ( empty( $agreed ) ) {
			if ( is_array( $message ) ) {
				$current = $message[0];
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
							die;
						}
					}
				} elseif ( 'all_pages' === $message_show_where ) {
					echo $this->print_message(
						$message_content,
						esc_attr( $message_background_color ),
						esc_attr( $message_text_color ),
						esc_attr( $message_text_padding )
					);
				}
			}
		}
		die;
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
