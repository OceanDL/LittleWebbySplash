<?php
namespace Raven\Modules\Alert\Widgets;

use Raven\Base\Base_Widget;

defined( 'ABSPATH' ) || die();

class Alert extends Base_Widget {

	public function get_name() {
		return 'raven-alert';
	}

	public function get_title() {
		return __( 'Alert', 'raven' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-alert';
	}

	protected function _register_controls() {
		$this->register_section_content();
		$this->register_section_settings();
		$this->register_section_alert();
		$this->register_section_title();
		$this->register_section_description();
		$this->register_section_icon();
	}

	private function register_section_content() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'raven' ),
			]
		);

		$this->add_control(
			'alert_type',
			[
				'label' => __( 'Type', 'raven' ),
				'type' => 'select',
				'default' => 'info',
				'options' => [
					'info' => __( 'Info', 'raven' ),
					'success' => __( 'Success', 'raven' ),
					'warning' => __( 'Warning', 'raven' ),
					'danger' => __( 'Danger', 'raven' ),
				],
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title & Description', 'raven' ),
				'type' => 'text',
				'placeholder' => __( 'Enter your title', 'raven' ),
				'default' => __( 'This is an Alert', 'raven' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'description',
			[
				'label' => __( 'Content', 'raven' ),
				'type' => 'wysiwyg',
				'placeholder' => __( 'Enter your description', 'raven' ),
				'default' => __( 'I am a description. Click the edit button to change this text.', 'raven' ),
				'separator' => 'none',
				'show_label' => false,
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => __( 'Choose Icon', 'raven' ),
				'type' => 'icon',
				'default' => 'fa fa-info-circle',
			]
		);

		$this->end_controls_section();
	}

	private function register_section_settings() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'raven' ),
			]
		);

		$this->add_control(
			'show_dismiss',
			[
				'label' => __( 'Dismiss Button', 'raven' ),
				'type' => 'switcher',
				'label_off' => __( 'Hide', 'raven' ),
				'label_on' => __( 'Show', 'raven' ),
				'default' => 'yes',
			]
		);

		$this->end_controls_section();
	}

	private function register_section_alert() {
		$this->start_controls_section(
			'section_alert',
			[
				'label' => __( 'Alert', 'raven' ),
				'tab' => 'style',
			]
		);

		$this->add_group_control(
			'background',
			[
				'name' => 'background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => __( 'Background Color Type', 'raven' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-alert',
			]
		);

		/**
		 * Use HIDDEN control to hack style.
		 *
		 * If background type is gradient, add `background-origin` property to fix the issue of border (dotted, dashed and etc).
		 *
		 * Proper code to use for Group_Control_Background. Unfortunately random bug encountered when using this code:
		 *
		 * 1. It can't read {{SELECTOR}} - though {{WRAPPER}} > .raven-alert would work
		 * 2. Javascript object style to print value is not working
		 *
		 * 'fields_options' => [
		 *  'gradient_position' => [
		 *   'selectors' => [
		 *    '{{SELECTOR}}' => 'background-origin: border-box; background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}});',
		 *   ],
		 *  ],
		 * ],
		 *
		 * This won't have any side effect in the future since we just added this to add new style property but with condition.
		 */
		$this->add_control(
			'background_origin',
			[
				'label' => __( 'View', 'raven' ),
				'type' => 'hidden',
				'default' => 'border-box',
				'selectors' => [
					'{{WRAPPER}} .raven-alert' => 'background-origin: {{VALUE}};',
				],
				'condition' => [
					'background_background' => 'gradient',
				],
			]
		);

		$this->add_responsive_control(
			'width',
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
						'min' => 30,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-alert' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'padding',
			[
				'label' => __( 'Padding', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-alert' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_align',
			[
				'label' => __( 'Alignment', 'raven' ),
				'type' => 'choose',
				'default' => 'left',
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
			]
		);

		$this->add_control(
			'border_heading',
			[
				'label' => __( 'Border', 'raven' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-alert' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'border_border!' => '',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'raven' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-alert',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-alert' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_title() {
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Title', 'raven' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-alert-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'title_typography',
				'scheme' => '1',
				'selector' => '{{WRAPPER}} .raven-alert-title',
			]
		);

		$this->end_controls_section();
	}

	private function register_section_description() {
		$this->start_controls_section(
			'section_description',
			[
				'label' => __( 'Description', 'raven' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Text Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-alert-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'description_typography',
				'scheme' => '3',
				'selector' => '{{WRAPPER}} .raven-alert-description',
			]
		);

		$this->end_controls_section();
	}

	private function register_section_icon() {
		$this->start_controls_section(
			'section_icon',
			[
				'label' => __( 'Icon', 'raven' ),
				'tab' => 'style',
				'condition' => [
					'icon!' => '',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-alert-icon i' => 'color: {{VALUE}};',
				],
				'condition' => [
					'icon!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'raven' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'default' => [ '30px' ],
				'selectors' => [
					'{{WRAPPER}} .raven-alert-icon i' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon!' => '',
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
					'{{WRAPPER}} .raven-alert-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'icon!' => '',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();

		if ( empty( $settings['title'] ) ) {
			return;
		}

		$this->add_render_attribute(
			'wrapper',
			'class',
			'raven-widget-wrapper raven-flex' .
			( ! empty( $settings['box_align'] ) ? ' raven-flex-' . $settings['box_align'] : '' ) .
			( ! empty( $settings['box_align_tablet'] ) ? ' raven-flex-' . $settings['box_align_tablet'] . '\@m' : '' ) .
			( ! empty( $settings['box_align_mobile'] ) ? ' raven-flex-' . $settings['box_align_mobile'] . '\@s' : '' )
		);

		$this->add_render_attribute(
			'alert',
			'class',
			[
				'raven-alert',
				'raven-alert-' . $settings['alert_type'],
				'raven-flex raven-flex-top raven-flex-none',
			]
		);
		$this->add_render_attribute( 'alert', 'role', 'alert' );
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'alert' ); ?>>
				<?php
				$this->render_icon();
				$this->render_text();

				if ( 'yes' === $settings['show_dismiss'] ) :
					?>
					<button class="raven-alert-dismiss" type="button">
						<span aria-hidden="true">&times;</span>
						<span class="elementor-screen-only"><?php esc_html_e( 'Dismiss Alert', 'raven' ); ?></span>
					</button>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	protected function render_icon() {
		$settings = $this->get_settings();

		if ( empty( $settings['icon'] ) ) {
			return;
		}
		?>
		<div class="raven-alert-icon">
			<i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
		</div>
		<?php
	}

	protected function render_text() {
		$settings = $this->get_settings();

		$this->add_render_attribute( 'title', 'class', 'raven-alert-title' );
		$this->add_render_attribute( 'description', 'class', 'raven-alert-description' );

		$this->add_inline_editing_attributes( 'title', 'none' );
		$this->add_inline_editing_attributes( 'description', 'advanced' );
		?>
		<div class="raven-alert-content">
			<div <?php echo $this->get_render_attribute_string( 'title' ); ?>><?php echo $settings['title']; ?></div>
			<?php if ( ! empty( $settings['description'] ) ) { ?>
				<div <?php echo $this->get_render_attribute_string( 'description' ); ?>><?php echo $settings['description']; ?></div>
			<?php } ?>
		</div>
		<?php
	}

	protected function _content_template() {
		?>
		<# if ( settings.title ) {
			view.addRenderAttribute(
				'wrapper',
				'class',
				'raven-widget-wrapper raven-flex' +
				( settings.box_align && ' raven-flex-' + settings.box_align ) +
				( settings.box_align_tablet && ' raven-flex-' + settings.box_align_tablet + '\@m' ) +
				( settings.box_align_mobile && ' raven-flex-' + settings.box_align_mobile + '\@s' )
			);

			view.addRenderAttribute( 'title', 'class', 'raven-alert-title' );
			view.addRenderAttribute( 'description', 'class', 'raven-alert-description' );

			view.addInlineEditingAttributes( 'title', 'none' );
			view.addInlineEditingAttributes( 'description', 'advanced' );
			#>
			<div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
				<div class="raven-alert raven-alert-{{ settings.alert_type }} raven-flex raven-flex-top raven-flex-none" role="alert">
					<# if ( settings.icon ) { #>
						<div class="raven-alert-icon">
							<i class="{{ settings.icon }}" aria-hidden="true"></i>
						</div>
					<# } #>
					<div class="raven-alert-content">
						<div {{{ view.getRenderAttributeString( 'title' ) }}}>{{{ settings.title }}}</div>
						<# if ( settings.description ) { #>
							<div {{{ view.getRenderAttributeString( 'description' ) }}}>{{{ settings.description }}}</div>
						<# } #>
					</div>
					<# if ( 'yes' === settings.show_dismiss ) { #>
						<button class="raven-alert-dismiss" type="button">
							<span aria-hidden="true">&times;</span>
							<span class="elementor-screen-only"><?php esc_html_e( 'Dismiss alert', 'raven' ); ?></span>
						</button>
					<# } #>
				</div>
			</div>
		<# } #>
		<?php
	}
}
