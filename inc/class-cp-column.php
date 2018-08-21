<?php
/**
 * Cp_Column
 *
 * This class add new columns into the post list as new custom features for this columns.
 *
 * @link     http://www.hashbangcode.com/
 * @package    CoffeePress
 * @subpackage Column
 * @link       https://donini.me/
 *
 * References:
 * https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_posts_columns
 * https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_pages_columns
 * https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_$post_type_posts_columns
 * https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_$taxonomy_id_columns
 */
class Cp_Column {
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
		add_filter( 'manage_posts_columns', array( $this, 'add_column' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'add_column_content' ), 10, 2 );

		add_filter( 'manage_message_posts_columns', array( $this, 'add_message_column' ) );
		add_action( 'manage_message_posts_custom_column', array( $this, 'add_message_column_content' ), 10, 2 );
	}

	/**
	 * Add new custom column item to columns array.
	 *
	 * @param array $columns current list columns.
	 */
	public function add_column( $columns ) {
		$columns['featured_image'] = __( 'Featured Image' );
		return $columns;
	}

	/**
	 * Show the value of the custom colum.
	 *
	 * @param string $column_name the name of the colum.
	 * @param int    $post_id post ID.
	 */
	public function add_column_content( $column_name, $post_id ) {
		if ( 'featured_image' === $column_name ) {
			$featured_image = get_the_post_thumbnail_url( $post_id );
			if ( has_post_thumbnail() ) {
				echo get_the_post_thumbnail( $post_id, array( 60, 60 ) );
			} else {
				echo esc_html( __( 'No feature image set.' ) );
			}
		}
	}

	/**
	 * Add new custom column item to custom post type columns array.
	 *
	 * @param array $columns current list columns.
	 */
	public function add_message_column( $columns ) {
		$columns['show_where'] = __( 'Visible Where?' );
		return $columns;
	}

	/**
	 * Show the value of the custom colum for the custom post type.
	 *
	 * @param string $column_name the name of the colum.
	 * @param int    $post_id post ID.
	 */
	public function add_message_column_content( $column_name, $post_id ) {
		if ( 'show_where' === $column_name ) {
			$show_where = get_field( 'show_where', $post_id );
			switch ( $show_where ) {
				case 'all_pages':
					echo esc_html( __( 'All pages' ) );
					break;

				case 'specific_page':
					$what_page = get_field( 'what_page', $post_id );
					echo sprintf(
						wp_kses( __( 'Specific page: <a target="_blank" href="%1$s">%2$s</a>' ), array( 'a' => array( 'href' => array() ) ) ),
						get_permalink( $post_id ),
						get_the_title( $what_page )
					);
					break;
			}
		}
	}
}
Cp_Column::get_instance();
