<?php
namespace Raven\Modules\Tabs\Widgets;

use Raven\Base\Base_Widget;

defined( 'ABSPATH' ) || die();

class Tabs extends Base_Widget {

	public function get_name() {
		return 'raven-tabs';
	}

	public function get_title() {
		return __( 'Tabs', 'raven' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-tabs';
	}

	protected function _register_controls() {
		$this->register_section_content();
		$this->register_section_settings();
		$this->register_section_tabs();
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

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'tab_title',
			[
				'label' => __( 'Title & Content', 'raven' ),
				'type' => 'text',
				'default' => __( 'Tab Title', 'raven' ),
				'placeholder' => __( 'Tab Title', 'raven' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'tab_content',
			[
				'label' => __( 'Content', 'raven' ),
				'default' => __( 'Tab Content', 'raven' ),
				'placeholder' => __( 'Tab Content', 'raven' ),
				'type' => 'wysiwyg',
				'show_label' => false,
			]
		);

		$repeater->add_control(
			'tab_icon',
			[
				'label' => __( 'Icon', 'raven' ),
				'type' => 'icon',
			]
		);

		$this->add_control(
			'tabs',
			[
				'label' => __( 'Tabs Items', 'raven' ),
				'type' => 'repeater',
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'tab_title' => __( 'Tab #1', 'raven' ),
						'tab_content' => __( 'I am tab content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'raven' ),
					],
					[
						'tab_title' => __( 'Tab #2', 'raven' ),
						'tab_content' => __( 'I am tab content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'raven' ),
					],
				],
				'title_field' => '{{{ tab_title }}}',
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
			'type',
			[
				'label' => __( 'Type', 'raven' ),
				'type' => 'select',
				'default' => 'horizontal',
				'separator' => 'before',
				'options' => [
					'horizontal' => __( 'Horizontal', 'raven' ),
					'vertical' => __( 'Vertical', 'raven' ),
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_tabs() {
		$this->start_controls_section(
			'section_tabs',
			[
				'label' => __( 'Tabs', 'raven' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => __( 'Background Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-title.raven-tabs-active, {{WRAPPER}} .raven-tabs-title.raven-tabs-active:after, {{WRAPPER}} .raven-tabs-content-wrapper, {{WRAPPER}} .raven-tabs-content.raven-tabs-active' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .raven-tabs-list:after, {{WRAPPER}} .raven-tabs-title.raven-tabs-active, {{WRAPPER}} .raven-tabs-mobile-title, {{WRAPPER}} .raven-tabs-content, {{WRAPPER}} .raven-tabs-content-wrapper' => 'border-color: {{VALUE}};',
				],
			]
		);

		// @codingStandardsIgnoreStart
		// $this->add_control(
		// 	'border_style',
		// 	[
		// 		'label' => __( 'Border Type', 'raven' ),
		// 		'type' =>select',
		// 		'options' => [
		// 			'' => __( 'None', 'raven' ),
		// 			'solid' => __( 'Solid', 'raven' ),
		// 			'double' => __( 'Double', 'raven' ),
		// 			'dotted' => __( 'Dotted', 'raven' ),
		// 			'dashed' => __( 'Dashed', 'raven' ),
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .raven-tabs-horizontal .raven-tabs-list:after' => 'border-top-style: {{VALUE}};',
		// 			'{{WRAPPER}} .raven-tabs-horizontal .raven-tabs-desktop-title' => 'border-top-style: {{VALUE}}; border-right-style: {{VALUE}}; border-left-style: {{VALUE}};',
		// 			'{{WRAPPER}} .raven-tabs-horizontal .raven-tabs-content' => 'border-right-style: {{VALUE}}; border-bottom-style: {{VALUE}}; border-left-style: {{VALUE}};',
		// 			'{{WRAPPER}} .raven-tabs-vertical .raven-tabs-list:after' => 'border-left-style: {{VALUE}};',
		// 			'{{WRAPPER}} .raven-tabs-vertical .raven-tabs-desktop-title' => 'border-top-style: {{VALUE}}; border-bottom-style: {{VALUE}}; border-left-style: {{VALUE}};',
		// 			'{{WRAPPER}} .raven-tabs-vertical .raven-tabs-content' => 'border-top-style: {{VALUE}}; border-right-style: {{VALUE}}; border-bottom-style: {{VALUE}};',
		// 		],
		// 	]
		// );
		// @codingStandardsIgnoreEnd

		$this->add_responsive_control(
			'border_width',
			[
				'label' => __( 'Border Width', 'raven' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-list:after, {{WRAPPER}} .raven-tabs-title, {{WRAPPER}} .raven-tabs-content, {{WRAPPER}} .raven-tabs-content-wrapper' => 'border-width: {{SIZE}}{{UNIT}};',
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
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_active_color',
			[
				'label' => __( 'Active Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-title.raven-tabs-active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'title_typography',
				'scheme' => '1',
				'selector' => '{{WRAPPER}} .raven-tabs-title',
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label' => __( 'Padding', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'description_typography',
				'scheme' => '3',
				'selector' => '{{WRAPPER}} .raven-tabs-content',
			]
		);

		$this->add_responsive_control(
			'description_padding',
			[
				'label' => __( 'Padding', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-title-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'raven' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-title-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label' => __( 'Spacing', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-tabs-title-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$tabs    = $this->get_settings( 'tabs' );
		$tabs_id = substr( $this->get_id_int(), 0, 3 );

		$this->add_render_attribute( 'tabs', 'class', 'raven-tabs raven-tabs-' . $this->get_settings( 'type' ) );
		?>
		<div class="raven-widget-wrapper">
			<div <?php echo $this->get_render_attribute_string( 'tabs' ); ?>>
				<div class="raven-tabs-list" role="tablist">
					<?php
					foreach ( $tabs as $index => $item ) :
						$tab_count             = $index + 1;
						$tab_title_setting_key = $this->get_repeater_setting_key( 'tab_title', 'tabs', $index );

						$this->add_render_attribute( $tab_title_setting_key, [
							'id' => 'raven-tabs-title-' . $tabs_id . $tab_count,
							'class' => [ 'raven-tabs-title', 'raven-tabs-desktop-title' ],
							'role' => 'tab',
							'aria-controls' => 'raven-tabs-content-' . $tabs_id . $tab_count,
							'tabindex' => '-1',
							'data-tab' => $tab_count,
						] );

						// Set initial active to avoid jumpy render.
						if ( 1 === $tab_count ) {
							$this->add_render_attribute( $tab_title_setting_key, 'class', 'raven-tabs-active' );
						}

						if ( ! empty( $item['tab_icon'] ) ) {
							$this->add_render_attribute( $tab_title_setting_key, 'class', 'raven-tabs-has-icon' );
						}
						?>
						<div <?php echo $this->get_render_attribute_string( $tab_title_setting_key ); ?>>
							<?php if ( ! empty( $item['tab_icon'] ) ) : ?>
								<span class="raven-tabs-title-icon">
									<i class="<?php echo esc_attr( $item['tab_icon'] ); ?>" aria-hidden="true"></i>
								</span>
							<?php endif; ?>
							<span class="raven-tabs-title-text"><?php echo $item['tab_title']; ?></span>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="raven-tabs-content-wrapper">
					<?php
					foreach ( $tabs as $index => $item ) :
						$tab_count                    = $index + 1;
						$tab_content_setting_key      = $this->get_repeater_setting_key( 'tab_content', 'tabs', $index );
						$tab_title_mobile_setting_key = $this->get_repeater_setting_key( 'tab_title_mobile', 'tabs', $tab_count );

						$this->add_render_attribute( $tab_content_setting_key, [
							'id' => 'raven-tabs-content-' . $tabs_id . $tab_count,
							'class' => [ 'raven-tabs-content', 'elementor-clearfix' ],
							'role' => 'tabpanel',
							'aria-labelledby' => 'raven-tabs-title-' . $tabs_id . $tab_count,
							'data-tab' => $tab_count,
						] );

						$this->add_render_attribute( $tab_title_mobile_setting_key, [
							'class' => [ 'raven-tabs-title', 'raven-tabs-mobile-title' ],
							'role' => 'tab',
							'tabindex' => '-1',
							'data-tab' => $tab_count,
						] );

						// Set initial active to avoid jumpy render.
						if ( 1 === $tab_count ) {
							$this->add_render_attribute( $tab_content_setting_key, 'class', 'raven-tabs-active' );
							$this->add_render_attribute( $tab_title_mobile_setting_key, 'class', 'raven-tabs-active' );
						}

						if ( ! empty( $item['tab_icon'] ) ) {
							$this->add_render_attribute( $tab_title_mobile_setting_key, 'class', 'raven-tabs-has-icon' );
						}

						$this->add_inline_editing_attributes( $tab_content_setting_key, 'advanced' );
						?>
						<div <?php echo $this->get_render_attribute_string( $tab_title_mobile_setting_key ); ?>>
							<?php if ( ! empty( $item['tab_icon'] ) ) : ?>
								<span class="raven-tabs-title-icon">
									<i class="<?php echo esc_attr( $item['tab_icon'] ); ?>" aria-hidden="true"></i>
								</span>
							<?php endif; ?>
							<span class="raven-tabs-title-text"><?php echo $item['tab_title']; ?></span>
						</div>
						<div <?php echo $this->get_render_attribute_string( $tab_content_setting_key ); ?>><?php echo $this->parse_text_editor( $item['tab_content'] ); ?></div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php
	}

	protected function _content_template() {
		?>
		<#
		if ( settings.tabs ) {
			var tabsID = view.getIDInt().toString().substr( 0, 3 );

			view.addRenderAttribute( 'tabs', 'class', 'raven-tabs raven-tabs-' + settings.type );
			#>
			<div class="raven-widget-wrapper">
				<div {{{ view.getRenderAttributeString( 'tabs' ) }}}>
					<div class="raven-tabs-list" role="tablist">
						<#
						_.each( settings.tabs, function( item, index ) {
							var tabCount = index + 1,
								hasIcon = '';

							if ( item.tab_icon ) {
								hasIcon = 'raven-tabs-has-icon';
							}
							#>
							<div id="raven-tabs-title-{{ tabsID + tabCount }}" class="raven-tabs-title raven-tabs-desktop-title {{{ hasIcon }}}" role="tab" aria-controls="raven-tabs-content-{{ tabsID + tabCount }}" tabindex="-1" data-tab="{{ tabCount }}">
								<# if ( item.tab_icon ) { #>
									<span class="raven-tabs-title-icon">
										<i class="{{ item.tab_icon }}" aria-hidden="true"></i>
									</span>
								<# } #>
								<span class="raven-tabs-title-text">{{{ item.tab_title }}}</span>
							</div>
						<# } ); #>
					</div>
					<div class="raven-tabs-content-wrapper">
						<#
						_.each( settings.tabs, function( item, index ) {
							var tabCount = index + 1,
								hasIcon = '';
								tabContentKey = view.getRepeaterSettingKey( 'tab_content', 'tabs',index );

							view.addRenderAttribute( tabContentKey, {
								'id': 'raven-tabs-content-' + tabsID + tabCount,
								'class': [ 'raven-tabs-content', 'elementor-clearfix', 'elementor-repeater-item-' + item._id ],
								'role' : 'tabpanel',
								'aria-labelledby' : 'raven-tabs-title-' + tabsID + tabCount,
								'data-tab': tabCount
							} );

							if ( item.tab_icon ) {
								hasIcon = 'raven-tabs-has-icon';
							}

							view.addInlineEditingAttributes( tabContentKey, 'advanced' );
							#>
							<div class="raven-tabs-title raven-tabs-mobile-title {{{ hasIcon }}}" role="tab" tabindex="-1" data-tab="{{ tabCount }}">
								<# if ( item.tab_icon ) { #>
									<span class="raven-tabs-title-icon">
										<i class="{{ item.tab_icon }}" aria-hidden="true"></i>
									</span>
								<# } #>
								<span class="raven-tabs-title-text">{{{ item.tab_title }}}</span>
							</div>
							<div {{{ view.getRenderAttributeString( tabContentKey ) }}}>{{{ item.tab_content }}}</div>
						<# } ); #>
					</div>
				</div>
			</div>
		<# } #>
		<?php
	}
}
