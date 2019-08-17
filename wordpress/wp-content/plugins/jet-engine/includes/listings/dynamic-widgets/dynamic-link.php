<?php
namespace Elementor;

use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Listing_Dynamic_Link_Widget extends Widget_Base {

	public function get_name() {
		return 'jet-listing-dynamic-link';
	}

	public function get_title() {
		return __( 'Dynamic Link', 'jet-engine' );
	}

	public function get_icon() {
		return 'jet-engine-icon-3';
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

		$meta_fields = $this->get_meta_fields_for_post_type();

		$this->add_control(
			'dynamic_link_source',
			array(
				'label'   => __( 'Source', 'jet-engine' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '_permalink',
				'options' => $meta_fields,
			)
		);

		/**
		 * Add 3rd-party controls for sources
		 */
		do_action( 'jet-engine/listings/dynamic-link/source-controls', $this );

		$this->add_control(
			'dynamic_link_source_custom',
			array(
				'label'       => __( 'Or enter post meta field key', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'description' => __( 'Note: this filed will override Meta Field value', 'jet-engine' ),
			)
		);

		$this->add_control(
			'link_label',
			array(
				'label'     => __( 'Label', 'jet-engine' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Read More', 'jet-engine' ),
				'description' => __( 'You can use next macros in this field: ', 'jet-engine' ) . jet_engine()->listings->macros->verbose_macros_list(),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'link_icon',
			array(
				'label'       => __( 'Field Icon', 'jet-engine' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => '',
			)
		);

		$this->add_control(
			'link_wrapper_tag',
			array(
				'label'   => __( 'HTML wrapper', 'jet-engine' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'div',
				'options' => array(
					'div'  => 'DIV',
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'span' => 'SPAN',
				),
			)
		);

		$this->add_control(
			'open_in_new',
			array(
				'label'        => esc_html__( 'Open in new window', 'jet-engine' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-engine' ),
				'label_off'    => esc_html__( 'No', 'jet-engine' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'rel_attr',
			array(
				'label'   => __( 'Add "rel" attr', 'jet-engine' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''           => __( 'No', 'jet-engine' ),
					'alternate'  => __( 'Alternate', 'jet-engine' ),
					'author'     => __( 'Author', 'jet-engine' ),
					'bookmark'   => __( 'Bookmark', 'jet-engine' ),
					'external'   => __( 'External', 'jet-engine' ),
					'help'       => __( 'Help', 'jet-engine' ),
					'license'    => __( 'License', 'jet-engine' ),
					'next'       => __( 'Next', 'jet-engine' ),
					'nofollow'   => __( 'Nofollow', 'jet-engine' ),
					'noreferrer' => __( 'Noreferrer', 'jet-engine' ),
					'noopener'   => __( 'Noopener', 'jet-engine' ),
					'prev'       => __( 'Prev', 'jet-engine' ),
					'search'     => __( 'Search', 'jet-engine' ),
					'tag'        => __( 'Tag', 'jet-engine' ),
				),
			)
		);

		$this->add_responsive_control(
			'link_alignment',
			array(
				'label'   => __( 'Alignment', 'jet-engine' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'flex-start',
				'options' => array(
					'flex-start'    => array(
						'title' => __( 'Left', 'jet-engine' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'jet-engine' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end' => array(
						'title' => __( 'Right', 'jet-engine' ),
						'icon'  => 'fa fa-align-right',
					),
					'stretch' => array(
						'title' => __( 'Fullwidth', 'jet-engine' ),
						'icon' => 'fa fa-align-justify',
					),
				),
				'selectors'  => array(
					$this->css_selector( '__link' ) => 'align-self: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_link_style',
			array(
				'label'      => __( 'General', 'jet-engine' ),
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

		$this->add_control(
			'link_icon_color',
			array(
				'label'  => __( 'Icon Color', 'jet-engine' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					$this->css_selector( '__icon' ) => 'color: {{VALUE}}',
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
			'link_icon_color_hover',
			array(
				'label'  => __( 'Icon Color', 'jet-engine' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					$this->css_selector( '__link:hover .jet-listing-dynamic-link__icon' ) => 'color: {{VALUE}}',
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

		$this->add_control(
			'link_icon_position',
			array(
				'label'   => __( 'Icon Position', 'jet-engine' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 1,
				'options' => array(
					1 => __( 'Before Label', 'jet-engine' ),
					3 => __( 'After Label', 'jet-engine' )
				),
				'selectors'  => array(
					$this->css_selector( '__icon' ) => 'order: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'link_icon_size',
			array(
				'label'      => __( 'Icon Size', 'jet-engine' ),
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
			'link_icon_gap_right',
			array(
				'label'      => __( 'Icon Gap', 'jet-engine' ),
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
				'condition' => array(
					'link_icon_position' => '1',
				),
			)
		);

		$this->add_responsive_control(
			'link_icon_gap_left',
			array(
				'label'      => __( 'Icon Gap', 'jet-engine' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					$this->css_selector( '__icon' ) => 'margin-left: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'link_icon_position' => '3',
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
	 * Get meta fields for post type
	 *
	 * @return array
	 */
	public function get_meta_fields_for_post_type() {

		$default = array(
			'_permalink' => __( 'Permalink', 'jet-engine' ),
		);

		$result      = array();
		$meta_fields = jet_engine()->listings->data->get_listing_meta_fields();

		if ( ! empty( $meta_fields ) ) {
			foreach ( $meta_fields as $field ) {
				if ( 'repeater' !== $field['type'] ) {
					$result[ $field['name'] ] = $field['title'];
				}
			}
		}

		return apply_filters(
			'jet-engine/listings/dynamic-link/fields',
			array_merge( $default, $result )
		);

	}

	/**
	 * Render link tag
	 *
	 * @param  [type] $settings   [description]
	 * @param  [type] $base_class [description]
	 * @return [type]             [description]
	 */
	public function render_link( $settings, $base_class ) {

		$format = '<a href="%1$s" class="%2$s__link"%5$s%6$s>%3$s%4$s</a>';
		$source = $settings['dynamic_link_source'];
		$custom = $settings['dynamic_link_source_custom'];

		$url = apply_filters(
			'jet-engine/listings/dynamic-link/custom-url',
			false,
			$settings
		);

		if ( ! $url ) {
			if ( $custom ) {
				$url = jet_engine()->listings->data->get_meta( $custom );
			} elseif ( '_permalink' === $source ) {
				$url = jet_engine()->listings->data->get_current_object_permalink();
			} elseif ( $source ) {
				$url = jet_engine()->listings->data->get_meta( $source );
			}
		}

		$label = $settings['link_label'];
		$icon  = $settings['link_icon'];

		if ( $label ) {
			$label = jet_engine()->listings->macros->do_macros( $label, $url );
			$label = sprintf( '<span class="%1$s__label">%2$s</span>', $base_class, $label );
		}

		if ( $icon ) {
			$icon = sprintf( '<i class="%1$s__icon %2$s"></i>', $base_class, $icon );
		}

		if ( is_wp_error( $url ) ) {
			echo $url->get_error_message();
			return;
		}

		$open_in_new = isset( $settings['open_in_new'] ) ? $settings['open_in_new'] : '';
		$rel_attr    = isset( $settings['rel_attr'] ) ? esc_attr( $settings['rel_attr'] ) : '';
		$rel         = '';
		$target      = '';

		if ( $rel_attr ) {
			$rel = sprintf( ' rel="%s"', $rel_attr );
		}

		if ( $open_in_new ) {
			$target = ' target="_blank"';
		}

		printf( $format, $url, $base_class, $icon, $label, $rel, $target );

	}

	protected function render() {

		$base_class = $this->get_name();
		$settings   = $this->get_settings();
		$tag        = isset( $settings['link_wrapper_tag'] ) ? $settings['link_wrapper_tag'] : 'div';

		printf( '<%2$s class="%1$s jet-listing">', $base_class, $tag );

			do_action( 'jet-engine/listing/dynamic-link/before-field', $this );

			$this->render_link( $settings, $base_class );

			do_action( 'jet-engine/listing/dynamic-link/after-field', $this );

		printf( '</%s>', $tag );

	}

}
