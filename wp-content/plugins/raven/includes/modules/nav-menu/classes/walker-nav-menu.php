<?php
/**
 * Walker class.
 *
 * @package Raven
 *
 * @since 1.0.0
 */

namespace Raven\Modules\Nav_Menu\Classes;

defined( 'ABSPATH' ) || die();

/**
 * Custom nav menu walker.
 *
 * @since 1.0.0
 *
 * Supress this class since this is almost similar to WP Walker_Nav_Menu class with minimal code modification.
 *
 * @SuppressWarnings(PHPMD)
 */
class Walker_Nav_Menu extends \Walker_Nav_Menu {

	/**
	 * Total top level count.
	 *
	 * @since 1.0.0
	 *
	 * @var integer
	 */
	protected $top_level_count = 0;

	/**
	 * Current loop top level index.
	 *
	 * @since 1.0.0
	 *
	 * @var integer
	 */
	protected $top_level_index = 0;

	/**
	 * Display array of elements hierarchically.
	 *
	 * Does not assume any existing order of elements.
	 *
	 * $max_depth = -1 means flatly display every element.
	 * $max_depth = 0 means display all levels.
	 * $max_depth > 0 specifies the number of display levels.
	 *
	 * @since 1.0.0
	 *
	 * @param array $elements  An array of elements.
	 * @param int   $max_depth The maximum hierarchical depth.
	 *
	 * @return string The hierarchical item output.
	 */
	public function walk( $elements, $max_depth ) {
		$args   = array_slice( func_get_args(), 2 );
		$output = '';

		if ( $max_depth < -1 || empty( $elements ) ) {
			return $output;
		}

		$parent_field = $this->db_fields['parent'];

		if ( -1 === $max_depth ) {
			$empty_array = [];
			foreach ( $elements as $e ) {
				$this->display_element( $e, $empty_array, 1, 0, $args, $output );
			}
			return $output;
		}

		$top_level_elements = [];
		$children_elements  = [];
		foreach ( $elements as $e ) {
			if ( empty( $e->$parent_field ) ) {
				$top_level_elements[] = $e;
			} else {
				$children_elements[ $e->$parent_field ][] = $e;
			}
		}

		if ( empty( $top_level_elements ) ) {

			$first = array_slice( $elements, 0, 1 );
			$root  = $first[0];

			$top_level_elements = [];
			$children_elements  = [];
			foreach ( $elements as $e ) {
				if ( $root->$parent_field === $e->$parent_field ) {
					$top_level_elements[] = $e;
				} else {
					$children_elements[ $e->$parent_field ][] = $e;
				}
			}
		}

		// Hack to get count and index of loop via class scope variable.
		$this->top_level_count = count( $top_level_elements );
		foreach ( $top_level_elements as $index => $e ) {
			$this->top_level_index = $index;
			$this->display_element( $e, $children_elements, $max_depth, 0, $args, $output );
		}
		// Immediately reset.
		$this->top_level_count = 0;
		$this->top_level_index = 0;

		if ( ( 0 === $max_depth ) && count( $children_elements ) > 0 ) {
			$empty_array = [];
			foreach ( $children_elements as $orphans ) {
				foreach ( $orphans as $op ) {
					$this->display_element( $op, $empty_array, 1, 0, $args, $output );
				}
			}
		}

		return $output;
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @since 1.0.0
	 *
	 * @see Walker::end_el()
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param WP_Post  $item   Page data object. Not used.
	 * @param int      $depth  Depth of page. Not Used.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = [] ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$output .= "</li>{$n}";

		if ( 0 === $depth && $this->top_level_count ) {
			$output .= apply_filters( 'raven_walker_nav_menu_logo', '', $this->top_level_count, $this->top_level_index );
		}
	}
}
