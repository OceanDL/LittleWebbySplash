<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Skins_Export' ) ) {

	/**
	 * Define Jet_Engine_Skins_Export class
	 */
	class Jet_Engine_Skins_Export {

		public $nonce = 'jet-engine-export';
		public $id    = null;

		public function __construct() {
			add_action( 'admin_footer', array( $this, 'export_popup' ) );
			$this->process_export();
		}

		/**
		 * Export skin
		 *
		 * @return [type] [description]
		 */
		public function process_export() {

			if ( empty( $_GET['export_skin'] ) ) {
				return;
			}

			if ( ! current_user_can( 'export' ) ) {
				return;
			}

			$this->id = null;

			$map = array(
				array(
					'key' => 'post_types',
					'var' => '_jet_post_types',
					'cb'  => array( $this, 'export_post_types' ),
				),
				array(
					'key' => 'taxonomies',
					'var' => '_jet_taxonomies',
					'cb'  => array( $this, 'export_taxonomies' ),
				),
				array(
					'key' => 'listings',
					'var' => '_jet_listings',
					'cb'  => array( $this, 'export_listings' ),
				),
				array(
					'key' => 'meta_boxes',
					'var' => '_jet_meta_boxes',
					'cb'  => array( $this, 'export_meta_boxes' ),
				),
				array(
					'key' => 'content',
					'var' => '_jet_content',
					'cb'  => array( $this, 'export_content' ),
				),
			);

			$json = array();

			foreach ( $map as $item ) {
				if ( empty( $_POST[ $item['var'] ] ) ) {
					$json[ $item['key'] ] = array();
				} else {
					$json[ $item['key'] ] = call_user_func( $item['cb'], $_POST[ $item['var'] ] );
				}
			}

			$file     = json_encode( $json );
			$filename = 'skin-export-' . $this->id . '.json';

			set_time_limit( 0 );

			@session_write_close();

			if( function_exists( 'apache_setenv' ) ) {
				@apache_setenv('no-gzip', 1);
			}

			@ini_set( 'zlib.output_compression', 'Off' );

			nocache_headers();

			header( "Robots: none" );
			header( "Content-Type: application/json" );
			header( "Content-Description: File Transfer" );
			header( "Content-Disposition: attachment; filename=\"" . $filename . "\";" );
			header( "Content-Transfer-Encoding: binary" );

			// Set the file size header
			header( "Content-Length: " . strlen( $file ) );

			echo $file;
			die();

		}

		/**
		 * Export post types
		 *
		 * @return void
		 */
		public function export_post_types( $post_types = array() ) {

			if ( ! is_array( $post_types ) ) {
				$post_types = array( $post_types );
			}

			$this->id .= implode( '', $post_types );

			return jet_engine()->cpt->data->get_raw( array( 'id' => $post_types ) );

		}

		/**
		 * Export meta boxes
		 *
		 * @param  array  $meta_boxes meta boxes to export
		 * @return array
		 */
		public function export_meta_boxes( $meta_boxes = array() ) {

			$all_boxes = jet_engine()->meta_boxes->data->get_raw();
			$result    = array();

			$result = array_filter( $all_boxes, function( $box ) use ( $meta_boxes ) {
				return in_array( $box['id'], $meta_boxes );
			} );

			return $result;
		}

		/**
		 * Export post types
		 *
		 * @return void
		 */
		public function export_taxonomies( $taxonomies = array() ) {

			if ( ! is_array( $taxonomies ) ) {
				$taxonomies = array( $taxonomies );
			}

			$this->id .= implode( '', $taxonomies );

			return jet_engine()->taxonomies->data->get_raw( array( 'id' => $taxonomies ) );

		}

		/**
		 * Export sample content
		 * @param  string $export [description]
		 * @return [type]         [description]
		 */
		public function export_content( $export = '' ) {

			if ( 'yes' !== $export ) {
				return;
			}

			$this->id .= '1';

			$post_types = ! empty( $_POST['_jet_post_types'] ) ? $_POST['_jet_post_types'] : array();
			$taxonomies = ! empty( $_POST['_jet_taxonomies'] ) ? $_POST['_jet_taxonomies'] : array();
			$result     = array();

			if ( ! empty( $post_types ) ) {
				$result['posts'] = $this->export_sample_posts( $post_types );
			}

			if ( ! empty( $taxonomies ) ) {
				$result['terms'] = $this->export_sample_terms( $taxonomies );
			}

			return $result;
		}

		/**
		 * Export sample posts
		 *
		 * @return [type] [description]
		 */
		public function export_sample_posts( $post_types ) {

			$slugs = array();

			foreach ( jet_engine()->cpt->get_items() as $post_type ) {
				if ( in_array( $post_type['id'], $post_types ) ) {
					$slugs[] = $post_type['slug'];
				}
			}

			$result = array();

			foreach ( $slugs as $slug ) {

				$posts = get_posts( array(
					'post_type'      => $slug,
					'posts_per_page' => 1,
				) );

				if ( empty( $posts ) ) {
					continue;
				}

				$post       = $posts[0];
				$meta_input = array(
					'_thumbnail_id' => array(
						'media' => true,
						'url'   => get_the_post_thumbnail_url( $post->ID, 'full' )
					),
				);

				$meta_fields = jet_engine()->cpt->get_meta_fields_for_object( $slug );

				if ( ! empty( $meta_fields ) ) {
					foreach ( $meta_fields as $field ) {
						if ( 'media' === $field['type'] ) {
							$img_id = get_post_meta( $post->ID, $field['name'], true );
							if ( $img_id ) {
								$meta_input[ $field['name'] ] = array(
									'media' => true,
									'url'   => wp_get_attachment_image_url( $img_id, 'full' )
								);
							}
						} else {
							$meta_input[ $field['name'] ] = get_post_meta( $post->ID, $field['name'], true );
						}
					}
				}

				$result[] = array(
					'post_title'   => $post->post_title,
					'post_type'    => $post->post_type,
					'post_name'    => $post->post_name,
					'post_content' => $post->post_content,
					'post_excerpt' => $post->post_excerpt,
					'meta_input'   => $meta_input,
				);

			}

			return $result;

		}

		/**
		 * Export sample terms
		 *
		 * @return [type] [description]
		 */
		public function export_sample_terms( $taxonomies ) {

			$slugs = array();

			foreach ( jet_engine()->taxonomies->get_items() as $tax ) {
				if ( in_array( $tax['id'], $taxonomies ) ) {
					$slugs[] = $tax['slug'];
				}
			}

			foreach ( $slugs as $slug ) {

				$terms = get_terms( array(
					'taxonomy'   => $slug,
					'hide_empty' => false,
				) );

				if ( empty( $terms ) ) {
					continue;
				}

				$term       = $terms[0];
				$meta_input = array();

				$meta_fields = jet_engine()->taxonomies->get_meta_fields_for_object( $slug );

				if ( ! empty( $meta_fields ) ) {
					foreach ( $meta_fields as $field ) {
						if ( 'media' === $field['type'] ) {
							$img_id = get_term_meta( $term->term_id, $field['name'], true );
							if ( $img_id ) {
								$meta_input[ $field['name'] ] = array(
									'media' => true,
									'url'   => wp_get_attachment_image_url( $img_id, 'full' )
								);
							}
						} else {
							$meta_input[ $field['name'] ] = get_term_meta( $term->term_id, $field['name'], true );
						}
					}
				}

				$result[] = array(
					'name'        => $term->name,
					'slug'        => $term->slug,
					'taxonomy'    => $slug,
					'description' => $term->description,
					'meta_input'  => $meta_input,
				);

			}

			return $result;
		}

		/**
		 * Export listings
		 *
		 * @return void
		 */
		public function export_listings( $listings ) {

			$query = get_posts( array(
				'post_type'      => jet_engine()->post_type->slug(),
				'post__in'       => $listings,
				'posts_per_page' => -1,
			) );

			$this->id .= implode( '', $listings );

			if ( empty( $query ) ) {
				return array();
			}

			$result = array();

			foreach ( $query as $post ) {
				$result[] = array(
					'title'    => $post->post_title,
					'slug'     => $post->post_name,
					'settings' => get_post_meta( $post->ID, '_elementor_page_settings', true ),
					'content'  => get_post_meta( $post->ID, '_elementor_data', true ),
				);
			}

			return $result;

		}

		/**
		 * Returns controls
		 *
		 * @return [type] [description]
		 */
		public function get_controls() {

			ob_start();
			?>
			<div class="jet-engine-export">
				<button type="button" class="cx-button cx-button-normal-style" id="jet_engine_export_skin">
					<?php _e( 'Export skin', 'jet-engine' ) ?>
				</button>
				&nbsp;&nbsp;&nbsp;
				<span>
					<?php _e( 'Export combination of post types, related taxonomies and listing items as new skin', 'jet-engine' ) ?>
				</span>
			</div>
			<?php
			return ob_get_clean();

		}

		/**
		 * Expost popup
		 *
		 * @return [type] [description]
		 */
		public function export_popup() {

			$post_types    = jet_engine()->cpt->get_items();
			$taxonomies    = jet_engine()->taxonomies->get_items();
			$meta_boxes    = jet_engine()->meta_boxes->get_items();
			$listing_items = jet_engine()->listings->get_listings();

			$post_types    = ! empty( $post_types ) ? $post_types : array();
			$taxonomies    = ! empty( $taxonomies ) ? $taxonomies : array();
			$meta_boxes    = ! empty( $meta_boxes ) ? $meta_boxes : array();
			$listing_items = ! empty( $listing_items ) ? $listing_items : array();

			$action = add_query_arg(
				array(
					'export_skin' => 1,
				)
			);

			?>
			<div class="jet-listings-popup">
				<div class="jet-listings-popup__overlay"></div>
				<div class="jet-listings-popup__content">
					<h3 class="jet-listings-popup__heading"><?php
						esc_html_e( 'Export Settings', 'jet-engine' );
					?></h3>
					<form method="POST" action="<?php echo $action; ?>">
						<div class="jet-listings-popup__settings">
							<div class="jet-listings-popup__group-title"><?php
								_e( 'Post types', 'jet-engine' );
							?></div>
							<div class="jet-listings-popup__check-group"><?php
								foreach ( $post_types as $post_type ) {

									echo '<label class="jet-listings-popup__check-group-item">';

									printf(
										'<input type="checkbox" name="_jet_post_types[]" value="%d">',
										$post_type['id']
									);

									printf(
										'<span>%s</span>',
										$post_type['labels']['name']
									);

									echo '</label>';
								}
							?></div>
							<div class="jet-listings-popup__group-title"><?php
								_e( 'Taxonomies', 'jet-engine' );
							?></div>
							<div class="jet-listings-popup__check-group"><?php
								foreach ( $taxonomies as $tax ) {

									echo '<label class="jet-listings-popup__check-group-item">';

									printf(
										'<input type="checkbox" name="_jet_taxonomies[]" value="%d">',
										$tax['id']
									);

									printf(
										'<span>%s</span>',
										$tax['labels']['name']
									);

									echo '</label>';
								}
							?></div>
							<div class="jet-listings-popup__group-title"><?php
								_e( 'Meta Boxes', 'jet-engine' );
							?></div>
							<div class="jet-listings-popup__check-group"><?php
								foreach ( $meta_boxes as $meta_box ) {

									echo '<label class="jet-listings-popup__check-group-item">';

									printf(
										'<input type="checkbox" name="_jet_meta_boxes[]" value="%s">',
										$meta_box['id']
									);

									printf(
										'<span>%s</span>',
										$meta_box['args']['name']
									);

									echo '</label>';
								}
							?></div>
							<div class="jet-listings-popup__group-title"><?php
								_e( 'Listings', 'jet-engine' );
							?></div>
							<div class="jet-listings-popup__check-group"><?php
								foreach ( $listing_items as $listing ) {

									echo '<label class="jet-listings-popup__check-group-item">';

									printf(
										'<input type="checkbox" name="_jet_listings[]" value="%d">',
										$listing->ID
									);

									printf(
										'<span>%s</span>',
										$listing->post_title
									);

									echo '</label>';
								}
							?></div>
							<div class="jet-listings-popup__group-title"><?php
								_e( 'Sample Content', 'jet-engine' );
							?></div>
							<div class="jet-listings-popup__check-group">
								<label class="jet-listings-popup__check-group-item fullwidth-item">
									<input type="checkbox" name="_jet_content" value="yes">
									<?php printf( '<span>%s</span>', __( 'Add Sample Content', 'jet-engine' ) ); ?>
								</label>
							</div>
							<div class="jet-listings-popup__actions">
								<button type="submit" class="cx-button cx-button-primary-style"><?php
									_e( 'Export', 'jet-engine' );
								?></button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<?php
		}

	}

}
