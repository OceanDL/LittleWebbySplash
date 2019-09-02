<?php

if ( ! class_exists( 'Jet_Meta_Boxes_List_Table' ) ) {
	require jet_engine()->plugin_path( 'includes/list-tables/meta-boxes-list-table.php' );
}

class Jet_Relations_List_Table extends Jet_Meta_Boxes_List_Table {

	/**
	 * Get columns to show in the list table.
	 *
	 * @return array Array of columns.
	 */
	public function get_columns() {

		$columns = array(
			'cb'       => '<input type="checkbox" />',
			'name'     => __( 'Name', 'jet-engine' ),
			'object_1' => __( 'Object 1', 'jet-engine' ),
			'object_2' => __( 'Object 2', 'jet-engine' ),
			'type'     => __( 'Type', 'jet-engine' ),
			'meta_key' => __( 'Meta Key', 'jet-engine' ),
			'actions'  => __( 'Actions', 'jet-engine' ),
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

		$item_name = isset( $item['name'] ) ? $item['name'] : '--';

		return sprintf( '<strong>%s</strong>', esc_html( $item_name ) );
	}

	/**
	 * Object 1
	 *
	 * @param  array   $item Item being shown.
	 * @return string Checkbox column markup.
	 */
	public function column_object_1( $item ) {

		$post_type = $item['post_type_1'];
		$obj       = get_post_type_object( $post_type );

		return ! empty( $obj ) ? $obj->labels->name : __( 'Post type not exists', 'jet-engine' );

	}

	/**
	 * Object 2
	 *
	 * @param  array   $item Item being shown.
	 * @return string Checkbox column markup.
	 */
	public function column_object_2( $item ) {

		$post_type = $item['post_type_2'];
		$obj       = get_post_type_object( $post_type );

		return ! empty( $obj ) ? $obj->labels->name : __( 'Post type not exists', 'jet-engine' );

	}

	/**
	 * Object 2
	 *
	 * @param  array   $item Item being shown.
	 * @return string Checkbox column markup.
	 */
	public function column_meta_key( $item ) {
		return jet_engine()->relations->get_relation_hash( $item['post_type_1'], $item['post_type_2'] );
	}

	/**
	 * Checkbox column.
	 *
	 * @param  array   $item Item being shown.
	 * @return string Checkbox column markup.
	 */
	public function column_type( $item ) {

		$allowed_types = jet_engine()->relations->get_relations_types();
		$type          = isset( $item['type'] ) ? $item['type'] : 'one_to_one';

		return ! empty( $allowed_types[ $type ] ) ? $allowed_types[ $type ] : __( 'Relation not exists', 'jet-engine' );
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
			jet_engine()->relations->get_page_link( 'edit-relation' )
		);

		$delete_link = add_query_arg(
			array(
				'id'     => esc_attr( $item['id'] ),
				'action' => 'delete_item',
			),
			jet_engine()->relations->get_page_link( 'edit-relation' )
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
		$this->items = jet_engine()->relations->data->get_items();
		$this->items = array_filter( $this->items );

		$this->set_pagination_args(
			array(
				'total_items' => jet_engine()->relations->data->total_items(),
				'per_page'    => $per_page,
			)
		);

	}

}
