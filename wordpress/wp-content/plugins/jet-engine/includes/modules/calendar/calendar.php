<?php
namespace Elementor;

use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Elementor\Jet_Listing_Calendar_Widget' ) ) {

	class Jet_Listing_Calendar_Widget extends Jet_Listing_Grid_Widget {

		public $is_first  = false;
		public $data      = false;
		public $first_day = false;
		public $last_day  = false;

		public function get_name() {
			return 'jet-listing-calendar';
		}

		public function get_title() {
			return __( 'Listing Calendar', 'jet-engine' );
		}

		public function get_icon() {
			return 'jet-engine-icon-7';
		}

		public function get_categories() {
			return array( 'jet-listing-elements' );
		}

		protected function _register_controls() {

			$this->register_general_settings();
			$this->register_query_settings();
			$this->register_visibility_settings();
			$this->register_style_settings();

		}

		public function register_general_settings() {

			$this->start_controls_section(
				'section_general',
				array(
					'label' => __( 'General', 'jet-engine' ),
				)
			);

			$this->add_control(
				'lisitng_id',
				array(
					'label'   => __( 'Listing', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => $this->get_listings(),
				)
			);

			$this->add_control(
				'group_by',
				array(
					'label'   => __( 'Group posts by', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'post_date',
					'options' => array(
						'post_date' => __( 'Post publication date', 'jet-engine' ),
						'post_mod'  => __( 'Post modification date', 'jet-engine' ),
						'meta_date' => __( 'Date from custom field', 'jet-engine' ),
					),
				)
			);

			$this->add_control(
				'group_by_key',
				array(
					'label'       => esc_html__( 'Meta field name', 'jet-engine' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'label_block' => true,
					'description' => __( 'This field must contain date to group posts by. Works only in "Save as timestamp" option for meta field is active', 'jet-engine' ),
					'condition'   => array(
						'group_by' => 'meta_date'
					),
				)
			);

			$this->add_control(
				'week_days_format',
				array(
					'label'   => __( 'Week days format', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'short',
					'options' => array(
						'full'    => __( 'Full', 'jet-engine' ),
						'short'   => __( 'Short', 'jet-engine' ),
						'initial' => __( 'Initial letter', 'jet-engine' ),
					),
				)
			);

			$this->end_controls_section();

		}

		/**
		 * Register style settings
		 * @return [type] [description]
		 */
		public function register_style_settings() {

			$this->start_controls_section(
				'section_caption_style',
				array(
					'label'      => esc_html__( 'Caption', 'jet-engine' ),
					'tab'        => Controls_Manager::TAB_STYLE,
					'show_label' => false,
				)
			);

			$this->add_control(
				'caption_layout',
				array(
					'label'   => __( 'Layout', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'layout-1',
					'options' => array(
						'layout-1' => __( 'Layout 1', 'jet-engine' ),
						'layout-2' => __( 'Layout 2', 'jet-engine' ),
						'layout-3' => __( 'Layout 3', 'jet-engine' ),
						'layout-4' => __( 'Layout 4', 'jet-engine' ),
					),
				)
			);

			$this->add_control(
				'caption_bg_color',
				array(
					'label' => esc_html__( 'Background Color', 'jet-engine' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-caption' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'caption_txt_color',
				array(
					'label'  => esc_html__( 'Label Color', 'jet-engine' ),
					'type'   => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-caption__name' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'caption_txt_typography',
					'selector' => '{{WRAPPER}} .jet-calendar-caption__name',
				)
			);

			$this->add_responsive_control(
				'caption_padding',
				array(
					'label'      => esc_html__( 'Padding', 'jet-engine' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} .jet-calendar-caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'caption_margin',
				array(
					'label'      => esc_html__( 'Margin', 'jet-engine' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} .jet-calendar-caption' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'           => 'caption_border',
					'label'          => esc_html__( 'Border', 'jet-engine' ),
					'placeholder'    => '1px',
					'selector'       => '{{WRAPPER}} .jet-calendar-caption',
				)
			);

			$this->add_responsive_control(
				'caption_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'jet-engine' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .jet-calendar-caption' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_nav_style',
				array(
					'label'      => esc_html__( 'Navigation Arrows', 'jet-engine' ),
					'tab'        => Controls_Manager::TAB_STYLE,
					'show_label' => false,
				)
			);

			$this->add_control(
				'nav_width',
				array(
					'label' => esc_html__( 'Width', 'jet-engine' ),
					'type'  => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 10,
							'max' => 100,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-nav__link' => 'width: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'nav_height',
				array(
					'label' => esc_html__( 'Height', 'jet-engine' ),
					'type'  => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 10,
							'max' => 100,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-nav__link' => 'height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'nav_size',
				array(
					'label' => esc_html__( 'Arrow Size', 'jet-engine' ),
					'type'  => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 10,
							'max' => 100,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-nav__link' => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->start_controls_tabs( 'tabs_nav_prev_next_style' );

			$this->start_controls_tab(
				'tab_nav_prev',
				array(
					'label' => esc_html__( 'Prev Arrow (Default)', 'jet-engine' ),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'           => 'nav_border',
					'label'          => esc_html__( 'Border', 'jet-engine' ),
					'placeholder'    => '1px',
					'selector'       => '{{WRAPPER}} .jet-calendar-nav__link',
				)
			);

			$this->add_responsive_control(
				'nav_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'jet-engine' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .jet-calendar-nav__link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_nav_next',
				array(
					'label' => esc_html__( 'Next Arrow', 'jet-engine' ),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'           => 'nav_border_next',
					'label'          => esc_html__( 'Border', 'jet-engine' ),
					'placeholder'    => '1px',
					'selector'       => '{{WRAPPER}} .jet-calendar-nav__link.nav-link-next',
				)
			);

			$this->add_responsive_control(
				'nav_border_radius_next',
				array(
					'label'      => esc_html__( 'Border Radius', 'jet-engine' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .jet-calendar-nav__link.nav-link-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->start_controls_tabs( 'tabs_nav_style' );

			$this->start_controls_tab(
				'tab_nav_normal',
				array(
					'label' => esc_html__( 'Normal', 'jet-engine' ),
				)
			);

			$this->add_control(
				'nav_color',
				array(
					'label'     => esc_html__( 'Text Color', 'jet-engine' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-nav__link' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'nav_background_color',
				array(
					'label'  => esc_html__( 'Background Color', 'jet-engine' ),
					'type'   => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-nav__link' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_nav_hover',
				array(
					'label' => esc_html__( 'Hover', 'jet-engine' ),
				)
			);

			$this->add_control(
				'nav_color_hover',
				array(
					'label' => esc_html__( 'Text Color', 'jet-engine' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-nav__link:hover' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'nav_background_color_hover',
				array(
					'label' => esc_html__( 'Background Color', 'jet-engine' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-nav__link:hover' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'nav_border_color_hover',
				array(
					'label' => esc_html__( 'Border Color', 'jet-engine' ),
					'type' => Controls_Manager::COLOR,
					'condition' => array(
						'nav_border_border!' => '',
					),
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-nav__link:hover' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->end_controls_section();

			$this->start_controls_section(
				'section_week_style',
				array(
					'label'      => esc_html__( 'Week Days', 'jet-engine' ),
					'tab'        => Controls_Manager::TAB_STYLE,
					'show_label' => false,
				)
			);

			$this->add_control(
				'week_bg_color',
				array(
					'label' => esc_html__( 'Background Color', 'jet-engine' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-header__week-day' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'week_txt_color',
				array(
					'label'  => esc_html__( 'Text Color', 'jet-engine' ),
					'type'   => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-header__week-day' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'week_txt_typography',
					'selector' => '{{WRAPPER}} .jet-calendar-header__week-day',
				)
			);

			$this->add_responsive_control(
				'week_padding',
				array(
					'label'      => esc_html__( 'Padding', 'jet-engine' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} .jet-calendar-header__week-day' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'week_border_width',
				array(
					'label'      => esc_html__( 'Border Width', 'jet-engine' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} .jet-calendar-header__week-day' => 'border-style: solid; border-top-width: {{TOP}}{{UNIT}}; border-bottom-width: {{BOTTOM}}{{UNIT}}; border-left-width: {{LEFT}}{{UNIT}}; border-right-width: 0;',
						'{{WRAPPER}} .jet-calendar-header__week-day:last-child' => 'border-right-width: {{RIGHT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'week_border_color',
				array(
					'label'  => esc_html__( 'Border Color', 'jet-engine' ),
					'type'   => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-header__week-day' => 'border-color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'week_border_color_first',
				array(
					'label'  => esc_html__( 'First Border Color', 'jet-engine' ),
					'type'   => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-header__week-day:first-child' => 'border-left-color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'week_border_color_last',
				array(
					'label'  => esc_html__( 'Last Border Color', 'jet-engine' ),
					'type'   => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-header__week-day:last-child' => 'border-right-color: {{VALUE}}',
					),
				)
			);

			$this->add_responsive_control(
				'week_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'jet-engine' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .jet-calendar-header__week-day:first-child' => 'border-radius: {{TOP}}{{UNIT}} 0 0 {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .jet-calendar-header__week-day:last-child' => 'border-radius: 0 {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0;',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_day_style',
				array(
					'label'      => esc_html__( 'Days', 'jet-engine' ),
					'tab'        => Controls_Manager::TAB_STYLE,
					'show_label' => false,
				)
			);

			$this->add_control(
				'day_bg_color',
				array(
					'label' => esc_html__( 'Background Color', 'jet-engine' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-week__day' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->add_responsive_control(
				'day_padding',
				array(
					'label'      => esc_html__( 'Padding', 'jet-engine' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} .jet-calendar-week__day-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'day_min_height',
				array(
					'label' => esc_html__( 'Min height', 'jet-engine' ),
					'type'  => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 10,
							'max' => 200,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-week__day-content' => 'min-height: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .jet-calendar-week__day-wrap'    => 'min-height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'day_events_gap',
				array(
					'label' => esc_html__( 'Gap between events', 'jet-engine' ),
					'type'  => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 20,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-week__day-event + .jet-calendar-week__day-event' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'day_border_width',
				array(
					'label' => esc_html__( 'Border Width', 'jet-engine' ),
					'type'  => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 20,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-week .jet-calendar-week__day' => 'border: {{SIZE}}{{UNIT}} solid; border-right-width: 0; border-bottom-width: 0;',
						'{{WRAPPER}} .jet-calendar-week .jet-calendar-week__day:last-child' => 'border-right-width: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} tbody .jet-calendar-week:last-child .jet-calendar-week__day' => 'border-bottom-width: {{SIZE}}{{UNIT}};'
					),
				)
			);

			$this->add_control(
				'day_border_color',
				array(
					'label'  => esc_html__( 'Border Color', 'jet-engine' ),
					'type'   => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-grid .jet-calendar-week .jet-calendar-week__day' => 'border-color: {{VALUE}}',
					),
				)
			);

			$this->add_responsive_control(
				'day_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'jet-engine' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} tbody .jet-calendar-week:first-child .jet-calendar-week__day:first-child' => 'border-radius: {{TOP}}{{UNIT}} 0 0 0;',
						'{{WRAPPER}} tbody .jet-calendar-week:first-child .jet-calendar-week__day:last-child' => 'border-radius: 0 {{RIGHT}}{{UNIT}} 0 0;',
						'{{WRAPPER}} tbody .jet-calendar-week:last-child .jet-calendar-week__day:first-child' => 'border-radius: 0 0 0 {{BOTTOM}}{{UNIT}};',
						'{{WRAPPER}} tbody .jet-calendar-week:last-child .jet-calendar-week__day:last-child' => 'border-radius: 0 0 {{LEFT}}{{UNIT}} 0;',
					),
				)
			);

			$this->add_control(
				'day_label_styles',
				array(
					'label'     => esc_html__( 'Date Label', 'jet-engine' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->start_controls_tabs( 'tabs_day_label_style' );

			$this->start_controls_tab(
				'tabs_day_label_noraml',
				array(
					'label' => esc_html__( 'Normal', 'jet-engine' ),
				)
			);

			$this->add_control(
				'day_label_color',
				array(
					'label'  => esc_html__( 'Text Color', 'jet-engine' ),
					'type'   => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-week__day-date' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'day_label_bg_color',
				array(
					'label' => esc_html__( 'Background Color', 'jet-engine' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-week__day-date' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tabs_day_label_has_events',
				array(
					'label' => esc_html__( 'Has Events', 'jet-engine' ),
				)
			);

			$this->add_control(
				'day_label_color_has_events',
				array(
					'label'  => esc_html__( 'Text Color', 'jet-engine' ),
					'type'   => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .has-events .jet-calendar-week__day-date' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'day_label_bg_color_has_events',
				array(
					'label' => esc_html__( 'Background Color', 'jet-engine' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .has-events .jet-calendar-week__day-date' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'day_label_typography',
					'selector' => '{{WRAPPER}} .jet-calendar-week__day-date',
				)
			);

			$this->add_responsive_control(
				'day_label_alignment',
				array(
					'label'   => esc_html__( 'Date Box Alignment', 'jet-engine' ),
					'type'    => Controls_Manager::CHOOSE,
					'default' => 'flex-end',
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
						'{{WRAPPER}} .jet-calendar-week__day-header' => 'justify-content: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'day_label_text_alignment',
				array(
					'label'   => esc_html__( 'Date Text Alignment', 'jet-engine' ),
					'type'    => Controls_Manager::CHOOSE,
					'default' => 'center',
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
						'{{WRAPPER}} .jet-calendar-week__day-date' => 'justify-content: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'day_label_width',
				array(
					'label' => esc_html__( 'Width', 'jet-engine' ),
					'type'  => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%' ),
					'range' => array(
						'%' => array(
							'min' => 1,
							'max' => 100,
						),
						'px' => array(
							'min' => 10,
							'max' => 100,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-week__day-date' => 'width: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'day_label_height',
				array(
					'label' => esc_html__( 'Height', 'jet-engine' ),
					'type'  => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 10,
							'max' => 100,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-week__day-date' => 'height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'           => 'day_label_border',
					'label'          => esc_html__( 'Border', 'jet-engine' ),
					'placeholder'    => '1px',
					'selector'       => '{{WRAPPER}} .jet-calendar-week__day-date',
				)
			);

			$this->add_responsive_control(
				'day_label_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'jet-engine' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .jet-calendar-week__day-date' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'day_label_padding',
				array(
					'label'      => esc_html__( 'Padding', 'jet-engine' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .jet-calendar-week__day-date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'day_label_margin',
				array(
					'label'      => esc_html__( 'Margin', 'jet-engine' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .jet-calendar-week__day-date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'day_disabled_styles',
				array(
					'label'     => esc_html__( 'Disabled Days (not in current month)', 'jet-engine' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_control(
				'day_opacity',
				array(
					'label' => esc_html__( 'Opacity', 'jet-engine' ),
					'type'  => Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 1,
					'step' => 0.1,
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-week__day.day-pad' => 'opacity: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'day_bg_color_disabled',
				array(
					'label' => esc_html__( 'Day Background Color', 'jet-engine' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-week__day.day-pad' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'day_border_color_disabled',
				array(
					'label'  => esc_html__( 'Day Border Color', 'jet-engine' ),
					'type'   => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-week__day.day-pad' => 'border-color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'day_label_color_disabled',
				array(
					'label'  => esc_html__( 'Day Label Text Color', 'jet-engine' ),
					'type'   => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-week__day.day-pad .jet-calendar-week__day-date' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'day_label_bg_color_disabled',
				array(
					'label' => esc_html__( 'Day Label Background Color', 'jet-engine' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-week__day.day-pad .jet-calendar-week__day-date' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'day_label_border_color_disabled',
				array(
					'label' => esc_html__( 'Day Label Border Color', 'jet-engine' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-week__day.day-pad .jet-calendar-week__day-date' => 'border-color: {{VALUE}}',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'calendar_mobile_style',
				array(
					'label'      => esc_html__( 'Mobile', 'jet-engine' ),
					'tab'        => Controls_Manager::TAB_STYLE,
					'show_label' => false,
				)
			);

			$this->add_control(
				'mobile_trigger_color',
				array(
					'label' => esc_html__( 'Mobile Trigger Color', 'jet-engine' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-week__day-mobile-trigger' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'mobile_trigger_color_active',
				array(
					'label' => esc_html__( 'Active Mobile Trigger Color', 'jet-engine' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .calendar-event-active .jet-calendar-week__day-mobile-trigger' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'mobile_trigger_width',
				array(
					'label'      => esc_html__( 'Mobile Trigger Width', 'jet-engine' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%' ),
					'range'      => array(
						'px' => array(
							'min' => 10,
							'max' => 100,
						),
						'%' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-week__day-mobile-trigger' => 'width: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'mobile_trigger_height',
				array(
					'label'      => esc_html__( 'Mobile Trigger Height', 'jet-engine' ),
					'type'       => Controls_Manager::SLIDER,
					'range'      => array(
						'px' => array(
							'min' => 10,
							'max' => 200,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .jet-calendar-week__day-mobile-trigger' => 'height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'mobile_trigger_alignment',
				array(
					'label'   => esc_html__( 'Mobile Trigger Alignment', 'jet-engine' ),
					'type'    => Controls_Manager::CHOOSE,
					'default' => 'flex-end',
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
						'{{WRAPPER}} .jet-calendar-week__day-mobile-wrap' => 'justify-content: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'mobile_trigger_border_radius',
				array(
					'label'      => esc_html__( 'Mobile Trigger Border Radius', 'jet-engine' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .jet-calendar-week__day-mobile-trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'mobile_trigger_margin',
				array(
					'label'      => esc_html__( 'Mobile Trigger Margin', 'jet-engine' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .jet-calendar-week__day-mobile-trigger' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'mobile_event_margin',
				array(
					'label'      => esc_html__( 'Mobile Event Margin', 'jet-engine' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .jet-calendar-week__day-mobile-event' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);


			$this->end_controls_section();

		}

		/**
		 * Get posts
		 *
		 * @return [type] [description]
		 */
		public function get_posts( $settings ) {

			add_filter( 'jet-engine/listing/grid/posts-query-args', array( $this, 'add_calendar_query' ) );
			$args  = $this->build_posts_query_args_array( $settings );
			remove_filter( 'jet-engine/listing/grid/posts-query-args', array( $this, 'add_calendar_query' ) );

			$query = new \WP_Query( $args );

			return $query->posts;

		}

		/**
		 * Prepare date query
		 *
		 * @return array
		 */
		public function add_calendar_query( $args ) {

			$settings       = $this->get_widget_settings();
			$prepared_posts = array();
			$group_by       = $settings['group_by'];
			$meta_key       = false;

			switch ( $group_by ) {

				case 'post_date':
				case 'post_mod':

					if ( 'post_date' === $group_by ) {
						$db_column = 'post_date_gmt';
					} else {
						$db_column = 'post_modified_gmt';
					}

					if ( isset( $args['date_query'] ) ) {
						$date_query = $args['date_query'];
					} else {
						$date_query = array();
					}

					$month = $this->get_current_month();

					$date_query = array_merge( $date_query, array(
						array(
							'column' => $db_column,
							'year'   => date( 'Y', $month ),
							'month'  => date( 'm', $month ),
						),
					) );

					$args['date_query'] = $date_query;

					break;

				case 'meta_date':

					if ( $settings['group_by_key'] ) {
						$meta_key = esc_attr( $settings['group_by_key'] );
					}

					if ( isset( $args['meta_query'] ) ) {
						$meta_query = $args['meta_query'];
					} else {
						$meta_query = array();
					}

					if ( $meta_key ) {

						$meta_query = array_merge( $meta_query, array(
							array(
								'key'     => $meta_key,
								'value'   => array( $this->get_current_month(), $this->get_current_month( true ) ),
								'compare' => 'BETWEEN',
							),
						) );

					}

					$args['meta_query'] = $meta_query;

					break;

				default:
					$args = apply_filters( 'jet-engine/listing/calendar/query', $args, $group_by, $this );
					break;

			}

			$args['posts_per_page'] = -1;

			return $args;

		}

		public function prepare_posts_for_calendar( $query, $settings ) {

			$prepared_posts = array();
			$group_by       = $settings['group_by'];
			$key            = false;

			if ( empty( $query ) ) {
				return $prepared_posts;
			}

			foreach ( $query as $post ) {

				switch ( $group_by ) {

					case 'post_date':
						$key = strtotime( $post->post_date );
						break;

					case 'post_mod':
						$key = strtotime( $post->post_modified );
						break;

					case 'meta_date':

						$meta_key = false;
						$meta_key = esc_attr( $settings['group_by_key'] );
						$key      = get_post_meta( $post->ID, $meta_key, true );

						break;

					default:
						/**
						 * Should return timestamp of required month day
						 * @var int
						 */
						$key = apply_filters( 'jet-engine/listing/calendar/date-key', $key, $group_by, $this );
						break;

				}

				$key = date( 'j', $key );

				if ( isset( $prepared_posts[ $key ] ) ) {
					$prepared_posts[ $key ][] = $post;
				} else {
					$prepared_posts[ $key ] = array( $post );
				}

			}

			return $prepared_posts;

		}

		/**
		 * Returns current month
		 * @return [type] [description]
		 */
		public function get_current_month( $last_day = false ) {

			if ( false !== $this->first_day && ! $last_day ) {
				return $this->first_day;
			}

			if ( false !== $this->last_day && $last_day ) {
				return $this->last_day;
			}

			if ( isset( $_REQUEST['month'] ) ) {
				$month = date( '1 F Y', strtotime( $_REQUEST['month'] ) );
			} else {
				$month = date( '1 F Y', strtotime( 'this month' ) );
			}

			$month = strtotime( $month );

			if ( ! $last_day ) {
				$this->first_day = $month;
				return $this->first_day;
			} else {
				$this->last_day = strtotime( date( 'Y-m-t', $month ) );
				return $this->last_day;
			}

		}

		/**
		 * Get days number for passed month
		 * @return [type] [description]
		 */
		public function get_days_num() {
			return date( 't', $this->get_current_month() );
		}

		/**
		 * Render posts template.
		 * Moved to separate function to be rewritten by other layouts
		 *
		 * @return [type] [description]
		 */
		public function posts_template( $query, $settings ) {

			$base_class       = $this->get_name();
			$prepared_posts   = $this->prepare_posts_for_calendar( $query, $settings );
			$days_num         = $this->get_days_num();
			$week_begins      = (int) get_option( 'start_of_week' );
			$first_week       = true;
			$current_month    = $this->get_current_month();
			$human_read_month = date( 'F Y', $current_month );
			$first_day        = date( 'w', $current_month );
			$inc              = 0;
			$pad              = $first_day - $week_begins;
			$prev_month       = strtotime( $human_read_month . ' - 1 month' );
			$human_read_prev  = date( 'F Y', $prev_month );
			$human_read_next  = date( 'F Y', strtotime( $human_read_month . ' + 1 month' ) );
			$prev_month       = date( 't', $prev_month );
			$days_format      = isset( $settings['week_days_format'] ) ? $settings['week_days_format'] : 'short';

			if ( 0 > $pad ) {
				$pad = 7 - abs( $pad );
			}

			$data_settings = array(
				'lisitng_id'          => isset( $settings['lisitng_id'] ) ? $settings['lisitng_id'] : false,
				'group_by'            => isset( $settings['group_by'] ) ? $settings['group_by'] : false,
				'group_by_key'        => isset( $settings['group_by_key'] ) ? $settings['group_by_key'] : false,
				'posts_query'         => isset( $settings['posts_query'] ) ? $settings['posts_query'] : array(),
				'meta_query_relation' => isset( $settings['meta_query_relation'] ) ? $settings['meta_query_relation'] : false,
				'tax_query_relation'  => isset( $settings['tax_query_relation'] ) ? $settings['tax_query_relation'] : false,
				'tax_query_relation'  => isset( $settings['tax_query_relation'] ) ? $settings['tax_query_relation'] : false,
				'hide_widget_if'      => isset( $settings['hide_widget_if'] ) ? $settings['hide_widget_if'] : false,
				'caption_layout'      => isset( $settings['caption_layout'] ) ? $settings['caption_layout'] : 'layout-1',
			);

			$data_settings = htmlspecialchars( json_encode( $data_settings ) );

			printf(
				'<div class="%1$s jet-calendar" data-settings="%2$s" data-post="%3$d">',
				$base_class, $data_settings, get_the_ID()
			);

			do_action( 'jet-engine/listing/grid/before', $this );

			do_action( 'jet-engine/listing/calendar/before', $this );

			echo '<table class="jet-calendar-grid" >';

			include jet_engine()->get_template( 'calendar/header.php' );

			echo '<tbody>';

			jet_engine()->frontend->set_listing( $settings['lisitng_id'] );

			// Add last days of previous month
			if ( 0 < $pad ) {

				for ( $i = 0; $i < $pad; $i++ ) {

					include jet_engine()->get_template( 'calendar/week-start.php' );

					$num      = $prev_month - $pad + $i + 1;
					$posts    = false;
					$padclass = ' day-pad';

					include jet_engine()->get_template( 'calendar/date.php' );
					include jet_engine()->get_template( 'calendar/week-end.php' );

					$inc++;
				}

			}

			// Current month
			for ( $i = 1; $i <= $days_num; $i++ ) {

				include jet_engine()->get_template( 'calendar/week-start.php' );

				$num      = $i;
				$posts    = ! empty( $prepared_posts[ $i ] ) ? $prepared_posts[ $i ] : false;
				$padclass = ! empty( $posts ) ? ' has-events' : '';

				include jet_engine()->get_template( 'calendar/date.php' );
				include jet_engine()->get_template( 'calendar/week-end.php' );

				$inc++;

			}

			// Add first days of next month
			$days_left = 7 - ( $inc % 7 );

			if ( 0 < $days_left ) {

				for ( $i = 1; $i <= $days_left; $i++ ) {

					include jet_engine()->get_template( 'calendar/week-start.php' );

					$num      = $i;
					$posts    = false;
					$padclass = ' day-pad';

					include jet_engine()->get_template( 'calendar/date.php' );
					include jet_engine()->get_template( 'calendar/week-end.php' );

					$inc++;

				}

			}

			jet_engine()->frontend->reset_listing();

			echo '</tbody>';

			echo '</table>';

			do_action( 'jet-engine/listing/grid/after', $this );

			do_action( 'jet-engine/listing/calendar/after', $this );

			echo '</div>';

		}

		protected function render() {
			$this->render_posts();
		}

	}

}
