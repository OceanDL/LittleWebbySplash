<?php
namespace Raven\Modules\Posts\Widgets;

defined( 'ABSPATH' ) || die();

use Raven\Utils;
use Raven\Modules\Posts\Classes\Base_Widget;
use Raven\Modules\Posts\Module;
use Raven\Modules\Posts\Carousel\Skins;

/**
 * Temporary suppressed.
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class Posts_Carousel extends Base_Widget {

	public function get_name() {
		return 'raven-posts-carousel';
	}

	public function get_title() {
		return __( 'Posts Carousel', 'raven' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-posts-carousel';
	}

	public function get_script_depends() {
		return [ 'jquery-slick', 'raven-object-fit' ];
	}

	public function get_style_depends() {
		return [ 'dashicons' ];
	}

	protected function _register_skins() {
		$this->add_skin( new Skins\Classic( $this ) );
		$this->add_skin( new Skins\Cover( $this ) );
	}

	protected function _register_controls() {
		$this->register_content_controls();
		$this->register_settings_controls();
		$this->register_sort_filter_controls();
	}

	protected function register_content_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'raven' ),
			]
		);

		$this->add_group_control(
			'raven-posts',
			[
				'name' => 'query',
				'post_type' => [
					'post' => __( 'Blog', 'raven' ),
					'portfolio' => __( 'Portfolio', 'raven' ),
				],
			]
		);

		$this->end_controls_section();

		$this->update_control(
			'_skin',
			[
				'frontend_available' => 'true',
			]
		);
	}

	protected function register_settings_controls() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'raven' ),
			]
		);

		$this->add_control(
			'query_posts_per_page',
			[
				'label' => __( 'How many posts?', 'raven' ),
				'type' => 'number',
				'default' => 10,
				'min' => 1,
				'max' => 50,
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function register_sort_filter_controls() {
		$this->start_controls_section(
			'section_sort_filter',
			[
				'label' => __( 'Sort & Filter', 'raven' ),
			]
		);

		$this->add_control(
			'query_orderby',
			[
				'label' => __( 'Order By', 'raven' ),
				'type' => 'select',
				'default' => 'date',
				'options' => [
					'date' => __( 'Date', 'raven' ),
					'title' => __( 'Title', 'raven' ),
					'menu_order' => __( 'Menu Order', 'raven' ),
					'rand' => __( 'Random', 'raven' ),
				],
			]
		);

		$this->add_control(
			'query_order',
			[
				'label' => __( 'Order', 'raven' ),
				'type' => 'select',
				'default' => 'DESC',
				'options' => [
					'ASC' => __( 'ASC', 'raven' ),
					'DESC' => __( 'DESC', 'raven' ),
				],
			]
		);

		$this->add_control(
			'query_offset',
			[
				'label' => __( 'Offset', 'raven' ),
				'description' => __( 'Use this setting to skip over posts (e.g. \'4\' to skip over 4 posts).', 'raven' ),
				'type' => 'number',
				'default' => 0,
				'min' => 0,
				'max' => 100,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'query_excludes',
			[
				'label' => __( 'Excludes', 'raven' ),
				'type' => 'select2',
				'multiple' => true,
				'label_block' => true,
				'default' => [ 'current_post' ],
				'options' => [
					'current_post' => __( 'Current Post', 'raven' ),
				],
			]
		);

		$this->end_controls_section();
	}

	public function get_query_posts() {
		$args = Utils::get_query_args( $this->get_settings() );

		$wp_query = new \WP_Query( $args );

		return $wp_query;
	}

	public static function excerpt_more() {
		return '';
	}

	protected function render() {}
}
