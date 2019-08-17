<?php
/**
 * Radio filter class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Radio_Filter' ) ) {

	/**
	 * Define Jet_Smart_Filters_Radio_Filter class
	 */
	class Jet_Smart_Filters_Radio_Filter extends Jet_Smart_Filters_Filter_Base {

		/**
		 * Get provider name
		 *
		 * @return string
		 */
		public function get_name() {
			return __( 'Radio', 'jet-smart-filters' );
		}

		/**
		 * Get provider ID
		 *
		 * @return string
		 */
		public function get_id() {
			return 'radio';
		}

		/**
		 * Get provider wrapper selector
		 *
		 * @return string
		 */
		public function get_scripts() {
			return false;
		}

		/**
		 * Return filter value in human-readable format
		 *
		 * @param  string $input Filter value to format.
		 * @param  int $filter_id Filter ID.
		 *
		 * @return string
		 */
		public function get_verbosed_val( $input, $filter_id ) {

			if ( 'false' === $input ) {
				return;
			}

			$args = $this->prepare_args( array(
				'filter_id' => $filter_id
			) );

			if ( empty( $args['options'] ) ) {
				return;
			}

			$options = $args['options'];

			if ( ! is_string( $input ) ) {
				return;
			}

			return isset( $options[ $input ] ) ? $options[ $input ] : false;

		}

		/**
		 * Prepare filter template argumnets
		 *
		 * @param  [type] $args [description]
		 *
		 * @return [type]       [description]
		 */
		public function prepare_args( $args ) {

			$filter_id        = $args['filter_id'];
			$content_provider = isset( $args['content_provider'] ) ? $args['content_provider'] : false;
			$apply_type       = isset( $args['apply_type'] ) ? $args['apply_type'] : false;

			if ( ! $filter_id ) {
				return false;
			}

			$source             = get_post_meta( $filter_id, '_data_source', true );
			$is_custom_checkbox = get_post_meta( $filter_id, '_is_custom_checkbox', true );
			$filter_label       = get_post_meta( $filter_id, '_filter_label', true );
			$options            = array();
			$by_parents         = false;

			switch ( $source ) {
				case 'taxonomies':
					$tax        = get_post_meta( $filter_id, '_source_taxonomy', true );
					$only_child = get_post_meta( $filter_id, '_only_child', true );
					$only_child = filter_var( $only_child, FILTER_VALIDATE_BOOLEAN );

					if ( ! isset( $args['ignore_parents'] ) || true !== $args['ignore_parents'] ) {
						$by_parents = get_post_meta( $filter_id, '_group_by_parent', true );
						$by_parents = filter_var( $by_parents, FILTER_VALIDATE_BOOLEAN );
					}

					if ( true === $by_parents ) {
						$options = jet_smart_filters()->data->get_terms_objects( $tax, $only_child );
					} else {
						$options = jet_smart_filters()->data->get_terms_for_options( $tax, $only_child );
					}

					$query_type = 'tax_query';
					$query_var  = $tax;
					break;

				case 'posts':

					$post_type = get_post_meta( $filter_id, '_source_post_type', true );
					$args      = array(
						'post_type' => $post_type,
						'post_status' => 'publish',
						'posts_per_page' => -1
					);

					$posts      = get_posts( $args );
					$query_type = 'meta_query';
					$query_var  = get_post_meta( $filter_id, '_query_var', true );

					if ( ! empty( $posts ) ) {
						$options = wp_list_pluck( $posts, 'post_title', 'ID' );
					}

					break;

				case 'custom_fields':
					$custom_field = get_post_meta( $filter_id, '_source_custom_field', true );
					$options      = get_post_meta( get_the_ID(), $custom_field, true );
					$options      = jet_smart_filters()->data->maybe_parse_repeater_options( $options );
					$query_type   = 'meta_query';
					$query_var    = get_post_meta( $filter_id, '_query_var', true );
					break;

				case 'manual_input':
					$options    = get_post_meta( $filter_id, '_source_manual_input', true );
					$options    = wp_list_pluck( $options, 'label', 'value' );
					$query_type = 'meta_query';
					$query_var  = get_post_meta( $filter_id, '_query_var', true );
					break;
			}

			return array(
				'options'          => $options,
				'query_type'       => $query_type,
				'query_var'        => $query_var,
				'by_parents'       => $by_parents,
				'query_var_suffix' => ( 'true' === $is_custom_checkbox ) ? 'is_custom_checkbox' : false,
				'content_provider' => $content_provider,
				'apply_type'       => $apply_type,
				'filter_id'        => $filter_id,
				'filter_label'     => $filter_label,
			);

		}

	}

}
