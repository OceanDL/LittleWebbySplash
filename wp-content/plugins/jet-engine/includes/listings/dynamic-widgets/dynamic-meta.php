<?php
namespace Elementor;

use Elementor\Group_Control_Border;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Listing_Dynamic_Meta_Widget extends Widget_Base {

	private $source = false;

	public function get_name() {
		return 'jet-listing-dynamic-meta';
	}

	public function get_title() {
		return __( 'Dynamic Meta', 'jet-engine' );
	}

	public function get_icon() {
		return 'jet-engine-icon-4';
	}

	public function get_categories() {
		return array( 'jet-listing-elements' );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'Content', 'jet-engine' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'type',
			array(
				'label'   => esc_html__( 'Type', 'jet-engine' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'     => __( 'Date', 'jet-engine' ),
					'author'   => __( 'Author', 'jet-engine' ),
					'comments' => __( 'Comments', 'jet-engine' ),
				),
			)
		);

		$repeater->add_control(
			'icon',
			array(
				'label'       => __( 'Icon', 'jet-engine' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => '',
			)
		);

		$repeater->add_control(
			'prefix',
			array(
				'label'   => esc_html__( 'Prefix', 'jet-engine' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$repeater->add_control(
			'suffix',
			array(
				'label'   => esc_html__( 'Suffix', 'jet-engine' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$this->add_control(
			'meta_items',
			array(
				'type'    => Controls_Manager::REPEATER,
				'fields'  => array_values( $repeater->get_controls() ),
				'default' => array(
					array(
						'type' => 'date',
						'icon' => 'fa fa-calendar',
					),
					array(
						'type' => 'author',
						'icon' => 'fa fa-user',
					),
					array(
						'type' => 'comments',
						'icon' => 'fa fa-comments',
					),
				),
				'title_field' => '{{{ type }}}',
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => esc_html__( 'Layout', 'jet-engine' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'inline',
				'options' => array(
					'inline' => __( 'Inline', 'jet-engine' ),
					'list'   => __( 'List', 'jet-engine' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_date',
			array(
				'label' => __( 'Date Settings', 'jet-engine' ),
			)
		);

		$this->add_control(
			'date_format',
			array(
				'label'       => esc_html__( 'Format', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'F j, Y',
				'description' => sprintf( '<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">%s</a>', __( 'Documentation on date and time formatting', 'jet-engine' ) ),
			)
		);

		$this->add_control(
			'date_link',
			array(
				'label'   => esc_html__( 'Link', 'jet-engine' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'archive',
				'options' => array(
					'archive' => __( 'Archive', 'jet-engine' ),
					'single'  => __( 'Post', 'jet-engine' ),
					'no-link' => __( 'None', 'jet-engine' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_author',
			array(
				'label' => __( 'Author Settings', 'jet-engine' ),
			)
		);

		$this->add_control(
			'author_link',
			array(
				'label'   => esc_html__( 'Link', 'jet-engine' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'archive',
				'options' => array(
					'archive' => __( 'Author Archives', 'jet-engine' ),
					'single'  => __( 'Post', 'jet-engine' ),
					'no-link' => __( 'None', 'jet-engine' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_comments',
			array(
				'label' => __( 'Comments Settings', 'jet-engine' ),
			)
		);

		$this->add_control(
			'comments_link',
			array(
				'label'   => esc_html__( 'Link', 'jet-engine' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'single',
				'options' => array(
					'single'  => __( 'Post', 'jet-engine' ),
					'no-link' => __( 'None', 'jet-engine' ),
				),
			)
		);

		$this->add_control(
			'zero_comments_format',
			array(
				'label'       => esc_html__( 'Zero Comments Format', 'jet-engine' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => '0',
			)
		);

		$this->add_control(
			'one_comment_format',
			array(
				'label'       => esc_html__( 'One Comments Format', 'jet-engine' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => '1',
			)
		);

		$this->add_control(
			'more_comments_format',
			array(
				'label'       => esc_html__( 'More Comments Format', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '%',
				'description' => __( 'Use % for comments number', 'jet-engine' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label'      => __( 'General', 'jet-engine' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'meta_alignment',
			array(
				'label'   => __( 'Alignment', 'jet-engine' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'flex-start',
				'options' => array(
					'left'    => array(
						'title' => __( 'Left', 'jet-engine' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'jet-engine' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => __( 'Right', 'jet-engine' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					$this->css_selector() => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_item_style',
			array(
				'label'      => __( 'Items', 'jet-engine' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'item_typography',
				'selector' => $this->css_selector( '__item' ),
			)
		);

		$this->start_controls_tabs( 'tabs_form_submit_style' );

		$this->start_controls_tab(
			'dynamic_item_normal',
			array(
				'label' => __( 'Normal', 'jet-engine' ),
			)
		);

		$this->add_control(
			'item_bg_color',
			array(
				'label'  => __( 'Background Color', 'jet-engine' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					$this->css_selector( '__item' ) => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'item_color',
			array(
				'label'  => __( 'Text Color', 'jet-engine' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					$this->css_selector( '__item' ) => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'dynamic_item_hover',
			array(
				'label' => __( 'Hover', 'jet-engine' ),
			)
		);

		$this->add_control(
			'item_bg_color_hover',
			array(
				'label'  => __( 'Background Color', 'jet-engine' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					$this->css_selector( '__item:hover' ) => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'item_color_hover',
			array(
				'label'  => __( 'Text Color', 'jet-engine' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					$this->css_selector( '__item:hover' ) => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'item_hover_border_color',
			array(
				'label' => __( 'Border Color', 'jet-engine' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'item_border_border!' => '',
				),
				'selectors' => array(
					$this->css_selector( '__item:hover' ) => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'item_padding',
			array(
				'label'      => __( 'Padding', 'jet-engine' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					$this->css_selector( '__item' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'item_margin',
			array(
				'label'      => __( 'Margin', 'jet-engine' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					$this->css_selector( '__item' ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'item_border',
				'label'          => __( 'Border', 'jet-engine' ),
				'placeholder'    => '1px',
				'selector'       => $this->css_selector( '__item' ),
			)
		);

		$this->add_responsive_control(
			'item_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-engine' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					$this->css_selector( '__item' ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'item_box_shadow',
				'selector' => $this->css_selector( '__item' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style',
			array(
				'label'      => __( 'Icon', 'jet-engine' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label' => __( 'Color', 'jet-engine' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					$this->css_selector( '__icon' ) => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => __( 'Size', 'jet-engine' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					$this->css_selector( '__icon' ) => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_gap',
			array(
				'label'      => __( 'Gap', 'jet-engine' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					$this->css_selector( '__icon' ) => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_item_val_style',
			array(
				'label'      => __( 'Items Value', 'jet-engine' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_item_val_style' );

		$this->start_controls_tab(
			'dynamic_item_val_normal',
			array(
				'label' => __( 'Normal', 'jet-engine' ),
			)
		);

		$this->add_control(
			'item_val_bg_color',
			array(
				'label'  => __( 'Background Color', 'jet-engine' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					$this->css_selector( '__item-val' ) => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'item_val_color',
			array(
				'label'  => __( 'Text Color', 'jet-engine' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					$this->css_selector( '__item-val' ) => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'dynamic_item_val_hover',
			array(
				'label' => __( 'Hover', 'jet-engine' ),
			)
		);

		$this->add_control(
			'item_val_bg_color_hover',
			array(
				'label'  => __( 'Background Color', 'jet-engine' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					$this->css_selector( '__item-val:hover' ) => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'item_val_color_hover',
			array(
				'label'  => __( 'Text Color', 'jet-engine' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					$this->css_selector( '__item-val:hover' ) => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'item_val_hover_border_color',
			array(
				'label' => __( 'Border Color', 'jet-engine' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'item_border_border!' => '',
				),
				'selectors' => array(
					$this->css_selector( '__item-val:hover' ) => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'item_val_padding',
			array(
				'label'      => __( 'Padding', 'jet-engine' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					$this->css_selector( '__item-val' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'item_val_margin',
			array(
				'label'      => __( 'Margin', 'jet-engine' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					$this->css_selector( '__item-val' ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'item_val_border',
				'label'          => __( 'Border', 'jet-engine' ),
				'placeholder'    => '1px',
				'selector'       => $this->css_selector( '__item-val' ),
			)
		);

		$this->add_responsive_control(
			'item_val_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-engine' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					$this->css_selector( '__item-val' ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'item_val_box_shadow',
				'selector' => $this->css_selector( '__item-val' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_prefix_style',
			array(
				'label'      => __( 'Prefix', 'jet-engine' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'prefix_l_gap',
			array(
				'label'      => __( 'Left Gap', 'jet-engine' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					$this->css_selector( '__prefix' ) => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'prefix_r_gap',
			array(
				'label'      => __( 'Right Gap', 'jet-engine' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					$this->css_selector( '__prefix' ) => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_suffix_style',
			array(
				'label'      => __( 'Suffix', 'jet-engine' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'suffix_l_gap',
			array(
				'label'      => __( 'Left Gap', 'jet-engine' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					$this->css_selector( '__suffix' ) => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'suffix_r_gap',
			array(
				'label'      => __( 'Right Gap', 'jet-engine' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					$this->css_selector( '__suffix' ) => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Returns CSS selector for nested element
	 *
	 * @param  [type] $el [description]
	 * @return [type]     [description]
	 */
	public function css_selector( $el = null ) {
		return sprintf( '{{WRAPPER}} .%1$s%2$s', $this->get_name(), $el );
	}

	/**
	 * Render meta block
	 *
	 * @return [type] [description]
	 */
	public function render_meta( $settings ) {

		if ( 'posts' !== jet_engine()->listings->data->get_listing_source() ) {
			return $this->wrong_source_notice();
		}

		$meta_items = isset( $settings['meta_items'] ) ? $settings['meta_items'] : array();

		if ( empty( $meta_items ) ) {
			return;
		}

		foreach ( $meta_items as $meta_item ) {
			$this->render_meta_item( $meta_item, $settings );
		}

	}

	/**
	 * Render single meta item
	 *
	 * @param  [type] $item     [description]
	 * @param  [type] $settings [description]
	 * @return [type]           [description]
	 */
	public function render_meta_item( $item, $settings ) {

		switch ( $item['type'] ) {
			case 'date':

				$this->render_date( $item, $settings );

				break;

			case 'comments':

				$this->render_comments( $item, $settings );

				break;

			case 'author':

				$this->render_author( $item, $settings );

				break;

			default:

				/**
				 * Render custom meta type.
				 */
				do_action( 'jet-engine/listings/dynamic-meta/render-type/' . $item['type'], $item, $settings, $this );

				break;

		}

	}

	/**
	 * Render post date meta item
	 *
	 * @param  [type] $item     [description]
	 * @param  [type] $settings [description]
	 * @return [type]           [description]
	 */
	public function render_date( $item, $settings ) {

		$this->open_item_wrap( 'date' );

		$this->render_icon( $item['icon'] );
		$this->render_prefix( $item['prefix'] );

		$format = ! empty( $settings['date_format'] ) ? esc_attr( $settings['date_format'] ) : 'F j, Y';
		$date   = sprintf(
			'<time datetime="%1$s">%2$s</time>',
			get_the_date( 'c' ),
			get_the_date( $format )
		);

		$link = ! empty( $settings['date_link'] ) ? esc_attr( $settings['date_link'] ) : 'archive';

		if ( 'no-link' === $link ) {
			printf( '<span class="%1$s__item-val">%2$s</span>', $this->get_name(), $date );
		} else {

			if ( 'archive' === $link ) {
				$url = get_month_link( get_the_date( 'Y' ), get_the_date( 'm' ) );
			} else {
				$url = get_permalink();
			}

			printf( '<a href="%3$s" class="%1$s__item-val">%2$s</a>', $this->get_name(), $date, $url );
		}

		$this->render_suffix( $item['suffix'] );

		$this->close_item_wrap( 'date' );

	}

	/**
	 * Render posts comments meta item
	 *
	 * @param  [type] $item     [description]
	 * @param  [type] $settings [description]
	 * @return [type]           [description]
	 */
	public function render_comments( $item, $settings ) {

		$this->open_item_wrap( 'comments' );

		$this->render_icon( $item['icon'] );
		$this->render_prefix( $item['prefix'] );

		$link = ! empty( $settings['comments_link'] ) ? esc_attr( $settings['comments_link'] ) : 'single';
		$zero = ! empty( $settings['zero_comments_format'] ) ? esc_attr( $settings['zero_comments_format'] ) : 0;
		$one  = ! empty( $settings['one_comment_format'] ) ? esc_attr( $settings['one_comment_format'] ) : 1;
		$more = ! empty( $settings['more_comments_format'] ) ? esc_attr( $settings['more_comments_format'] ) : '%';

		$comments_num = get_comments_number( $zero, $one, $more );

		if ( 'no-link' === $link ) {
			printf( '<span class="%1$s__item-val">%2$s</span>', $this->get_name(), $comments_num );
		} else {
			$url = get_comments_link();
			printf( '<a href="%3$s" class="%1$s__item-val">%2$s</a>', $this->get_name(), $comments_num, $url );
		}

		$this->render_suffix( $item['suffix'] );

		$this->close_item_wrap( 'comments' );

	}

	/**
	 * Render post author meta item
	 *
	 * @param  [type] $item [description]
	 * @param  [type] $settings [description]
	 * @return [type]       [description]
	 */
	public function render_author( $item, $settings ) {

		$this->open_item_wrap( 'author' );

		$this->render_icon( $item['icon'] );
		$this->render_prefix( $item['prefix'] );

		$link   = ! empty( $settings['author_link'] ) ? esc_attr( $settings['author_link'] ) : 'archive';
		$author = get_the_author();

		if ( 'no-link' === $link ) {
			printf( '<span class="%1$s__item-val">%2$s</span>', $this->get_name(), $author );
		} else {

			if ( 'archive' === $link ) {
				$id  = get_the_author_meta( 'ID' );
				$url = get_author_posts_url( $id );
			} else {
				$url = get_permalink();
			}

			printf( '<a href="%3$s" class="%1$s__item-val">%2$s</a>', $this->get_name(), $author, $url );
		}

		$this->render_suffix( $item['suffix'] );

		$this->close_item_wrap( 'author' );

	}

	/**
	 * Render opening meta item div
	 *
	 * @param  [type] $meta_name [description]
	 * @return [type]       [description]
	 */
	public function open_item_wrap( $meta_name ) {
		printf(
			'<div class="%1$s__%2$s %1$s__item">',
			$this->get_name(),
			$meta_name
		);
	}

	/**
	 * Render closing meta item div
	 *
	 * @param  [type] $meta_name [description]
	 * @return [type]       [description]
	 */
	public function close_item_wrap( $meta_name ) {
		echo '</div>';
	}

	/**
	 * Render meta item prefix
	 *
	 * @param  [type] $prefix [description]
	 * @return [type]       [description]
	 */
	public function render_prefix( $prefix = null ) {

		if ( empty( $prefix ) ) {
			return;
		}

		printf(
			'<span class="%2$s__prefix">%1$s</span>',
			$prefix,
			$this->get_name()
		);
	}

	/**
	 * Render meta item suffix
	 *
	 * @param  [type] $suffix [description]
	 * @return [type]       [description]
	 */
	public function render_suffix( $suffix = null ) {

		if ( empty( $suffix ) ) {
			return;
		}

		printf(
			'<span class="%2$s__suffix">%1$s</span>',
			$suffix,
			$this->get_name()
		);
	}

	/**
	 * Render icon tag for passed class
	 *
	 * @param  [type] $icon [description]
	 * @return [type]       [description]
	 */
	public function render_icon( $icon = null ) {

		if ( empty( $icon ) ) {
			return;
		}

		printf(
			'<i class="%1$s %2$s__icon"></i>',
			$icon,
			$this->get_name()
		);
	}

	/**
	 * Show notice if source is terms
	 */
	public function wrong_source_notice() {
		_e( 'Dynamic Terms widget allowed only for Posts listing source or in Post context', 'jet-engine' );
	}

	protected function render() {

		$base_class = $this->get_name();
		$settings   = $this->get_settings();
		$layout     = ! empty( $settings['layout'] ) ? $settings['layout'] : 'inline';

		printf( '<div class="%1$s jet-listing meta-layout-%2$s">', $base_class, $layout );

			do_action( 'jet-engine/listing/dynamic-terms/before-terms', $this );

			$this->render_meta( $settings );

			do_action( 'jet-engine/listing/dynamic-terms/after-terms', $this );

		echo '</div>';

	}

}
