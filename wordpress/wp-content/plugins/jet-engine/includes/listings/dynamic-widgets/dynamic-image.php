<?php
namespace Elementor;

use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Listing_Dynamic_Image_Widget extends Widget_Base {

	private $source = false;

	public function get_name() {
		return 'jet-listing-dynamic-image';
	}

	public function get_title() {
		return __( 'Dynamic Image', 'jet-engine' );
	}

	public function get_icon() {
		return 'jet-engine-icon-2';
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
			'dynamic_image_source',
			array(
				'label'   => __( 'Source', 'jet-engine' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'post_thumbnail',
				'options' => $this->get_dynamic_sources( 'media' ),
			)
		);

		/**
		 * Add 3rd-party controls for sources
		 */
		do_action( 'jet-engine/listings/dynamic-image/source-controls', $this );

		$this->add_control(
			'dynamic_image_source_custom',
			array(
				'label'       => __( 'Or enter custom meta field key', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'description' => __( 'Note: this filed will override Source value', 'jet-engine' ),
			)
		);

		$this->add_control(
			'dynamic_image_size',
			array(
				'label'       => __( 'Image Size', 'jet-engine' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'full',
				'options'     => $this->get_image_sizes(),
				'description' => __( 'Note: this option will work only if image stored as attachment ID', 'jet-engine' ),
			)
		);

		$this->add_control(
			'linked_image',
			array(
				'label'        => __( 'Linked image', 'jet-engine' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'jet-engine' ),
				'label_off'    => __( 'No', 'jet-engine' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'image_link_source',
			array(
				'label'     => __( 'Link Source', 'jet-engine' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '_permalink',
				'options'   => $this->get_dynamic_sources( 'links' ),
				'condition' => array(
					'linked_image' => 'yes',
				),
			)
		);

		/**
		 * Add 3rd-party controls for sources
		 */
		do_action( 'jet-engine/listings/dynamic-image/link-source-controls', $this );

		$this->add_control(
			'image_link_source_custom',
			array(
				'label'       => __( 'Or enter post meta field key', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'description' => __( 'Note: this filed will override Meta Field value', 'jet-engine' ),
				'condition'   => array(
					'linked_image' => 'yes',
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
				'condition'    => array(
					'linked_image' => 'yes',
				),
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
				'condition'   => array(
					'linked_image' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'image_alignment',
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
					$this->css_selector() => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_style',
			array(
				'label'      => __( 'Image', 'jet-engine' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'image_border',
				'label'          => __( 'Border', 'jet-engine' ),
				'placeholder'    => '1px',
				'selector'       => $this->css_selector( ' img' ),
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-engine' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					$this->css_selector( ' img' ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	 * Returns image size array in slug => name format
	 *
	 * @return  array
	 */
	public function get_image_sizes() {

		global $_wp_additional_image_sizes;

		$sizes  = get_intermediate_image_sizes();
		$result = array();

		foreach ( $sizes as $size ) {
			if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
				$result[ $size ] = ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) );
			} else {
				$result[ $size ] = sprintf(
					'%1$s (%2$sx%3$s)',
					ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) ),
					$_wp_additional_image_sizes[ $size ]['width'],
					$_wp_additional_image_sizes[ $size ]['height']
				);
			}
		}

		return array_merge( array( 'full' => __( 'Full', 'jet-engine' ), ), $result );
	}

	/**
	 * Get meta fields for post type
	 *
	 * @return array
	 */
	public function get_dynamic_sources( $for = 'media' ) {

		if ( 'media' === $for ) {
			$default = array(
				'post_thumbnail' => __( 'Post thumbnail', 'jet-engine' ),
			);
		} else {
			$default = array(
				'_permalink' => __( 'Permalink', 'jet-engine' ),
			);
		}

		$result      = array();
		$meta_fields = jet_engine()->listings->data->get_listing_meta_fields();

		if ( ! empty( $meta_fields ) ) {

			foreach ( $meta_fields as $field ) {
				if ( 'media' === $for && 'media' === $field['type'] ) {
					$result[ $field['name'] ] = $field['title'];
				} elseif ( 'media' !== $for ) {
					$result[ $field['name'] ] = $field['title'];
				} elseif ( jet_engine()->relations->is_relation_key( $field['name'] ) ) {
					$result[ $field['name'] ] = $field['title'];
				}

			}

		}

		$result = apply_filters(
			'jet-engine/listings/dynamic-image/fields',
			array_merge( $default, $result ),
			$for
		);

		return $result;

	}

	/**
	 * Render image
	 *
	 * @return [type] [description]
	 */
	public function render_image( $settings ) {

		$listing_source = jet_engine()->listings->data->get_listing_source();
		$source         = isset( $settings['dynamic_image_source'] ) ? $settings['dynamic_image_source'] : false;
		$size           = isset( $settings['dynamic_image_size'] ) ? $settings['dynamic_image_size'] : 'full';
		$custom         = isset( $settings['dynamic_image_source_custom'] ) ? $settings['dynamic_image_source_custom'] : false;

		if ( ! $source && ! $custom ) {
			return;
		}

		if ( $custom ) {
			$this->render_image_by_meta_field( $custom, $size );
			return;
		}

		if ( 'post_thumbnail' === $source ) {

			if ( 'posts' === $listing_source ) {

				$post = jet_engine()->listings->data->get_current_object();

				if ( ! has_post_thumbnail( $post->ID ) ) {
					return;
				}

				echo get_the_post_thumbnail( $post->ID, $size, array( 'alt' => get_the_title() ) );
				return;

			}

		} else {
			$this->render_image_by_meta_field( $source, $size );
		}

	}

	public function render_image_by_meta_field( $field, $size = 'full' ) {

		$custom_output = apply_filters(
			'jet-engine/listings/dynamic-image/custom-image',
			false,
			$this->get_settings()
		);

		if ( $custom_output ) {
			echo $custom_output;
		}

		$image = false;

		if ( jet_engine()->relations->is_relation_key( $field ) ) {
			$related_post = get_post_meta( get_the_ID(), $field, false );
			if ( ! empty( $related_post ) ) {
				$related_post = $related_post[0];
				if ( has_post_thumbnail( $related_post ) ) {
					$image = get_post_thumbnail_id( $related_post );
				}
			}
		} else {
			$image = jet_engine()->listings->data->get_meta( $field );
		}

		if ( ! $image ) {
			return;
		}

		if ( filter_var( $image, FILTER_VALIDATE_URL ) ) {
			printf( '<img src="%1$s" alt="%2$s">', $image, get_the_title() );
		} else {
			echo wp_get_attachment_image( $image, $size, false, array( 'alt' => get_the_title() ) );
		}

	}

	public function get_image_url( $settings ) {

		$is_linked = $settings['linked_image'];

		if ( ! $is_linked ) {
			return false;
		}

		$source = $settings['image_link_source'];
		$custom = $settings['image_link_source_custom'];

		$url = apply_filters(
			'jet-engine/listings/dynamic-image/custom-url',
			false,
			$settings
		);

		if ( false !== $url ) {
			return $url;
		}

		if ( $custom ) {
			$url = jet_engine()->listings->data->get_meta( $custom );
		} elseif ( '_permalink' === $source ) {
			$url = jet_engine()->listings->data->get_current_object_permalink();
		} elseif ( $source ) {
			$url = jet_engine()->listings->data->get_meta( $source );
		}

		return $url;

	}

	protected function render() {

		$base_class = $this->get_name();
		$settings   = $this->get_settings();

		printf( '<div class="%1$s jet-listing">', $base_class );

			do_action( 'jet-engine/listing/dynamic-image/before-image', $this );

			$image_url = $this->get_image_url( $settings );

			if ( $image_url ) {

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

				printf( '<a href="%1$s" class="%2$s__link"%3$s%4$s>', $image_url, $base_class, $rel, $target );
			}

			$this->render_image( $settings );

			if ( $image_url ) {
				echo '</a>';
			}

			do_action( 'jet-engine/listing/dynamic-image/after-image', $this );

		echo '</div>';

	}

}
