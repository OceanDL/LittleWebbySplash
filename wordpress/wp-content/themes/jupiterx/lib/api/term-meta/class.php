<?php
// @codingStandardsIgnoreFile
/**
 * Class to handle the Jupiter Term Meta Workflow.
 *
 * @package JupiterX\Framework\API\Term_Meta
 *
 * @since 1.0.0
 */

/**
 * Handle the Jupiter Term Meta workflow.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Term_Meta
 */
final class _JupiterX_Term_Meta {

	/**
	 * Field section.
	 *
	 * @var string
	 */
	private $section;

	/**
	 * Constructor.
	 *
	 * @param string $section Field section.
	 */
	public function __construct( $section ) {
		$this->section = $section;
		$this->do_once();
		add_action( jupiterx_get( 'taxonomy' ) . '_edit_form_fields', array( $this, 'fields' ) );
	}

	/**
	 * Trigger actions only once.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function do_once() {
		static $once = false;

		if ( ! $once ) {
			add_action( jupiterx_get( 'taxonomy' ) . '_edit_form', array( $this, 'nonce' ) );
			add_action( 'edit_term', array( $this, 'save' ) );
			add_action( 'delete_term', array( $this, 'delete' ), 10, 3 );

			$once = true;
		}
	}

	/**
	 * Term meta nonce.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function nonce() {
		?>
		<input type="hidden" name="jupiterx_term_meta_nonce" value="<?php echo esc_attr( wp_create_nonce( 'jupiterx_term_meta_nonce' ) ); ?>" />
		<?php
	}

	/**
	 * Fields content.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function fields() {
		jupiterx_remove_action( 'jupiterx_field_label' );
		jupiterx_modify_action_hook( 'jupiterx_field_description', 'jupiterx_field_wrap_after_markup' );
		jupiterx_modify_markup( 'jupiterx_field_description', 'p' );
		jupiterx_add_attribute( 'jupiterx_field_description', 'class', 'description' );

		foreach ( jupiterx_get_fields( 'term_meta', $this->section ) as $field ) {
			?>
			<tr class="form-field">
				<th scope="row">
					<?php echo esc_html( jupiterx_field_label( $field ) ); ?>
				</th>
				<td>
					<?php echo esc_html( jupiterx_field( $field ) ); ?>
				</td>
			</tr>
			<?php
		}
	}

	/**
	 * Save Term Meta.
	 *
	 * @since 1.0.0
	 *
	 * @param int $term_id Term ID.
	 *
	 * @return int
	 */
	public function save( $term_id ) {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return $term_id;
		}

		if ( ! wp_verify_nonce( jupiterx_post( 'jupiterx_term_meta_nonce' ), 'jupiterx_term_meta_nonce' ) ) {
			return $term_id;
		}

		$fields = jupiterx_post( 'jupiterx_fields' );

		if ( ! $fields ) {
			return $term_id;
		}

		foreach ( $fields as $field => $value ) {
			update_option( "jupiterx_term_{$term_id}_{$field}", stripslashes_deep( $value ) );
		}
	}

	/**
	 * Delete Term Meta.
	 *
	 * @since 1.0.0
	 *
	 * @param int $term_id Term ID.
	 *
	 * @return void
	 */
	public function delete( $term_id ) {
		global $wpdb;

		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $wpdb->options WHERE option_name LIKE %s",
				"jupiterx_term_{$term_id}_%"
			)
		);
	}
}
