<?php

if ( ! class_exists( 'Jet_Post_Types_List_Table' ) ) {
	require jet_engine()->plugin_path( 'includes/list-tables/post-types-list-table.php' );
}

class Jet_Taxonomies_List_Table extends Jet_Post_Types_List_Table {

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
			jet_engine()->taxonomies->get_page_link( 'edit-tax' )
		);

		$delete_link = add_query_arg(
			array(
				'id'     => esc_attr( $item['id'] ),
				'action' => 'delete_item',
			),
			jet_engine()->taxonomies->get_page_link( 'edit-tax' )
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
		$per_page    = 20;
		$this->items = jet_engine()->taxonomies->data->get_items();
		$this->items = array_filter( $this->items );

		$this->set_pagination_args(
			array(
				'total_items' => jet_engine()->taxonomies->data->total_items(),
				'per_page'    => $per_page,
			)
		);

	}

}
