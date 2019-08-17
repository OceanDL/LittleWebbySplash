<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Jet_Post_Types_List_Table extends WP_List_Table {

	/**
	 * Get columns to show in the list table.
	 *
	 * @return array Array of columns.
	 */
	public function get_columns() {

		$columns = array(
			'cb'      => '<input type="checkbox" />',
			'name'    => __( 'Name', 'jet-engine' ),
			'slug'    => __( 'SLug', 'jet-engine' ),
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
	public function column_cb( $item ) {

		return sprintf( '<input type="checkbox" name="request_id[]" value="%1$s" /><span class="spinner"></span>', esc_attr( $item['id'] ) );
	}

	/**
	 * Checkbox column.
	 *
	 * @param  array   $item Item being shown.
	 * @return string Checkbox column markup.
	 */
	public function column_name( $item ) {

		$labels    = maybe_unserialize( $item['labels'] );
		$item_name = isset( $labels['name'] ) ? $labels['name'] : '--';

		return sprintf( '<strong>%s</strong>', esc_html( $item_name ) );
	}

	/**
	 * Checkbox column.
	 *
	 * @param  array   $item Item being shown.
	 * @return string Checkbox column markup.
	 */
	public function column_slug( $item ) {
		return esc_attr( $item['slug'] );
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
			jet_engine()->cpt->get_page_link( 'edit' )
		);

		$delete_link = add_query_arg(
			array(
				'id'     => esc_attr( $item['id'] ),
				'action' => 'delete_item',
			),
			jet_engine()->cpt->get_page_link( 'edit' )
		);

		$actions_list = array(
			sprintf( '<a href="%1$s">%2$s</a>', $edit_link, __( 'Edit', 'jet-engine' ) ),
			sprintf( '<a href="%1$s" class="cpt-delete">%2$s</a>', $delete_link, __( 'Delete', 'jet-engine' ) ),
		);

		$actions_string = implode( ' | ', $actions_list );

		return $actions_string;
	}

	/**
	 * Get a list of sortable columns.
	 *
	 * @return array Default sortable columns.
	 */
	protected function get_sortable_columns() {
		return array();
	}

	/**
	 * Default primary column.
	 *
	 * @return string Default primary column name.
	 */
	protected function get_default_primary_column_name() {
		return 'slug';
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
		$per_page    = 20;
		$this->items = jet_engine()->cpt->data->get_items();
		$this->items = array_filter( $this->items );

		$this->set_pagination_args(
			array(
				'total_items' => jet_engine()->cpt->data->total_items(),
				'per_page'    => $per_page,
			)
		);

	}

}
