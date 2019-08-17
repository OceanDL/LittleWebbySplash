<?php
namespace Elementor;

use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Listing_Dynamic_Field_Widget extends Widget_Base {

	public $show_field = true;

	public function get_name() {
		return 'jet-listing-dynamic-field';
	}

	public function get_title() {
		return __( 'Dynamic Field', 'jet-engine' );
	}

	public function get_icon() {
		return 'jet-engine-icon-1';
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
			'dynamic_field_source',
			array(
				'label'   => __( 'Source', 'jet-engine' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'object',
				'options' => jet_engine()->listings->data->get_field_sources(),
			)
		);

		$this->add_control(
			'dynamic_field_post_object',
			array(
				'label'   => __( 'Object Field', 'jet-engine' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'post_title',
				'options' => $this->get_object_fields(),
				'condition' => array(
					'dynamic_field_source' => 'object',
				),
			)
		);

		$meta_fields = $this->get_meta_fields_for_post_type();

		if ( ! empty( $meta_fields ) ) {

			$this->add_control(
				'dynamic_field_post_meta',
				array(
					'label'   => __( 'Meta Field', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => $meta_fields,
					'condition' => array(
						'dynamic_field_source' => 'meta',
					),
				)
			);

		}

		/**
		 * Add 3rd-party controls for sources
		 */
		do_action( 'jet-engine/listings/dynamic-field/source-controls', $this );


		$this->add_control(
			'dynamic_field_post_meta_custom',
			array(
				'label'       => __( 'Or enter meta field key', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'description' => __( 'Note: this filed will override Meta Field value', 'jet-engine' ),
				'condition'   => array(
					'dynamic_field_source!' => 'object',
				),
			)
		);

		$this->add_control(
			'field_icon',
			array(
				'label'       => __( 'Field Icon', 'jet-engine' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => '',
			)
		);

		$this->add_control(
			'field_tag',
			array(
				'label'   => __( 'HTML tag', 'jet-engine' ),
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
			'hide_if_empty',
			array(
				'label'        => esc_html__( 'Hide if value is empty', 'jet-engine' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-engine' ),
				'label_off'    => esc_html__( 'No', 'jet-engine' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'dynamic_field_filter',
			array(
				'label'        => esc_html__( 'Filter field output', 'jet-engine' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-engine' ),
				'label_off'    => esc_html__( 'No', 'jet-engine' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'filter_callback',
			array(
				'label'     => __( 'Callback', 'jet-engine' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => jet_engine()->listings->get_allowed_callbacks(),
				'condition' => array(
					'dynamic_field_filter' => 'yes',
				),
			)
		);

		$this->add_control(
			'date_format',
			array(
				'label'       => esc_html__( 'Format', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'F j, Y',
				'condition'   => array(
					'dynamic_field_filter' => 'yes',
					'filter_callback'      => array( 'date', 'date_i18n' ),
				),
				'description' => sprintf( '<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">%s</a>', __( 'Documentation on date and time formatting', 'jet-engine' ) ),
			)
		);

		$this->add_control(
			'num_dec_point',
			array(
				'label'       => esc_html__( 'Decimal point', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '.',
				'description' => __( 'Sets the separator for the decimal point', 'jet-engine' ),
				'condition'   => array(
					'dynamic_field_filter' => 'yes',
					'filter_callback'      => 'number_format',
				),
			)
		);

		$this->add_control(
			'num_thousands_sep',
			array(
				'label'       => esc_html__( 'Thousands separator', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => ',',
				'condition'   => array(
					'dynamic_field_filter' => 'yes',
					'filter_callback'      => 'number_format',
				),
			)
		);

		$this->add_control(
			'num_decimals',
			array(
				'label'       => esc_html__( 'Decimal points', 'jet-engine' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 10,
				'step'        => 1,
				'default'     => 2,
				'description' => __( 'Sets the number of visible decimal points', 'jet-engine' ),
				'condition'   => array(
					'dynamic_field_filter' => 'yes',
					'filter_callback'      => 'number_format',
				),
			)
		);

		$this->add_control(
			'related_list_is_single',
			array(
				'label'        => esc_html__( 'Single value', 'jet-engine' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-engine' ),
				'label_off'    => esc_html__( 'No', 'jet-engine' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'dynamic_field_filter' => 'yes',
					'filter_callback'      => 'jet_related_posts_list',
				),
			)
		);

		$this->add_control(
			'related_list_is_linked',
			array(
				'label'        => esc_html__( 'Add links to related posts', 'jet-engine' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-engine' ),
				'label_off'    => esc_html__( 'No', 'jet-engine' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'dynamic_field_filter' => 'yes',
					'filter_callback'      => 'jet_related_posts_list',
				),
			)
		);

		$this->add_control(
			'related_list_tag',
			array(
				'label'   => __( 'Related list HTML tag', 'jet-engine' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ul',
				'options' => array(
					'ul'   => 'UL',
					'ol'   => 'OL',
					'div'  => 'DIV',
				),
				'condition'   => array(
					'dynamic_field_filter' => 'yes',
					'filter_callback'      => 'jet_related_posts_list',
				),
			)
		);

		$this->add_control(
			'multiselect_delimiter',
			array(
				'label'       => esc_html__( 'Delimiter', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => ', ',
				'condition'   => array(
					'dynamic_field_filter' => 'yes',
					'filter_callback'      => array( 'jet_engine_render_multiselect', 'jet_related_posts_list', 'jet_engine_render_post_titles', 'jet_engine_render_checkbox_values' ),
				),
			)
		);

		/**
		 * Add custom controls for Callbacks
		 */
		do_action( 'jet-engine/listing/dynamic-field/callback-controls', $this );

		$this->add_control(
			'dynamic_field_custom',
			array(
				'label'        => esc_html__( 'Customize field output', 'jet-engine' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-engine' ),
				'label_off'    => esc_html__( 'No', 'jet-engine' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'dynamic_field_format',
			array(
				'label'       => __( 'Field format', 'jet-engine' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '%s',
				'description' => __( '%s will be replaced with field value', 'jet-engine' ),
				'condition' => array(
					'dynamic_field_custom' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_field_style',
			array(
				'label'      => __( 'Field', 'jet-engine' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'field_color',
			array(
				'label' => __( 'Color', 'jet-engine' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					$this->css_selector( '__content' ) => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'field_typography',
				'selector' => $this->css_selector( '__content' ),
			)
		);

		$this->add_control(
			'field_width',
			array(
				'label'   => __( 'Field content width', 'jet-engine' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => array(
					'auto' => __( 'Auto', 'jet-engine' ),
					'100%' => __( 'Fullwidth', 'jet-engine' ),
				),
				'selectors'  => array(
					$this->css_selector( ' .jet-listing-dynamic-field__inline-wrap' ) => 'width: {{VALUE}};',
					$this->css_selector( ' .jet-listing-dynamic-field__content' ) => 'width: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'field_display',
			array(
				'label'     => __( 'Display', 'jet-engine' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'inline',
				'options'   => array(
					'inline'    => __( 'Inline', 'jet-engine' ),
					'multiline' => __( 'Multiline', 'jet-engine' ),
				),
			)
		);

		$this->add_responsive_control(
			'field_alignment',
			array(
				'label'   => esc_html__( 'Widget Items Alignment', 'jet-engine' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'flex-start',
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Left', 'jet-engine' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-engine' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Right', 'jet-engine' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					$this->css_selector() => 'justify-content: {{VALUE}};',
				),
				'condition' => array(
					'field_display' => 'inline',
				),
				'description' => __( 'Icon and field value. Affects to single line field values.', 'jet-engine' ),
			)
		);

		$this->add_responsive_control(
			'content_alignment',
			array(
				'label'   => esc_html__( 'Field Content Alignment', 'jet-engine' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'jet-engine' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-engine' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-engine' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					$this->css_selector( '__content' ) => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					'field_display' => 'multiline',
				),
				'description' => __( 'Field value. Affects to multiline field values.', 'jet-engine' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'field_bg',
				'selector' => $this->css_selector( '.display-multiline' ) . ', ' . $this->css_selector( '.display-inline .jet-listing-dynamic-field__inline-wrap' ),
			)
		);

		$this->add_responsive_control(
			'field_padding',
			array(
				'label'      => __( 'Padding', 'jet-engine' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					$this->css_selector( '.display-multiline' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					$this->css_selector( '.display-inline .jet-listing-dynamic-field__inline-wrap' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'field_margin',
			array(
				'label'      => __( 'Margin', 'jet-engine' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					$this->css_selector( '.display-multiline' ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					$this->css_selector( '.display-inline .jet-listing-dynamic-field__inline-wrap' ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'field_border',
				'label'          => __( 'Border', 'jet-engine' ),
				'placeholder'    => '1px',
				'selector'       => $this->css_selector( '.display-multiline' ) . ', ' . $this->css_selector( '.display-inline .jet-listing-dynamic-field__inline-wrap' ),
			)
		);

		$this->add_responsive_control(
			'field_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-engine' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					$this->css_selector( '.display-multiline' ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					$this->css_selector( '.display-inline .jet-listing-dynamic-field__inline-wrap' ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'field_box_shadow',
				'selector' => $this->css_selector( '.display-multiline' ) . ', ' . $this->css_selector( '.display-inline .jet-listing-dynamic-field__inline-wrap' ),
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
			'section_misc_style',
			array(
				'label'      => __( 'Misc', 'jet-engine' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		/**
		 * Add custom controls for Callbacks
		 */
		do_action( 'jet-engine/listing/dynamic-field/misc-style-controls', $this );

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
	 * Returns object fileds option depends from source
	 *
	 * @return array
	 */
	public function get_object_fields() {

		$fields = array();

		switch ( jet_engine()->listings->data->get_listing_source() ) {

			case 'posts':

				$fields = array(
					'post_title'   => __( 'Title', 'jet-engine' ),
					'post_date'    => __( 'Date', 'jet-engine' ),
					'post_content' => __( 'Content', 'jet-engine' ),
					'post_excerpt' => __( 'Excerpt', 'jet-engine' ),
				);

				break;

			case 'terms':

				$fields = array(
					'name'        => __( 'Term name', 'jet-engine' ),
					'description' => __( 'Term description', 'jet-engine' ),
					'count'       => __( 'Posts count', 'jet-engine' ),
				);

				break;
		}

		return $fields;

	}

	/**
	 * Get meta fields for post type
	 *
	 * @return array
	 */
	public function get_meta_fields_for_post_type() {

		$result      = array();
		$meta_fields = jet_engine()->listings->data->get_listing_meta_fields();

		if ( ! $meta_fields ) {
			return $result;
		}

		foreach ( $meta_fields as $field ) {
			if ( 'repeater' !== $field['type'] ) {
				$result[ $field['name'] ] = $field['title'];
			}
		}

		return $result;

	}

	/**
	 * Render post/term field content
	 *
	 * @param  array $settings Widget settings.
	 * @return void
	 */
	public function render_field_content( $settings ) {

		$source = $settings['dynamic_field_source'];
		$result = '';

		switch ( $source ) {
			case 'object':

				$field  = $settings['dynamic_field_post_object'];
				$result = jet_engine()->listings->data->get_prop( $field );

				if ( 'post_content' === $field ) {
					$result = apply_filters( 'the_content', $result );
				}

				break;

			case 'meta':

				$field = $settings['dynamic_field_post_meta_custom'];

				if ( ! $field && isset( $settings['dynamic_field_post_meta'] ) ) {
					$field = $settings['dynamic_field_post_meta'];
				}

				if ( $field ) {
					$result = jet_engine()->listings->data->get_meta( $field );
				}

				break;

			default:

				$result = apply_filters( 'jet-engine/listings/dynamic-field/field-value', null, $settings );
				break;
		}

		if ( is_array( $result ) ) {
			$result = array_filter( $result );
		}

		if ( isset( $settings['hide_if_empty'] ) && 'yes' === $settings['hide_if_empty'] && empty( $result ) ) {
			$this->show_field = false;
			return;
		}

		$this->render_filtered_result( $result, $settings );

	}

	/**
	 * Render result with applied format from settings
	 *
	 * @param  [type] $result   [description]
	 * @param  [type] $settings [description]
	 * @return [type]           [description]
	 */
	public function render_filtered_result( $result, $settings ) {

		$is_filtered = isset( $settings['dynamic_field_filter'] ) ? $settings['dynamic_field_filter'] : false;

		if ( 'yes' === $is_filtered ) {
			$result = $this->apply_callback( $result, $settings );
		}

		if ( is_wp_error( $result ) ) {
			_e( '<strong>Warning:</strong> Error appears on callback applying. Please select other callback to filter field value.', 'jet-engine' );
			return;
		}

		$is_custom = isset( $settings['dynamic_field_custom'] ) ? $settings['dynamic_field_custom'] : false;

		if ( $is_custom && ! empty( $settings['dynamic_field_format'] ) ) {
			$result = sprintf( $settings['dynamic_field_format'], $result );
			$result = do_shortcode( $result );
		}

		echo $result;

	}

	public function apply_callback( $result, $settings ) {

		$callback = isset( $settings['filter_callback'] ) ? $settings['filter_callback'] : '';

		if ( ! $callback ) {
			return;
		}

		if ( ! is_callable( $callback ) ) {
			return;
		}

		$args = array();

		switch ( $callback ) {

			case 'date':
			case 'date_i18n':

				if ( ! $this->is_valid_timestamp( $result ) ) {
					$result = strtotime( $result );
				}

				$format = ! empty( $settings['date_format'] ) ? $settings['date_format'] : 'F j, Y';
				$args   = array( $format, $result );

				break;

			case 'number_format':

				$dec_point     = isset( $settings['num_dec_point'] ) ? $settings['num_dec_point'] : '.';
				$thousands_sep = isset( $settings['num_thousands_sep'] ) ? $settings['num_thousands_sep'] : ',';
				$decimals      = isset( $settings['num_decimals'] ) ? $settings['num_decimals'] : 2;
				$args          = array( $result, $decimals, $dec_point, $thousands_sep );

				break;

			case 'wp_get_attachment_image':

				$args = array( $result, 'full' );

				break;

			case 'jet_engine_render_multiselect':
			case 'jet_engine_render_post_titles':
			case 'jet_engine_render_checkbox_values':

				$delimiter = isset( $settings['multiselect_delimiter'] ) ? $settings['multiselect_delimiter'] : ', ';
				$args      = array( $result, $delimiter );

				break;

			case 'jet_related_posts_list':

				$tag       = isset( $settings['related_list_tag'] ) ? $settings['related_list_tag'] : '';
				$is_linked = isset( $settings['related_list_is_linked'] ) ? $settings['related_list_is_linked'] : '';
				$is_single = isset( $settings['related_list_is_single'] ) ? $settings['related_list_is_single'] : '';
				$delimiter = isset( $settings['multiselect_delimiter'] ) ? $settings['multiselect_delimiter'] : ', ';
				$is_linked = filter_var( $is_linked, FILTER_VALIDATE_BOOLEAN );
				$is_single = filter_var( $is_single, FILTER_VALIDATE_BOOLEAN );
				$args      = array( $result, $tag, $is_single, $is_linked, $delimiter );

				break;

			default:

				$args = apply_filters(
					'jet-engine/listing/dynamic-field/callback-args',
					array( $result ),
					$callback,
					$settings,
					$this
				);

				break;
		}

		return call_user_func_array( $callback, $args );

	}

	/**
	 * Check if is valid timestamp
	 *
	 * @param  [type]  $timestamp [description]
	 * @return boolean            [description]
	 */
	public function is_valid_timestamp( $timestamp ) {
		return ( ( string ) ( int ) $timestamp === $timestamp) && ( $timestamp <= PHP_INT_MAX ) && ($timestamp >= ~PHP_INT_MAX );
	}

	protected function render() {

		$this->show_field = true;

		$base_class    = $this->get_name();
		$settings      = $this->get_settings();
		$tag           = ! empty( $settings['field_tag'] ) ? esc_attr( $settings['field_tag'] ) : 'div';
		$field_icon    = ! empty( $settings['field_icon'] ) ? esc_attr( $settings['field_icon'] ) : false;
		$field_display = ! empty( $settings['field_display'] ) ? esc_attr( $settings['field_display'] ) : 'inline';

		ob_start();

		printf( '<%1$s class="%2$s %3$s jet-listing">', $tag, $base_class, 'display-' . $field_display );

			if ( 'inline' === $field_display ) {
				printf( '<div class="%s__inline-wrap">', $base_class );
			}

			if ( $field_icon ) {
				printf( '<i class="%1$s %2$s__icon"></i>', $field_icon, $base_class );
			}

			do_action( 'jet-engine/listing/dynamic-field/before-field', $this );

			printf( '<div class="%s__content">', $base_class );
				$this->render_field_content( $settings );
			echo '</div>';

			do_action( 'jet-engine/listing/dynamic-field/after-field', $this );

			if ( 'inline' === $field_display ) {
				echo '</div>';
			}

		printf( '</%s>', $tag );

		$content = ob_get_clean();

		if ( $this->show_field ) {
			echo $content;
		}

	}

}
