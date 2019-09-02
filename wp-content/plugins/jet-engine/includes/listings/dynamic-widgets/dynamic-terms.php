<?php
namespace Elementor;

use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Listing_Dynamic_Terms_Widget extends Widget_Base {

	private $source = false;

	public function get_name() {
		return 'jet-listing-dynamic-terms';
	}

	public function get_title() {
		return __( 'Dynamic Terms', 'jet-engine' );
	}

	public function get_icon() {
		return 'jet-engine-icon-6';
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

		$this->add_control(
			'from_tax',
			array(
				'label'   => __( 'From taxonomy', 'jet-engine' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_taxonomies_for_options(),
			)
		);

		$this->add_control(
			'show_all_terms',
			array(
				'label'        => esc_html__( 'Show all terms', 'jet-engine' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-engine' ),
				'label_off'    => esc_html__( 'No', 'jet-engine' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'terms_num',
			array(
				'label'   => esc_html__( 'Terms number to show', 'jet-engine' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 1,
				'max'     => 20,
				'step'    => 1,
				'condition' => array(
					'show_all_terms!' => 'yes',
				),
			)
		);

		$this->add_control(
			'terms_delimiter',
			array(
				'label'       => __( 'Delimiter', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => ',',
			)
		);

		$this->add_control(
			'terms_linked',
			array(
				'label'        => esc_html__( 'Linked terms', 'jet-engine' ),
				'description'  => __( 'Terms labels are linked to term archive page', 'jet-engine' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-engine' ),
				'label_off'    => esc_html__( 'No', 'jet-engine' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'terms_icon',
			array(
				'label'       => __( 'Terms Icon', 'jet-engine' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => '',
			)
		);

		$this->add_control(
			'terms_prefix',
			array(
				'label'       => __( 'Text before terms list', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
			)
		);

		$this->add_control(
			'terms_suffix',
			array(
				'label'       => __( 'Text after terms list', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
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
			'terms_alignment',
			array(
				'label'   => __( 'Alignment', 'jet-engine' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'left',
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
			'section_link_style',
			array(
				'label'      => __( 'Labels', 'jet-engine' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'link_typography',
				'selector' => $this->css_selector( '__link' ),
			)
		);

		$this->start_controls_tabs( 'tabs_form_submit_style' );

		$this->start_controls_tab(
			'dynamic_link_normal',
			array(
				'label' => __( 'Normal', 'jet-engine' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'link_bg',
				'selector' => $this->css_selector( '__link' ),
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'  => __( 'Text Color', 'jet-engine' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					$this->css_selector( '__link' ) => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'dynamic_link_hover',
			array(
				'label' => __( 'Hover', 'jet-engine' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'link_bg_hover',
				'selector' => $this->css_selector( '__link:hover' ),
			)
		);

		$this->add_control(
			'link_color_hover',
			array(
				'label'  => __( 'Text Color', 'jet-engine' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					$this->css_selector( '__link:hover' ) => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'link_hover_border_color',
			array(
				'label' => __( 'Border Color', 'jet-engine' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'link_border_border!' => '',
				),
				'selectors' => array(
					$this->css_selector( '__link:hover' ) => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'link_padding',
			array(
				'label'      => __( 'Padding', 'jet-engine' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					$this->css_selector( '__link' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'link_margin',
			array(
				'label'      => __( 'Margin', 'jet-engine' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					$this->css_selector( '__link' ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'link_border',
				'label'          => __( 'Border', 'jet-engine' ),
				'placeholder'    => '1px',
				'selector'       => $this->css_selector( '__link' ),
			)
		);

		$this->add_responsive_control(
			'link_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-engine' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					$this->css_selector( '__link' ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'link_box_shadow',
				'selector' => $this->css_selector( '__link' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_delimiter_style',
			array(
				'label'      => __( 'Delimiter', 'jet-engine' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'delimiter_color',
			array(
				'label' => __( 'Color', 'jet-engine' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					$this->css_selector( '__delimiter' ) => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'delimiter_size',
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
					$this->css_selector( '__delimiter' ) => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'delimiter_l_gap',
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
					$this->css_selector( '__delimiter' ) => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'delimiter_r_gap',
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
					$this->css_selector( '__delimiter' ) => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_prefix_style',
			array(
				'label'      => __( 'Text before', 'jet-engine' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'prefix_typography',
				'selector' => $this->css_selector( '__prefix' ),
			)
		);

		$this->add_control(
			'prefix_color',
			array(
				'label' => __( 'Color', 'jet-engine' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					$this->css_selector( '__prefix' ) => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'prefix_gap',
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
					$this->css_selector( '__prefix' ) => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_suffix_style',
			array(
				'label'      => __( 'Text after', 'jet-engine' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'suffix_typography',
				'selector' => $this->css_selector( '__suffix' ),
			)
		);

		$this->add_control(
			'suffix_color',
			array(
				'label' => __( 'Color', 'jet-engine' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					$this->css_selector( '__suffix' ) => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'suffix_gap',
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
					$this->css_selector( '__suffix' ) => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}


	public function get_taxonomies_for_options() {

		$source = jet_engine()->listings->data->get_listing_source();

		if ( 'posts' !== $source ) {
			return array();
		}

		$taxonomies = apply_filters( 'jet-engine/listings/dynamic-terms/taxonomies-list', array() );

		if ( empty( $taxonomies ) ) {
			$taxonomies = get_object_taxonomies( jet_engine()->listings->data->get_listing_post_type() );
		}

		if ( empty( $taxonomies ) ) {
			return array();
		}

		$result = array();

		foreach ( $taxonomies as $taxonomy ) {

			$tax = get_taxonomy( $taxonomy );

			if ( ! $tax ) {
				continue;
			}

			$result[ $taxonomy ] = $tax->label;
		}

		return $result;

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
	 * Render taxonomies list
	 *
	 * @return [type] [description]
	 */
	public function render_taxonomies_list( $settings ) {

		if ( 'posts' !== jet_engine()->listings->data->get_listing_source() ) {
			return $this->wrong_source_notice();
		}

		$tax = isset( $settings['from_tax'] ) ? esc_attr( $settings['from_tax'] ) : false;

		if ( ! $tax ) {
			return;
		}

		$terms = wp_get_post_terms( get_the_ID(), $tax );

		if ( empty( $terms ) ) {
			return;
		}

		$show_all = isset( $settings['show_all_terms'] ) ? $settings['show_all_terms'] : 'yes';

		if ( 'yes' !== $show_all ) {
			$num   = isset( $settings['terms_num'] ) ? absint( $settings['terms_num'] ) : 1;
			$terms = array_slice( $terms, 0, $num );
		}

		$add_delimiter = false;
		$delimiter     = '';

		if ( ! empty( $settings['terms_delimiter'] ) ) {
			$add_delimiter = true;
		}

		if ( ! empty( $settings['terms_icon'] ) ) {
			$this->render_icon( $settings['terms_icon'] );
		}

		if ( ! empty( $settings['terms_prefix'] ) ) {
			printf( '<span class="%2$s__prefix">%1$s</span>', $settings['terms_prefix'], $this->get_name() );
		}

		$item_format = '<a href="%1$s" class="%3$s__link">%2$s</a>';
		$is_linked   = isset( $settings['terms_linked'] ) ? $settings['terms_linked'] : false;
		$is_linked   = filter_var( $is_linked, FILTER_VALIDATE_BOOLEAN );

		if ( ! $is_linked ) {
			$item_format = '<span class="%3$s__link">%2$s</span>';
		}

		foreach ( $terms as $term ) {

			if ( $add_delimiter ) {
				echo $delimiter;
				$delimiter = sprintf(
					'<span class="%2$s__delimiter">%1$s</span>',
					$settings['terms_delimiter'],
					$this->get_name()
				);
			}

			/**
			 * Filter term name befor printing
			 *
			 * @var string
			 */
			$name = apply_filters( 'jet-engine/listings/dynamic-terms/term-name', $term->name, $term, $this );

			printf( $item_format, get_term_link( $term, $tax ), $name, $this->get_name() );

		}

		if ( ! empty( $settings['terms_suffix'] ) ) {
			printf( '<span class="%2$s__suffix">%1$s</span>', $settings['terms_suffix'], $this->get_name() );
		}

	}

	public function render_icon( $icon ) {
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

		printf( '<div class="%1$s jet-listing">', $base_class );

			do_action( 'jet-engine/listing/dynamic-terms/before-terms', $this );

			$this->render_taxonomies_list( $settings );

			do_action( 'jet-engine/listing/dynamic-terms/after-terms', $this );

		echo '</div>';

	}

}
