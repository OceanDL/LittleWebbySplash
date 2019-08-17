<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Jet_Engine_Custom_Image_Tag extends Elementor\Core\DynamicTags\Data_Tag {

	public function get_name() {
		return 'jet-post-custom-image';
	}

	public function get_title() {
		return __( 'Custom Image', 'jet-engine' );
	}

	public function get_group() {
		return Jet_Engine_Dynamic_Tags_Module::JET_GROUP;
	}

	public function get_categories() {
		return array(
			Jet_Engine_Dynamic_Tags_Module::IMAGE_CATEGORY,
		);
	}

	protected function _register_controls() {

		$this->add_control(
			'img_field',
			array(
				'label'   => __( 'Field', 'jet-engine' ),
				'type'    => Elementor\Controls_Manager::SELECT,
				'options' => $this->get_meta_fields(),
			)
		);

		$plugin   = Elementor\Plugin::instance();
		$document = $plugin->documents->get_doc_or_auto_save( $plugin->editor->get_post_id() );

		if ( $document ) {

			$config = $document->get_config();

			if ( isset( $config['type'] ) && 'archive' === $config['type'] ) {

				$this->add_control(
					'tax_desc',
					array(
						'label'     => __( 'Queried term thumbnial', 'jet-engine' ),
						'raw'       => __( 'For taxonomy archives only', 'jet-engine' ),
						'type'      => Elementor\Controls_Manager::RAW_HTML,
						'separator' => 'before',
					)
				);

				$this->add_control(
					'tax_thumb_meta',
					array(
						'label'       => __( 'Thumbnail meta key', 'jet-engine' ),
						'type'        => Elementor\Controls_Manager::TEXT,
						'label_block' => true,
						'separator'   => 'after',
					)
				);

			}

		}

		$this->add_control(
			'fallback',
			array(
				'label' => __( 'Fallback', 'jet-engine' ),
				'type'  => Elementor\Controls_Manager::MEDIA,
			)
		);

	}

	public function get_value( array $options = array() ) {

		$meta_field = $this->get_settings( 'img_field' );
		$thumb_key  = $this->get_settings( 'tax_thumb_meta' );

		if ( empty( $meta_field ) && empty( $thumb_key ) ) {
			return $this->get_settings( 'fallback' );
		}

		$current_object = jet_engine()->listings->data->get_current_object();

		if ( ! empty( $thumb_key ) ) {

			if ( ! $current_object ) {
				return $this->get_settings( 'fallback' );
			}

			$class = get_class( $current_object );

			if ( 'WP_Term' === $class ) {

				$img = get_term_meta( $current_object->term_id, $thumb_key, true );

				if ( $img ) {
					return array(
						'id'  => $img,
						'url' => wp_get_attachment_image_src( $img, 'full' )[0],
					);
				}

				return $this->get_settings( 'fallback' );

			}

		}

		if ( empty( $meta_field ) ) {
			return $this->get_settings( 'fallback' );
		}

		if ( ! $current_object ) {
			return $this->get_settings( 'fallback' );
		}

		$source = jet_engine()->listings->data->get_listing_source();
		$img_id = false;

		if ( 'post_thumbnail' === $meta_field ) {

			$post = jet_engine()->listings->data->get_current_object();

			if ( ! has_post_thumbnail( $post->ID ) ) {
				return $this->get_settings( 'fallback' );
			}

			if ( 'posts' === $source ) {
				$img_id = get_post_thumbnail_id( $post->ID );
			}

		} else {
			$img_id = jet_engine()->listings->data->get_meta( $meta_field );
		}

		if ( $img_id ) {
			return array(
				'id'  => $img_id,
				'url' => wp_get_attachment_image_src( $img_id, 'full' )[0],
			);
		} else {
			return $this->get_settings( 'fallback' );
		}

	}

	private function get_meta_fields() {

		$options = array(
			''               => __( 'Select...', 'jet-engine' ),
			'post_thumbnail' => __( 'Post thumbnail', 'jet-engine' ),
		);

		$meta_fields = jet_engine()->listings->data->get_listing_meta_fields();

		if ( ! $meta_fields ) {
			return $options;
		}

		foreach ( $meta_fields as $field ) {
			if ( 'media' === $field['type'] ) {
				$options[ $field['name'] ] = $field['title'];
			}
		}

		return $options;

	}
}
