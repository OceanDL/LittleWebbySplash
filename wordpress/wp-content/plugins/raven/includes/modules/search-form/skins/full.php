<?php
namespace Raven\Modules\Search_Form\Skins;

defined( 'ABSPATH' ) || die();

class Full extends \Elementor\Skin_Base {

	public function get_id() {
		return 'full';
	}

	public function get_title() {
		return __( 'Full Screen', 'raven' );
	}

	protected function _register_controls_actions() {
		add_action( 'elementor/element/raven-search-form/section_content/after_section_end', [ $this, 'register_controls' ], 10 );
	}

	public function register_controls() {
		$this->register_input_controls();
		$this->register_overlay_controls();
		$this->register_icon_controls();
	}

	public function register_input_controls() {
		$this->start_controls_section(
			'section_input',
			[
				'label' => __( 'Input', 'raven' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'input_width',
			[
				'label' => __( 'Width', 'raven' ),
				'type' => 'slider',
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 5,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-input' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'input_typography',
				'scheme' => '3',
				'selector' => '{{WRAPPER}} .raven-search-form-input',
			]
		);

		$this->add_responsive_control(
			'input_padding',
			[
				'label' => __( 'Padding', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_input' );

		$this->start_controls_tab(
			'tab_input_normal',
			[
				'label' => __( 'Normal', 'raven' ),
			]
		);

		$this->add_control(
			'input_color',
			[
				'label' => __( 'Text Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-input' => 'color: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'input_background_color',
			[
				'label' => __( 'Background Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-input' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_border_heading',
			[
				'label' => __( 'Border', 'raven' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'input_border_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'input_border_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-input' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'input_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'raven' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-search-form-input',
			]
		);

		$this->add_control(
			'input_border_radius',
			[
				'label' => __( 'Border Radius', 'raven' ),
				'type' => 'dimensions',
				'size_s' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'input_box_shadow',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-search-form-input',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_input_focus',
			[
				'label' => __( 'Focus', 'raven' ),
			]
		);

		$this->add_control(
			'focus_input_color',
			[
				'label' => __( 'Text Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-input:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'focus_input_background_color',
			[
				'label' => __( 'Background Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-input:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'focus_input_border_heading',
			[
				'label' => __( 'Border', 'raven' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'focus_input_border_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'focus_input_border_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-input:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'focus_input_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'raven' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-search-form-input:focus',
			]
		);

		$this->add_control(
			'focus_input_border_radius',
			[
				'label' => __( 'Border Radius', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-input:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'focus_input_box_shadow',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-search-form-input:focus',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function register_overlay_controls() {
		$this->start_controls_section(
			'section_overlay',
			[
				'label' => __( 'Overlay', 'raven' ),
				'tab' => 'style',
			]
		);

		$this->add_group_control(
			'background',
			[
				'name' => 'overlay_background',
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .raven-search-form-lightbox',
			]
		);

		$this->end_controls_section();
	}

	public function register_icon_controls() {
		$this->start_controls_section(
			'section_icon',
			[
				'label' => __( 'Icon', 'raven' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'raven' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-button' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_padding',
			[
				'label' => __( 'Padding', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_align',
			[
				'label' => __( 'Alignment', 'raven' ),
				'type' => 'choose',
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'raven' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'raven' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'raven' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-container' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_icon' );

		$this->start_controls_tab(
			'tab_icon_normal',
			[
				'label' => __( 'Normal', 'raven' ),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_background_color',
			[
				'label' => __( 'Background Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_border_heading',
			[
				'label' => __( 'Border', 'raven' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_border_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'icon_border_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-button' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'icon_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'raven' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-search-form-button',
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label' => __( 'Border Radius', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'icon_box_shadow',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-search-form-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_hover',
			[
				'label' => __( 'Hover', 'raven' ),
			]
		);

		$this->add_control(
			'hover_icon_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_icon_background_color',
			[
				'label' => __( 'Background Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_icon_border_heading',
			[
				'label' => __( 'Border', 'raven' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'hover_icon_border_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'hover_icon_border_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'hover_icon_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'raven' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-search-form-button:hover',
			]
		);

		$this->add_control(
			'hover_icon_border_radius',
			[
				'label' => __( 'Border Radius', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-search-form-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'hover_icon_box_shadow',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-search-form-button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function render() {
		$icon = $this->parent->get_settings( 'icon' );

		if ( empty( $icon ) ) {
			$icon = 'fa fa-search';
		}
		?>
		<form class="raven-search-form raven-search-form-full" method="get" action="<?php echo esc_url( get_bloginfo( 'url' ) ); ?>" role="search">
			<div class="raven-search-form-container">
				<span class="raven-search-form-button raven-search-form-toggle <?php echo esc_attr( $icon ); ?>" tabindex="-1"></span>
			</div>
			<div class="raven-search-form-lightbox">
				<span class="raven-search-form-close" tabindex="-1">&times;</span>
				<input class="raven-search-form-input" type="search" name="s" placeholder="<?php echo $this->parent->get_settings( 'placeholder' ); ?>" />
			</div>
		</form>
		<?php
	}
}
