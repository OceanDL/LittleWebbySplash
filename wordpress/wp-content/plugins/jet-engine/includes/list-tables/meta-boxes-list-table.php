<?php

if ( ! class_exists( 'Jet_Post_Types_List_Table' ) ) {
	require jet_engine()->plugin_path( 'includes/list-tables/post-types-list-table.php' );
}

class Jet_Meta_Boxes_List_Table extends Jet_Post_Types_List_Table {

	/**
	 * Get columns to show in the list table.
	 *
	 * @return array Array of columns.
	 */
	public function get_columns() {

		$columns = array(
			'cb'      => '<input type="checkbox" />',
			'name'    => __( 'Name', 'jet-engine' ),
			'object'  => __( 'Object', 'jet-engine' ),
			'where'   => __( 'Where', 'jet-engine' ),
			'actions' => __( 'Actions', 'jet-engine' ),
		);

		return $columns;
	}

	/**
	 * Checkbox column.
	 *
	 * @param  array   $item Item being shown.
	 * @return string Checkbox column markup.
	 */
	public function column_name( $item ) {

		$args      = isset( $item['args'] ) ? $item['args'] : array();
		$item_name = isset( $args['name'] ) ? $args['name'] : '--';

		return sprintf( '<strong>%s</strong>', esc_html( $item_name ) );
	}

	/**
	 * Checkbox column.
	 *
	 * @param  array   $item Item being shown.
	 * @return string Checkbox column markup.
	 */
	public function column_object( $item ) {

		$args        = isset( $item['args'] ) ? $item['args'] : array();
		$object_type = isset( $args['object_type'] ) ? $args['object_type'] : 'post';

		$label = ( 'tax' === $object_type ) ? __( 'Taxonomy', 'jet-engine' ) : __( 'Post', 'jet-engine' );

		return esc_html( $label );
	}

	/**
	 * Checkbox column.
	 *
	 * @param  array   $item Item being shown.
	 * @return string Checkbox column markup.
	 */
	public function column_where( $item ) {

		$args        = isset( $item['args'] ) ? $item['args'] : array();
		$object_type = isset( $args['object_type'] ) ? $args['object_type'] : 'post';
		$result      = array();

		if ( 'post' === $object_type ) {
			$where = isset( $args['allowed_post_type'] ) ? $args['allowed_post_type'] : array();
			$post_types = jet_engine()->listings->get_post_types_for_options();
			foreach ( $where as $post_type ) {
				$result[] = isset( $post_types[ $post_type ] ) ? $post_types[ $post_type ] : false;
			}
		} else {
			$where = isset( $args['allowed_tax'] ) ? $args['allowed_tax'] : array();
			$taxes = jet_engine()->listings->get_taxonomies_for_options();
			foreach ( $where as $tax ) {
				$result[] = isset( $taxes[ $tax ] ) ? $taxes[ $tax ] : false;
			}
		}

		return implode( ', ', array_filter( $result ) );
	}

	/**
	 * Checkbox column.
	 *
	 * @param  array   $item Item being shown.
	 * @return string Checkbox column markup.
	 */
	public function column_actions( $item ) {

		$edit_link = add_query_arg(
			array(
				'id' => esc_attr( $item['id'] ),
			),
			jet_engine()->meta_boxes->get_page_link( 'edit-meta' )
		);

		$delete_link = add_query_arg(
			array(
				'id'     => esc_attr( $item['id'] ),
				'action' => 'delete_item',
			),
			jet_engine()->meta_boxes->get_page_link( 'edit-meta' )
		);

		$actions_list = array(
			sprintf( '<a href="%1$s">%2$s</a>', $edit_link, __( 'Edit', 'jet-engine' ) ),
			sprintf( '<a href="%1$s" class="cpt-delete">%2$s</a>', $delete_link, __( 'Delete', 'jet-engine' ) ),
		);

		$actions_string = implode( ' | ', $actions_list );

		return $actions_string;
	}

	/**
	 * Prepare items to output.
	 */
	public function prepare_items() {

		global $wpdb;

		$primary               = $this->get_primary_column_name();
		$this->_column_headers = array(
			$this->get_columns(),
			array(),
			$this->get_sortable_columns(),
			$primary,
		);

		$this->items = array();
		$per_page    = 40;
		$this->items = jet_engine()->meta_boxes->data->get_items();
		$this->items = array_filter( $this->items );

		$this->set_pagination_args(
			array(
				'total_items' => jet_engine()->meta_boxes->data->total_items(),
				'per_page'    => $per_page,
			)
		);

	}

}
