<?php
namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Listing_Dynamic_Repeater_Widget extends Widget_Base {

	public function get_name() {
		return 'jet-listing-dynamic-repeater';
	}

	public function get_title() {
		return __( 'Dynamic Repeater', 'jet-engine' );
	}

	public function get_icon() {
		return 'jet-engine-icon-5';
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
			'repeater_notice',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => __( '<b>Note</b> this widget could process only repeater meta fields created with JetThemeCore or ACF plugins', 'jet-engine' ),
			)
		);

		$repeater_fields = $this->get_repeater_fields();

		$this->add_control(
			'dynamic_field_source',
			array(
				'label'   => __( 'Source', 'jet-engine' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $repeater_fields,
			)
		);

		/**
		 * Add 3rd-party controls for sources
		 */
		do_action( 'jet-engine/listings/dynamic-repeater/source-controls', $this );

		$this->add_control(
			'dynamic_field_format',
			array(
				'label'       => __( 'Item format', 'jet-engine' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '<span>%name%</span>',
				'description' => __( 'You can render repeater fields values with macros %repeater field name%', 'jet-engine' )
			)
		);

		$this->add_control(
			'item_tag',
			array(
				'label'   => __( 'Item HTML tag', 'jet-engine' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'div',
				'options' => array(
					'div'  => 'DIV',
					'tr'   => 'tr',
					'li'   => 'li',
				),
			)
		);

		$this->add_control(
			'items_delimiter',
			array(
				'label'   => __( 'Items delimiter', 'jet-engine' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$this->add_control(
			'dynamic_field_before',
			array(
				'label'       => __( 'Before items markup', 'jet-engine' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'description' => __( 'HTML to output before repeater items', 'jet-engine' )
			)
		);

		$this->add_control(
			'dynamic_field_after',
			array(
				'label'       => __( 'After items markup', 'jet-engine' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'description' => __( 'HTML to output after repeater items', 'jet-engine' )
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_general_style',
			array(
				'label'      => __( 'General', 'jet-engine' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'items_direction',
			array(
				'label'   => __( 'Direction', 'jet-engine' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'flex-start',
				'options' => array(
					'row'    => array(
						'title' => __( 'Horizontal', 'jet-engine' ),
						'icon' => 'eicon-ellipsis-h',
					),
					'column' => array(
						'title' => __( 'Vertical', 'jet-engine' ),
						'icon' => 'eicon-editor-list-ul',
					),
				),
				'label_block' => false,
				'selectors'  => array(
					$this->css_selector( '__items' ) => 'flex-direction: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'items_alignment',
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
				),
				'selectors'  => array(
					$this->css_selector( '__items' ) => 'justify-content: {{VALUE}};',
				),
			)
		);


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'general_typography',
				'selector' => $this->css_selector( '__item > *' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_items_style',
			array(
				'label'      => __( 'Items', 'jet-engine' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'fixed_size',
			array(
				'label'        => esc_html__( 'Fixed item size', 'jet-engine' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-engine' ),
				'label_off'    => esc_html__( 'No', 'jet-engine' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_responsive_control(
			'item_width',
			array(
				'label'      => esc_html__( 'Item Width', 'jet-engine' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 15,
						'max' => 150,
					),
				),
				'condition' => array(
					'fixed_size' => 'yes',
				),
				'selectors'  => array(
					$this->css_selector( '__item > *' ) => 'display: flex; width: {{SIZE}}{{UNIT}}; justify-content: center;',
				),
			)
		);

		$this->add_responsive_control(
			'item_height',
			array(
				'label'      => esc_html__( 'Item Height', 'jet-engine' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 15,
						'max' => 150,
					),
				),
				'condition' => array(
					'fixed_size' => 'yes',
				),
				'selectors'  => array(
					$this->css_selector( '__item > *' ) => 'height: {{SIZE}}{{UNIT}}; display: flex; align-items: center;',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_item_style' );

		$this->start_controls_tab(
			'tabs_item_style_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-engine' ),
			)
		);

		$this->add_control(
			'item_color',
			array(
				'label' => __( 'Color', 'jet-engine' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					$this->css_selector( '__item > *' ) => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'item_background_color',
			array(
				'label' => __( 'Background color', 'jet-engine' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					$this->css_selector( '__item > *' ) => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_item_style_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-engine' ),
			)
		);

		$this->add_control(
			'item_color_hover',
			array(
				'label' => __( 'Color', 'jet-engine' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					$this->css_selector( '__item > *:hover' ) => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'item_background_color_hover',
			array(
				'label' => __( 'Background color', 'jet-engine' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					$this->css_selector( '__item > *:hover' ) => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'item_border_color_hover',
			array(
				'label' => __( 'Border Color', 'jet-engine' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'item_border_border!' => '',
				),
				'selectors' => array(
					$this->css_selector( '__item > *:hover' ) => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'item_border',
				'label'          => __( 'Border', 'jet-engine' ),
				'placeholder'    => '1px',
				'selector'       => $this->css_selector( '__item > *' ),
			)
		);

		$this->add_responsive_control(
			'item_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-engine' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					$this->css_selector( '__item > *' ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'item_box_shadow',
				'selector' => $this->css_selector( '__item > *' ),
			)
		);

		$this->add_control(
			'item_padding',
			array(
				'label'      => __( 'Padding', 'jet-engine' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					$this->css_selector( '__item > *' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'fixed_size!' => 'yes',
				),
			)
		);

		$this->add_control(
			'item_margin',
			array(
				'label'      => __( 'Margin', 'jet-engine' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					$this->css_selector( '__item > *' ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
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
			'delimiter_margin',
			array(
				'label'      => __( 'Margin', 'jet-engine' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					$this->css_selector( '__delimiter' ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	public function get_repeater_fields() {

		$result      = array();
		$meta_fields = jet_engine()->listings->data->get_listing_meta_fields();

		if ( ! empty( $meta_fields ) ) {
			foreach ( $meta_fields as $field ) {
				if ( 'repeater' === $field['type'] ) {
					$result[ $field['name'] ] = $field['title'];
				}
			}
		}

		return apply_filters( 'jet-engine/listings/dynamic-repeater/fields', $result );

	}

	/**
	 * Return saved fields from post/term meta
	 *
	 * @param  [type] $settings [description]
	 * @return [type]           [description]
	 */
	public function get_saved_fields( $settings ) {

		$saved = apply_filters( 'jet-engine/listings/dynamic-repeater/pre-get-saved', false, $settings );

		if ( $saved ) {
			return $saved;
		}

		$source = isset( $settings['dynamic_field_source'] ) ? $settings['dynamic_field_source'] : false;
		return jet_engine()->listings->data->get_meta( $source );

	}

	/**
	 * Render field content
	 *
	 * @return [type] [description]
	 */
	public function render_repeater_items( $settings ) {

		global $post;

		$fields       = $this->get_saved_fields( $settings );
		$format       = isset( $settings['dynamic_field_format'] ) ? $settings['dynamic_field_format'] : false;
		$delimiter    = isset( $settings['items_delimiter'] ) ? $settings['items_delimiter'] : false;
		$item_tag     = isset( $settings['item_tag'] ) ? $settings['item_tag'] : 'div';
		$items_before = isset( $settings['dynamic_field_before'] ) ? $settings['dynamic_field_before'] : '';
		$items_after  = isset( $settings['dynamic_field_after'] ) ? $settings['dynamic_field_after'] : '';
		$is_first     = true;

		if ( empty( $fields ) ) {
			return;
		}

		$base_class = $this->get_name();

		printf( '<div class="%s__items">', $base_class );

		if ( $items_before ) {
			echo $items_before;
		}

		foreach ( $fields as $field ) {

			$item_content = preg_replace_callback(
				'/\%(([a-zA-Z0-9_-]+)(\|([a-zA-Z0-9\(\)_-]+))*)\%/',
				function( $matches ) use ( $field ) {

					if ( ! isset( $matches[2] ) ) {
						return $matches[0];
					}

					if ( ! isset( $field[ $matches[2] ] ) ) {
						return $matches[0];
					} else {
						if ( isset( $matches[4] ) ) {
							return jet_engine()->listings->filters->apply_filters(
								$field[ $matches[2] ], $matches[4]
							);
						} else {
							return $field[ $matches[2] ];
						}
					}

				},
				$format
			);

			if ( $delimiter && ! $is_first ) {
				printf(
					'<div class="%1$s__delimiter">%2$s</div>',
					$base_class,
					$delimiter
				);
			}

			if ( false === strpos( $item_content, '<' ) ) {
				$item_content = '<div>' . $item_content. '</div>';
			}

			printf(
				'<%3$s class="%1$s__item">%2$s</%3$s>',
				$base_class,
				$item_content,
				$item_tag
			);

			$is_first = false;

		}

		if ( $items_after ) {
			echo $items_after;
		}

		echo '</div>';

	}

	protected function render() {

		$base_class = $this->get_name();
		$settings   = $this->get_settings();

		printf( '<div class="%s jet-listing">', $base_class );

			do_action( 'jet-engine/listing/dynamic-repeater/before-field', $this );

			$this->render_repeater_items( $settings );

			do_action( 'jet-engine/listing/dynamic-repeater/after-field', $this );

		echo '</div>';

	}

}
