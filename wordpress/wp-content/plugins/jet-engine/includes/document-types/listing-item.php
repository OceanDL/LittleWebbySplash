<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Jet_Listing_Item_Document extends Elementor\Core\Base\Document {

	public function get_name() {
		return jet_engine()->listings->get_id();
	}

	public static function get_title() {
		return __( 'Listing Item', 'jet-engine' );
	}

	protected function _register_controls() {

		parent::_register_controls();

		$this->start_controls_section(
			'jet_listing_settings',
			array(
				'label' => __( 'Listing Settings', 'jet-engine' ),
				'tab' => Elementor\Controls_Manager::TAB_SETTINGS,
			)
		);

		$this->add_control(
			'reload_notice',
			array(
				'type' => Elementor\Controls_Manager::RAW_HTML,
				'raw'  => __( '<b>Please note:</b> You need to reload page after applying new source, changing post type or taxonomy. New meta fields and options for dynamic fields will be applied only after reloading.', 'jet-engine' ),
			)
		);

		$this->add_control(
			'listing_source',
			array(
				'label'   => esc_html__( 'Listing source:', 'jet-engine' ),
				'type'    => Elementor\Controls_Manager::SELECT,
				'default' => 'posts',
				'options' => array(
					'posts' => esc_html__( 'Posts', 'jet-engine' ),
					'terms' => esc_html__( 'Terms', 'jet-engine' ),
				),
			)
		);

		$this->add_control(
			'listing_post_type',
			array(
				'label'   => esc_html__( 'From post type:', 'jet-engine' ),
				'type'    => Elementor\Controls_Manager::SELECT,
				'options' => jet_engine()->listings->get_post_types_for_options(),
				'condition' => array(
					'listing_source' => 'posts',
				),
			)
		);

		$this->add_control(
			'listing_tax',
			array(
				'label'   => esc_html__( 'From taxonomy:', 'jet-engine' ),
				'type'    => Elementor\Controls_Manager::SELECT,
				'default' => 'right',
				'options' => jet_engine()->listings->get_taxonomies_for_options(),
				'condition' => array(
					'listing_source' => 'terms',
				),
			)
		);

		$this->add_control(
			'preview_width',
			array(
				'label'      => esc_html__( 'Preview Width', 'jet-engine' ),
				'type'       => Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 300,
						'max' => 1200,
					),
				),
				'selectors'  => array(
					'.jet-listing-item.single-jet-engine .elementor' => 'width: {{SIZE}}{{UNIT}}; margin-left: auto; margin-right: auto;',
				),
			)
		);

		$this->end_controls_section();

	}

	public function get_preview_as_query_args() {

		$preview_id = (int) $this->get_settings( 'preview_id' );
		$source     = $this->get_settings( 'listing_source' );
		$post_type  = $this->get_settings( 'listing_post_type' );
		$tax        = $this->get_settings( 'listing_tax' );
		$args       = false;

		jet_engine()->listings->data->set_listing( $this );

		switch ( $source ) {

			case 'posts':

				$post = get_posts( array(
					'post_type'        => $post_type,
					'numberposts'      => 1,
					'orderby'          => 'date',
					'order'            => 'DESC',
					'suppress_filters' => false,
				) );

				if ( ! empty( $post ) ) {

					jet_engine()->listings->data->set_current_object( $post[0] );

					$args = array(
						'post_type' => $post_type,
						'p'         => $post[0]->ID,
					);

				}

				break;

			case 'terms':

				$terms = get_terms( array(
					'taxonomy'   => $tax,
					'hide_empty' => false,
				) );

				if ( ! empty( $terms ) ) {

					jet_engine()->listings->data->set_current_object( $terms[0] );

					$args = array(
						'tax_query' => array(
							array(
								'taxonomy' => $tax,
								'field'    => 'slug',
								'terms'    => $terms[0]->slug,
							),
						),
					);

				}

				break;
		}

		return $args;
	}

	public function get_elements_raw_data( $data = null, $with_html_content = false ) {

		jet_engine()->listings->switch_to_preview_query();

		$editor_data = parent::get_elements_raw_data( $data, $with_html_content );

		jet_engine()->listings->restore_current_query();

		return $editor_data;
	}

	public function render_element( $data ) {

		jet_engine()->listings->switch_to_preview_query();

		$render_html = parent::render_element( $data );

		jet_engine()->listings->restore_current_query();

		return $render_html;
	}

	public function get_elements_data( $status = 'publish' ) {

		if ( ! isset( $_GET[ jet_engine()->post_type->slug() ] ) || ! isset( $_GET['preview'] ) ) {
			return parent::get_elements_data( $status );
		}

		jet_engine()->listings->switch_to_preview_query();

		$elements = parent::get_elements_data( $status );

		jet_engine()->listings->restore_current_query();

		return $elements;
	}

}
