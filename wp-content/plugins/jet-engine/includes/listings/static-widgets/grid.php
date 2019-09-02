<?php
namespace Elementor;

use Elementor\Group_Control_Border;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Elementor\Jet_Listing_Grid_Widget' ) ) {

	class Jet_Listing_Grid_Widget extends Widget_Base {

		public $is_first = false;
		public $data     = false;

		public function get_name() {
			return 'jet-listing-grid';
		}

		public function get_title() {
			return __( 'Listing Grid', 'jet-engine' );
		}

		public function get_icon() {
			return 'jet-engine-icon-7';
		}

		public function get_categories() {
			return array( 'jet-listing-elements' );
		}

		public function register_general_settings() {

			$this->start_controls_section(
				'section_general',
				array(
					'label' => __( 'General', 'jet-engine' ),
				)
			);

			$this->add_control(
				'lisitng_id',
				array(
					'label'   => __( 'Listing', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => $this->get_listings(),
				)
			);

			$this->add_responsive_control(
				'columns',
				array(
					'label'   => __( 'Columns Number', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 3,
					'options' => array(
						1 => 1,
						2 => 2,
						3 => 3,
						4 => 4,
						5 => 5,
						6 => 6,
					),
				)
			);

			$this->add_control(
				'is_archive_template',
				array(
					'label'        => __( 'Use as Archive Template', 'jet-engine' ),
					'type'         => Controls_Manager::SWITCHER,
					'description'  => '',
					'label_on'     => __( 'Yes', 'jet-engine' ),
					'label_off'    => __( 'No', 'jet-engine' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			$this->add_control(
				'post_status',
				array(
					'label' => esc_html__( 'Status', 'jet-engine' ),
					'type' => Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => array(
						'publish'    => esc_html__( 'Publish', 'jet-engine' ),
						'future'     => esc_html__( 'Future', 'jet-engine' ),
						'draft'      => esc_html__( 'Draft', 'jet-engine' ),
						'pending'    => esc_html__( 'Pending Review', 'jet-engine' ),
						'private'    => esc_html__( 'Private', 'jet-engine' ),
					),
					'default' => array( 'publish' ),
					'condition'   => array(
						'is_archive_template!' => 'yes',
					),
				)
			);

			$this->add_control(
				'posts_num',
				array(
					'label'       => __( 'Posts number', 'jet-engine' ),
					'type'        => Controls_Manager::NUMBER,
					'default'     => 6,
					'min'         => 1,
					'max'         => 1000,
					'step'        => 1,
					'condition'   => array(
						'is_archive_template!' => 'yes',
					),
				)
			);

			$this->add_control(
				'not_found_message',
				array(
					'label'       => __( 'Not found message', 'jet-engine' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'No data was found', 'jet-engine' ),
					'label_block' => true,
				)
			);

			$this->add_control(
				'equal_columns_height',
				array(
					'label'        => __( 'Equal columns height', 'jet-engine' ),
					'type'         => Controls_Manager::SWITCHER,
					'description'  => __( 'Fits only top level sections of grid item', 'jet-engine' ),
					'label_on'     => __( 'Yes', 'jet-engine' ),
					'label_off'    => __( 'No', 'jet-engine' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			$this->end_controls_section();

		}

		public function register_query_settings() {

			$this->start_controls_section(
				'section_posts_query',
				array(
					'label' => __( 'Posts Query', 'jet-engine' ),
				)
			);

			$this->add_control(
				'posts_query_notice',
				array(
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => __( 'Set advanced query parameters', 'jet-engine' ),
				)
			);

			$this->add_control(
				'posts_query_ignored_notice',
				array(
					'type'      => Controls_Manager::RAW_HTML,
					'raw'       => __( 'You select <b>Use as Archive Template</b> option, so other query parameters will be ignored', 'jet-engine' ),
					'condition' => array(
						'is_archive_template' => 'yes',
					),
				)
			);

			$posts_query_repeater = new Repeater();

			$posts_query_repeater->add_control(
				'type',
				array(
					'label'   => __( 'Type', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => array(
						'posts_params' => __( 'Posts Parameters', 'jet-engine' ),
						'order_offset' => __( 'Order & Offset', 'jet-engine' ),
						'tax_query'    => __( 'Tax Query', 'jet-engine' ),
						'meta_query'   => __( 'Meta Query', 'jet-engine' ),
						'date_query'   => __( 'Date Query', 'jet-engine' ),
					),
				)
			);

			$posts_query_repeater->add_control(
				'date_query_column',
				array(
					'label'   => __( 'Column', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => array(
						'post_date'         => __( 'Post date', 'jet-engine' ),
						'post_date_gmt'     => __( 'Post date GMT', 'jet-engine' ),
						'post_modified'     => __( 'Post modified', 'jet-engine' ),
						'post_modified_gmt' => __( 'Post modified GMT', 'jet-engine' ),
					),
					'condition'   => array(
						'type' => 'date_query'
					),
				)
			);

			$posts_query_repeater->add_control(
				'date_query_after',
				array(
					'label'       => __( 'After', 'jet-engine' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'description' => __( 'Date to retrieve posts after. Accepts strtotime()-compatible string', 'jet-engine' ),
					'label_block' => true,
					'condition'   => array(
						'type' => 'date_query'
					),
				)
			);

			$posts_query_repeater->add_control(
				'date_query_before',
				array(
					'label'       => __( 'Before', 'jet-engine' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'description' => __( 'Date to retrieve posts before. Accepts strtotime()-compatible string', 'jet-engine' ),
					'label_block' => true,
					'condition'   => array(
						'type' => 'date_query'
					),
				)
			);

			$posts_query_repeater->add_control(
				'posts_in',
				array(
					'label'       => __( 'Include posts by IDs', 'jet-engine' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'description' => __( 'Eg. 12, 24, 33', 'jet-engine' ),
					'condition'   => array(
						'type' => 'posts_params'
					),
				)
			);

			$posts_query_repeater->add_control(
				'posts_not_in',
				array(
					'label'       => __( 'Exclude posts by IDs', 'jet-engine' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'description' => __( 'Eg. 12, 24, 33. If this is used in the same query as Include posts by IDs, it will be ignored', 'jet-engine' ),
					'condition'   => array(
						'type' => 'posts_params'
					),
				)
			);

			$posts_query_repeater->add_control(
				'posts_parent',
				array(
					'label'       => __( 'Get child of', 'jet-engine' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'description' => __( 'Eg. 12, 24, 33', 'jet-engine' ),
					'condition'   => array(
						'type' => 'posts_params'
					),
				)
			);

			$posts_query_repeater->add_control(
				'posts_status',
				array(
					'label'   => __( 'Get posts with status', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'publish',
					'options' => array(
						'publish'    => __( 'Publish', 'jet-engine' ),
						'pending'    => __( 'Pending', 'jet-engine' ),
						'draft'      => __( 'Draft', 'jet-engine' ),
						'auto-draft' => __( 'Auto draft', 'jet-engine' ),
						'future'     => __( 'Future', 'jet-engine' ),
						'private'    => __( 'Private', 'jet-engine' ),
						'trash'      => __( 'Trash', 'jet-engine' ),
						'any'        => __( 'Any', 'jet-engine' ),
					),
					'condition'   => array(
						'type' => 'posts_params'
					),
				)
			);

			$posts_query_repeater->add_control(
				'offset',
				array(
					'label'     => __( 'Posts offset', 'jet-engine' ),
					'type'      => Controls_Manager::NUMBER,
					'default'   => '0',
					'min'       => 0,
					'max'       => 100,
					'step'      => 1,
					'condition' => array(
						'type' => 'order_offset'
					),
				)
			);

			$posts_query_repeater->add_control(
				'order',
				array(
					'label'   => __( 'Order', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'DESC',
					'options' => array(
						'ASC'  => __( 'ASC', 'jet-engine' ),
						'DESC' => __( 'DESC', 'jet-engine' ),
					),
					'condition'   => array(
						'type' => 'order_offset'
					),
				)
			);

			$posts_query_repeater->add_control(
				'order_by',
				array(
					'label'   => __( 'Order by', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'date',
					'options' => array(
						'none'          => __( 'None', 'jet-engine' ),
						'ID'            => __( 'ID', 'jet-engine' ),
						'author'        => __( 'Author', 'jet-engine' ),
						'title'         => __( 'Title', 'jet-engine' ),
						'name'          => __( 'Name', 'jet-engine' ),
						'type'          => __( 'Type', 'jet-engine' ),
						'date'          => __( 'Date', 'jet-engine' ),
						'modified'      => __( 'Modified', 'jet-engine' ),
						'parent'        => __( 'Parent', 'jet-engine' ),
						'rand'          => __( 'Rand', 'jet-engine' ),
						'comment_count' => __( 'Comment count', 'jet-engine' ),
						'relevance'     => __( 'Relevance', 'jet-engine' ),
						'menu_order'    => __( 'Menu order', 'jet-engine' ),
						'meta_value'    => __( 'Meta value', 'jet-engine' ),
					),
					'condition'   => array(
						'type' => 'order_offset'
					),
				)
			);

			$posts_query_repeater->add_control(
				'meta_key',
				array(
					'label'       => __( 'Meta key to order', 'jet-engine' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'label_block' => true,
					'description' => __( 'Set meta field name to order by', 'jet-engine' ),
					'condition'   => array(
						'type'     => 'order_offset',
						'order_by' => 'meta_value',
					),
				)
			);

			$posts_query_repeater->add_control(
				'meta_type',
				array(
					'label'   => __( 'Meta type', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'CHAR',
					'options' => array(
						'NUMERIC'  => __( 'NUMERIC', 'jet-engine' ),
						'CHAR'     => __( 'CHAR', 'jet-engine' ),
						'DATE'     => __( 'DATE', 'jet-engine' ),
						'DATETIME' => __( 'DATETIME', 'jet-engine' ),
						'DECIMAL'  => __( 'DECIMAL', 'jet-engine' ),
					),
					'condition'   => array(
						'type'     => 'order_offset',
						'order_by' => 'meta_value',
					),
				)
			);

			$posts_query_repeater->add_control(
				'tax_query_taxonomy',
				array(
					'label'   => __( 'Taxonomy', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'options' => jet_engine()->listings->get_taxonomies_for_options(),
					'default' => '',
					'condition' => array(
						'type' => 'tax_query'
					),
				)
			);

			$posts_query_repeater->add_control(
				'tax_query_taxonomy_meta',
				array(
					'label'       => __( 'Taxonomy from meta field', 'jet-engine' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'label_block' => true,
					'description' => __( 'Get taxonomy name from current page meta field', 'jet-engine' ),
					'condition'   => array(
						'type' => 'tax_query'
					),
				)
			);


			$posts_query_repeater->add_control(
				'tax_query_compare',
				array(
					'label'   => __( 'Operator', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'IN'         => __( 'IN', 'jet-engine' ),
						'NOT IN'     => __( 'NOT IN', 'jet-engine' ),
						'AND'        => __( 'AND', 'jet-engine' ),
						'EXISTS'     => __( 'EXISTS', 'jet-engine' ),
						'NOT EXISTS' => __( 'NOT EXISTS', 'jet-engine' ),
					),
					'default' => 'IN',
					'condition' => array(
						'type' => 'tax_query'
					),
				)
			);

			$posts_query_repeater->add_control(
				'tax_query_field',
				array(
					'label'   => __( 'Field', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'term_id' => __( 'Term ID', 'jet-engine' ),
						'slug'    => __( 'Slug', 'jet-engine' ),
						'name'    => __( 'Name', 'jet-engine' ),
					),
					'default' => 'term_id',
					'condition' => array(
						'type' => 'tax_query'
					),
				)
			);

			$posts_query_repeater->add_control(
				'tax_query_terms',
				array(
					'label'       => __( 'Terms', 'jet-engine' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'label_block' => true,
					'condition'   => array(
						'type' => 'tax_query'
					),
				)
			);

			$posts_query_repeater->add_control(
				'tax_query_terms_meta',
				array(
					'label'       => __( 'Terms from meta field', 'jet-engine' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'label_block' => true,
					'description' => __( 'Get terms IDs from current page meta field', 'jet-engine' ),
					'condition'   => array(
						'type' => 'tax_query'
					),
				)
			);

			$posts_query_repeater->add_control(
				'meta_query_key',
				array(
					'label'   => __( 'Key (name/ID)', 'jet-engine' ),
					'type'    => Controls_Manager::TEXT,
					'default' => '',
					'condition' => array(
						'type' => 'meta_query'
					),
				)
			);

			$posts_query_repeater->add_control(
				'meta_query_compare',
				array(
					'label'   => __( 'Operator', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '=',
					'options' => array(
						'='           => __( 'Equal', 'jet-engine' ),
						'!='          => __( 'Not equal', 'jet-engine' ),
						'>'           => __( 'Greater than', 'jet-engine' ),
						'>='          => __( 'Greater or equal', 'jet-engine' ),
						'<'           => __( 'Less than', 'jet-engine' ),
						'<='          => __( 'Equal or less', 'jet-engine' ),
						'LIKE'        => __( 'Like', 'jet-engine' ),
						'NOT LIKE'    => __( 'Not like', 'jet-engine' ),
						'IN'          => __( 'In', 'jet-engine' ),
						'NOT IN'      => __( 'Not in', 'jet-engine' ),
						'BETWEEN'     => __( 'Between', 'jet-engine' ),
						'NOT BETWEEN' => __( 'Not between', 'jet-engine' ),
					),
					'condition'   => array(
						'type' => 'meta_query',
					),
				)
			);

			$posts_query_repeater->add_control(
				'meta_query_val',
				array(
					'label'       => __( 'Value', 'jet-engine' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'label_block' => true,
					'description' => __( 'For <b>In</b>, <b>Not in</b>, <b>Between</b> and <b>Not between</b> compare separate multiple values with comma', 'jet-engine' ),
					'condition'   => array(
						'type' => 'meta_query'
					),
				)
			);

			$posts_query_repeater->add_control(
				'meta_query_request_val',
				array(
					'label'       => __( 'Or get value from query variable', 'jet-engine' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'label_block' => true,
					'description' => __( 'Set query variable name (from URL or WordPress query var) to get value from', 'jet-engine' ),
					'condition'   => array(
						'type' => 'meta_query'
					),
				)
			);

			$posts_query_repeater->add_control(
				'meta_query_type',
				array(
					'label'   => __( 'Type', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'CHAR',
					'options' => $this->meta_types(),
					'condition'   => array(
						'type' => 'meta_query',
					),
				)
			);

			$this->add_control(
				'posts_query',
				array(
					'type'    => Controls_Manager::REPEATER,
					'fields'  => array_values( $posts_query_repeater->get_controls() ),
					'default' => array(),
					'title_field' => '{{{ type }}}',
				)
			);

			$this->add_control(
				'meta_query_relation',
				array(
					'label'   => __( 'Meta query relation', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'AND',
					'options' => array(
						'AND' => __( 'AND', 'jet-engine' ),
						'OR'  => __( 'OR', 'jet-engine' ),
					),
				)
			);

			$this->add_control(
				'tax_query_relation',
				array(
					'label'   => __( 'Tax query relation', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'AND',
					'options' => array(
						'AND' => __( 'AND', 'jet-engine' ),
						'OR'  => __( 'OR', 'jet-engine' ),
					),
				)
			);

			$this->end_controls_section();

		}

		/**
		 * Terms query settings
		 * @return [type] [description]
		 */
		public function register_terms_query_settings() {

			$this->start_controls_section(
				'section_terms_query',
				array(
					'label' => __( 'Terms Query', 'jet-engine' ),
				)
			);

			$this->add_control(
				'terms_query_notice',
				array(
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => __( 'Set advanced query parameters', 'jet-engine' ),

				)
			);

			$this->add_control(
				'terms_query_ignored_notice',
				array(
					'type'      => Controls_Manager::RAW_HTML,
					'raw'       => __( 'You select <b>Use as Archive Template</b> option, so other query parameters will be ignored', 'jet-engine' ),
					'condition' => array(
						'is_archive_template' => 'yes',
					),
				)
			);

			$this->add_control(
				'terms_object_ids',
				array(
					'label'       => __( 'Get terms of posts', 'jet-engine' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'label_block' => true,
				)
			);

			$this->add_control(
				'terms_orderby',
				array(
					'label'   => __( 'Order By', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'name',
					'options' => array(
						'name'        => __( 'Name', 'jet-engine' ),
						'slug'        => __( 'Slug', 'jet-engine' ),
						'term_group'  => __( 'Term Group', 'jet-engine' ),
						'term_id'     => __( 'Term ID', 'jet-engine' ),
						'description' => __( 'Description', 'jet-engine' ),
						'parent'      => __( 'Parent', 'jet-engine' ),
						'count'       => __( 'Count', 'jet-engine' ),
						'none'        => __( 'None', 'jet-engine' ),
					),
				)
			);

			$this->add_control(
				'terms_order',
				array(
					'label'   => __( 'Order', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'DESC',
					'options' => array(
						'ASC'  => __( 'ASC', 'jet-engine' ),
						'DESC' => __( 'DESC', 'jet-engine' ),
					),
				)
			);

			$this->add_control(
				'terms_hide_empty',
				array(
					'label'        => __( 'Hide empty', 'jet-engine' ),
					'type'         => Controls_Manager::SWITCHER,
					'description'  => '',
					'label_on'     => __( 'Yes', 'jet-engine' ),
					'label_off'    => __( 'No', 'jet-engine' ),
					'return_value' => 'true',
					'default'      => 'true',
				)
			);

			$this->add_control(
				'terms_include',
				array(
					'label'       => __( 'Include terms', 'jet-engine' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'label_block' => true,
					'description' => __( 'Comma/space-separated string of term ids to include', 'jet-engine' ),
				)
			);

			$this->add_control(
				'terms_exclude',
				array(
					'label'       => __( 'Exclude terms', 'jet-engine' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'label_block' => true,
					'description' => __( 'Comma/space-separated string of term ids to exclude. Ignore if <b>Include terms</b> not empty', 'jet-engine' ),
				)
			);

			$this->add_control(
				'terms_offset',
				array(
					'label'     => __( 'Offset', 'jet-engine' ),
					'type'      => Controls_Manager::NUMBER,
					'default'   => '0',
					'min'       => 0,
					'max'       => 100,
					'step'      => 1,
				)
			);

			$this->add_control(
				'terms_child_of',
				array(
					'label'       => __( 'Child of', 'jet-engine' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'description' => __( 'Term ID to retrieve child terms of', 'jet-engine' ),
				)
			);

			$this->add_control(
				'terms_meta_query_heading',
				array(
					'label'     => __( 'Meta Query', 'jet-engine' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$terms_meta_query = new Repeater();

			$terms_meta_query->add_control(
				'meta_query_key',
				array(
					'label'   => __( 'Key (name/ID)', 'jet-engine' ),
					'type'    => Controls_Manager::TEXT,
					'default' => '',
				)
			);

			$terms_meta_query->add_control(
				'meta_query_compare',
				array(
					'label'   => __( 'Operator', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '=',
					'options' => array(
						'='           => __( 'Equal', 'jet-engine' ),
						'!='          => __( 'Not equal', 'jet-engine' ),
						'>'           => __( 'Greater than', 'jet-engine' ),
						'>='          => __( 'Greater or equal', 'jet-engine' ),
						'<'           => __( 'Less than', 'jet-engine' ),
						'<='          => __( 'Equal or less', 'jet-engine' ),
						'LIKE'        => __( 'Like', 'jet-engine' ),
						'NOT LIKE'    => __( 'Not like', 'jet-engine' ),
						'IN'          => __( 'In', 'jet-engine' ),
						'NOT IN'      => __( 'Not in', 'jet-engine' ),
						'BETWEEN'     => __( 'Between', 'jet-engine' ),
						'NOT BETWEEN' => __( 'Not between', 'jet-engine' ),
					),
				)
			);

			$terms_meta_query->add_control(
				'meta_query_val',
				array(
					'label'       => __( 'Value', 'jet-engine' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'label_block' => true,
					'description' => __( 'For <b>In</b>, <b>Not in</b>, <b>Between</b> and <b>Not between</b> compare separate multiple values with comma', 'jet-engine' ),
				)
			);

			$terms_meta_query->add_control(
				'meta_query_type',
				array(
					'label'   => __( 'Type', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'CHAR',
					'options' => $this->meta_types(),
				)
			);

			$this->add_control(
				'terms_meta_query',
				array(
					'type'    => Controls_Manager::REPEATER,
					'fields'  => array_values( $terms_meta_query->get_controls() ),
					'default' => array(),
					'title_field' => '{{{ meta_query_key }}}',
				)
			);

			$this->add_control(
				'term_meta_query_relation',
				array(
					'label'   => __( 'Meta query relation', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'AND',
					'options' => array(
						'AND' => __( 'AND', 'jet-engine' ),
						'OR'  => __( 'OR', 'jet-engine' ),
					),
				)
			);

			$this->end_controls_section();

		}

		public function register_visibility_settings() {

			$this->start_controls_section(
				'section_widget_visibility',
				array(
					'label' => __( 'Widget Visibility', 'jet-engine' ),
				)
			);

			$hide_options = apply_filters( 'jet-engine/listing/grid/widget-hide-options', array(
				''            => __( 'Always show', 'jet-engine' ),
				'empty_query' => __( 'Query is empty', 'jet-engine' ),
			) );

			$this->add_control(
				'hide_widget_if',
				array(
					'label'   => __( 'Hide widget if', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => $hide_options,
				)
			);

			$this->end_controls_section();

		}

		/**
		 * Register style settings
		 * @return [type] [description]
		 */
		public function register_style_settings() {

			$this->start_controls_section(
				'section_caption_style',
				array(
					'label'      => __( 'Columns', 'jet-engine' ),
					'tab'        => Controls_Manager::TAB_STYLE,
					'show_label' => false,
				)
			);

			$this->add_responsive_control(
				'horizontal_gap',
				array(
					'label' => __( 'Horizontal Gap', 'jet-engine' ),
					'type'  => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .jet-listing-grid__item' => 'padding-left: calc({{SIZE}}{{UNIT}} / 2); padding-right: calc({{SIZE}}{{UNIT}} / 2);',
						'{{WRAPPER}} .jet-listing-grid__items' => 'margin-left: calc(-{{SIZE}}{{UNIT}} / 2); margin-right: calc(-{{SIZE}}{{UNIT}} / 2);',
					),
				)
			);

			$this->add_responsive_control(
				'vertical_gap',
				array(
					'label' => __( 'Vertical Gap', 'jet-engine' ),
					'type'  => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .jet-listing-grid__item' => 'padding-top: calc({{SIZE}}{{UNIT}} / 2); padding-bottom: calc({{SIZE}}{{UNIT}} / 2);',
					),
				)
			);

			$this->end_controls_section();

		}

		/**
		 * Register style settings
		 * @return [type] [description]
		 */
		public function register_carousel_settings() {

			$this->start_controls_section(
				'section_carousel',
				array(
					'label' => __( 'Slider', 'jet-engine' ),
				)
			);

			$this->add_control(
				'carousel_enabled',
				array(
					'label'        => __( 'Enable Slider', 'jet-engine' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'jet-engine' ),
					'label_off'    => __( 'No', 'jet-engine' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			$this->add_control(
				'slides_to_scroll',
				array(
					'label'     => __( 'Slides to Scroll', 'jet-engine' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => '1',
					'options'   => array(
						1 => 1,
						2 => 2,
						3 => 3,
						4 => 4,
					),
					'condition' => array(
						'columns!' => '1',
					),
				)
			);

			$this->add_control(
				'arrows',
				array(
					'label'        => __( 'Show Arrows Navigation', 'jet-engine' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'jet-engine' ),
					'label_off'    => __( 'No', 'jet-engine' ),
					'return_value' => 'true',
					'default'      => 'true',
				)
			);

			$this->add_control(
				'arrow_icon',
				array(
					'label'   => __( 'Arrow Icon', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'fa fa-angle-left',
					'options' => array(
						'fa fa-angle-left'          => __( 'Angle', 'jet-engine' ),
						'fa fa-chevron-left'        => __( 'Chevron', 'jet-engine' ),
						'fa fa-angle-double-left'   => __( 'Angle Double', 'jet-engine' ),
						'fa fa-arrow-left'          => __( 'Arrow', 'jet-engine' ),
						'fa fa-caret-left'          => __( 'Caret', 'jet-engine' ),
						'fa fa-long-arrow-left'     => __( 'Long Arrow', 'jet-engine' ),
						'fa fa-arrow-circle-left'   => __( 'Arrow Circle', 'jet-engine' ),
						'fa fa-chevron-circle-left' => __( 'Chevron Circle', 'jet-engine' ),
						'fa fa-caret-square-o-left' => __( 'Caret Square', 'jet-engine' ),
					),
					'condition' => array(
						'arrows' => 'true',
					),
				)
			);

			$this->add_control(
				'dots',
				array(
					'label'        => __( 'Show Dots Navigation', 'jet-engine' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'jet-engine' ),
					'label_off'    => __( 'No', 'jet-engine' ),
					'return_value' => 'true',
					'default'      => '',
				)
			);

			$this->add_control(
				'autoplay',
				array(
					'label'        => __( 'Autoplay', 'jet-engine' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'jet-engine' ),
					'label_off'    => __( 'No', 'jet-engine' ),
					'return_value' => 'true',
					'default'      => 'true',
				)
			);

			$this->add_control(
				'autoplay_speed',
				array(
					'label'     => __( 'Autoplay Speed', 'jet-engine' ),
					'type'      => Controls_Manager::NUMBER,
					'default'   => 5000,
					'condition' => array(
						'autoplay' => 'true',
					),
				)
			);

			$this->add_control(
				'infinite',
				array(
					'label'        => __( 'Infinite Loop', 'jet-engine' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'jet-engine' ),
					'label_off'    => __( 'No', 'jet-engine' ),
					'return_value' => 'true',
					'default'      => 'true',
				)
			);

			$this->add_control(
				'effect',
				array(
					'label'   => __( 'Effect', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'slide',
					'options' => array(
						'slide' => __( 'Slide', 'jet-engine' ),
						'fade'  => __( 'Fade', 'jet-engine' ),
					),
					'condition' => array(
						'columns' => '1',
					),
				)
			);

			$this->add_control(
				'speed',
				array(
					'label'   => __( 'Animation Speed', 'jet-engine' ),
					'type'    => Controls_Manager::NUMBER,
					'default' => 500,
				)
			);

			$this->end_controls_section();

		}

		/**
		 * Register carousel styles settings
		 *
		 * @return [type] [description]
		 */
		public function register_carousel_style_settings() {

			$this->start_controls_section(
				'section_slider_style',
				array(
					'label'      => __( 'Slider', 'jet-engine' ),
					'tab'        => Controls_Manager::TAB_STYLE,
					'show_label' => false,
				)
			);

			$this->add_responsive_control(
				'arrows_box_size',
				array(
					'label'      => __( 'Slider arrows box size', 'jet-engine' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 16,
							'max' => 120,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .jet-listing-grid__slider-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; margin-top: calc( -{{SIZE}}{{UNIT}}/2 );',
					),
				)
			);

			$this->add_responsive_control(
				'arrows_size',
				array(
					'label'      => __( 'Slider arrows size', 'jet-engine' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 10,
							'max' => 50,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .jet-listing-grid__slider-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->start_controls_tabs( 'tabs_arrow_style' );

			$this->start_controls_tab(
				'tab_arrow_normal',
				array(
					'label' => __( 'Noraml', 'jet-engine' ),
				)
			);

			$this->add_control(
				'arrow_color',
				array(
					'label'     => __( 'Color', 'jet-engine' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-listing-grid__slider-icon' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'arrow_bg_color',
				array(
					'label'     => __( 'Background', 'jet-engine' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-listing-grid__slider-icon' => 'background: {{VALUE}}',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_arrow_hover',
				array(
					'label' => __( 'Hover', 'jet-engine' ),
				)
			);

			$this->add_control(
				'arrow_color_hover',
				array(
					'label'     => __( 'Color', 'jet-engine' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-listing-grid__slider-icon:hover' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'arrow_bg_color_hover',
				array(
					'label'     => __( 'Background', 'jet-engine' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-listing-grid__slider-icon:hover' => 'background: {{VALUE}}',
					),
				)
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'prev_vert_position',
				array(
					'label'   => __( 'Vertical Position by', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'top',
					'options' => array(
						'top'    => __( 'Top', 'jet-engine' ),
						'bottom' => __( 'Bottom', 'jet-engine' ),
					),
				)
			);

			$this->add_responsive_control(
				'prev_top_position',
				array(
					'label'      => __( 'Top Indent', 'jet-engine' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em' ),
					'range'      => array(
						'px' => array(
							'min' => -400,
							'max' => 400,
						),
						'%' => array(
							'min' => -100,
							'max' => 100,
						),
						'em' => array(
							'min' => -50,
							'max' => 50,
						),
					),
					'condition' => array(
						'prev_vert_position' => 'top',
					),
					'selectors'  => array(
						'{{WRAPPER}} .jet-listing-grid__slider-icon.prev-arrow' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
					),
				)
			);

			$this->add_responsive_control(
				'prev_bottom_position',
				array(
					'label'      => __( 'Bottom Indent', 'jet-engine' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em' ),
					'range'      => array(
						'px' => array(
							'min' => -400,
							'max' => 400,
						),
						'%' => array(
							'min' => -100,
							'max' => 100,
						),
						'em' => array(
							'min' => -50,
							'max' => 50,
						),
					),
					'condition' => array(
						'prev_vert_position' => 'bottom',
					),
					'selectors'  => array(
						'{{WRAPPER}} .jet-listing-grid__slider-icon.prev-arrow' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
					),
				)
			);

			$this->add_control(
				'prev_hor_position',
				array(
					'label'   => __( 'Horizontal Position by', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'left',
					'options' => array(
						'left'  => __( 'Left', 'jet-engine' ),
						'right' => __( 'Right', 'jet-engine' ),
					),
				)
			);

			$this->add_responsive_control(
				'prev_left_position',
				array(
					'label'      => __( 'Left Indent', 'jet-engine' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em' ),
					'range'      => array(
						'px' => array(
							'min' => -400,
							'max' => 400,
						),
						'%' => array(
							'min' => -100,
							'max' => 100,
						),
						'em' => array(
							'min' => -50,
							'max' => 50,
						),
					),
					'condition' => array(
						'prev_hor_position' => 'left',
					),
					'selectors'  => array(
						'{{WRAPPER}} .jet-listing-grid__slider-icon.prev-arrow' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
					),
				)
			);

			$this->add_responsive_control(
				'prev_right_position',
				array(
					'label'      => __( 'Right Indent', 'jet-engine' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em' ),
					'range'      => array(
						'px' => array(
							'min' => -400,
							'max' => 400,
						),
						'%' => array(
							'min' => -100,
							'max' => 100,
						),
						'em' => array(
							'min' => -50,
							'max' => 50,
						),
					),
					'condition' => array(
						'prev_hor_position' => 'right',
					),
					'selectors'  => array(
						'{{WRAPPER}} .jet-listing-grid__slider-icon.prev-arrow' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
					),
				)
			);

			$this->add_control(
				'next_arrow_position',
				array(
					'label'     => __( 'Next Arrow Position', 'jet-engine' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_control(
				'next_vert_position',
				array(
					'label'   => __( 'Vertical Position by', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'top',
					'options' => array(
						'top'    => __( 'Top', 'jet-engine' ),
						'bottom' => __( 'Bottom', 'jet-engine' ),
					),
				)
			);

			$this->add_responsive_control(
				'next_top_position',
				array(
					'label'      => __( 'Top Indent', 'jet-engine' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em' ),
					'range'      => array(
						'px' => array(
							'min' => -400,
							'max' => 400,
						),
						'%' => array(
							'min' => -100,
							'max' => 100,
						),
						'em' => array(
							'min' => -50,
							'max' => 50,
						),
					),
					'condition' => array(
						'next_vert_position' => 'top',
					),
					'selectors'  => array(
						'{{WRAPPER}} .jet-listing-grid__slider-icon.next-arrow' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
					),
				)
			);

			$this->add_responsive_control(
				'next_bottom_position',
				array(
					'label'      => __( 'Bottom Indent', 'jet-engine' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em' ),
					'range'      => array(
						'px' => array(
							'min' => -400,
							'max' => 400,
						),
						'%' => array(
							'min' => -100,
							'max' => 100,
						),
						'em' => array(
							'min' => -50,
							'max' => 50,
						),
					),
					'condition' => array(
						'next_vert_position' => 'bottom',
					),
					'selectors'  => array(
						'{{WRAPPER}} .jet-listing-grid__slider-icon.next-arrow' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
					),
				)
			);

			$this->add_control(
				'next_hor_position',
				array(
					'label'   => __( 'Horizontal Position by', 'jet-engine' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'right',
					'options' => array(
						'left'  => __( 'Left', 'jet-engine' ),
						'right' => __( 'Right', 'jet-engine' ),
					),
				)
			);

			$this->add_responsive_control(
				'next_left_position',
				array(
					'label'      => __( 'Left Indent', 'jet-engine' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em' ),
					'range'      => array(
						'px' => array(
							'min' => -400,
							'max' => 400,
						),
						'%' => array(
							'min' => -100,
							'max' => 100,
						),
						'em' => array(
							'min' => -50,
							'max' => 50,
						),
					),
					'condition' => array(
						'next_hor_position' => 'left',
					),
					'selectors'  => array(
						'{{WRAPPER}} .jet-listing-grid__slider-icon.next-arrow' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
					),
				)
			);

			$this->add_responsive_control(
				'next_right_position',
				array(
					'label'      => __( 'Right Indent', 'jet-engine' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em' ),
					'range'      => array(
						'px' => array(
							'min' => -400,
							'max' => 400,
						),
						'%' => array(
							'min' => -100,
							'max' => 100,
						),
						'em' => array(
							'min' => -50,
							'max' => 50,
						),
					),
					'condition' => array(
						'next_hor_position' => 'right',
					),
					'selectors'  => array(
						'{{WRAPPER}} .jet-listing-grid__slider-icon.next-arrow' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
					),
				)
			);

			$this->add_control(
				'dots_styles',
				array(
					'label'     => __( 'Dots Styles', 'jet-engine' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_responsive_control(
				'dots_size',
				array(
					'label'      => __( 'Dots Size', 'jet-engine' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 6,
							'max' => 100,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .jet-listing-grid__slider .jet-slick-dots li' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'dots_gap',
				array(
					'label'      => __( 'Dots Gap', 'jet-engine' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .jet-listing-grid__slider .jet-slick-dots li' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 ); margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					),
				)
			);

			$this->start_controls_tabs( 'tabs_dots_style' );

			$this->start_controls_tab(
				'tab_dots_normal',
				array(
					'label' => __( 'Noraml', 'jet-engine' ),
				)
			);

			$this->add_control(
				'dots_bg_color',
				array(
					'label'     => __( 'Color', 'jet-engine' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-listing-grid__slider .jet-slick-dots li' => 'background: {{VALUE}}',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_dots_hover',
				array(
					'label' => __( 'Hover', 'jet-engine' ),
				)
			);

			$this->add_control(
				'dots_bg_color_hover',
				array(
					'label'     => __( 'Color', 'jet-engine' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-listing-grid__slider .jet-slick-dots li:hover' => 'background: {{VALUE}}',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_dots_active',
				array(
					'label' => __( 'Active', 'jet-engine' ),
				)
			);

			$this->add_control(
				'dots_bg_color_active',
				array(
					'label'     => __( 'Color', 'jet-engine' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-listing-grid__slider .jet-slick-dots li.slick-active' => 'background: {{VALUE}}',
					),
				)
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->end_controls_section();

		}

		protected function _register_controls() {

			$this->register_general_settings();
			$this->register_query_settings();
			$this->register_terms_query_settings();
			$this->register_visibility_settings();
			$this->register_carousel_settings();
			$this->register_style_settings();
			$this->register_carousel_style_settings();

		}

		/**
		 * Return meta types list for options
		 * @return [type] [description]
		 */
		public function meta_types() {

			return array(
				'NUMERIC'  => __( 'NUMERIC', 'jet-engine' ),
				'BINARY'   => __( 'BINARY', 'jet-engine' ),
				'CHAR'     => __( 'CHAR', 'jet-engine' ),
				'DATE'     => __( 'DATE', 'jet-engine' ),
				'DATETIME' => __( 'DATETIME', 'jet-engine' ),
				'DECIMAL'  => __( 'DECIMAL', 'jet-engine' ),
				'SIGNED'   => __( 'SIGNED', 'jet-engine' ),
				'UNSIGNED' => __( 'UNSIGNED', 'jet-engine' ),
			);

		}

		/**
		 * Build query arguments array based on settings
		 *
		 * @return [type] [description]
		 */
		public function build_posts_query_args_array( $settings = array() ) {

			$post_type = jet_engine()->listings->data->get_listing_post_type();
			$per_page  = ! empty( $settings['posts_num'] ) ? absint( $settings['posts_num'] ) : 6;
			$post_status = ! empty( $settings['post_status'] ) ? $settings['post_status'] : 'publish';

			$args = array(
				'post_status'    => $post_status,
				'post_type'      => $post_type,
				'posts_per_page' => $per_page,
			);

			if ( ! empty( $settings['posts_query'] ) ) {
				foreach ( $settings['posts_query'] as $query_item ) {

					if ( empty( $query_item['type'] ) ) {
						continue;
					}

					$meta_index = 0;
					$tax_index  = 0;

					switch ( $query_item['type'] ) {

						case 'posts_params':
							$args = $this->add_posts_params_to_args( $args, $query_item );
							break;

						case 'order_offset':
							$args = $this->add_order_offset_to_args( $args, $query_item );
							break;

						case 'tax_query':
							$args = $this->add_tax_query_to_args( $args, $query_item );
							break;

						case 'meta_query':
							$args = $this->add_meta_query_to_args( $args, $query_item );
							break;

						case 'date_query':
							$args = $this->add_date_query_to_args( $args, $query_item );
							break;

					}

				}
			}

			if ( ! empty( $args['tax_query'] ) && ( 1 < count( $args['tax_query'] ) ) ) {
				$relation = ! empty( $settings['tax_query_relation'] ) ? $settings['tax_query_relation'] : 'AND';
				$args['tax_query']['relation'] = $relation;
			}

			if ( ! empty( $args['meta_query'] ) && ( 1 < count( $args['meta_query'] ) ) ) {
				$relation = ! empty( $settings['meta_query_relation'] ) ? $settings['meta_query_relation'] : 'AND';
				$args['meta_query']['relation'] = $relation;
			}

			array_walk( $args, array( $this, 'apply_macros_in_query' ) );

			return apply_filters( 'jet-engine/listing/grid/posts-query-args', $args, $this );

		}

		/**
		 * Apply macros in query callback
		 *
		 * @param  [type] &$item [description]
		 * @return [type]        [description]
		 */
		public function apply_macros_in_query( &$item ) {
			if ( ! is_array( $item ) ) {
				$item = jet_engine()->listings->macros->do_macros( $item );
			}
		}

		/**
		 * Build terms query arguments array based on settings
		 *
		 * @return [type] [description]
		 */
		public function build_terms_query_args_array( $settings = array() ) {

			$tax    = jet_engine()->listings->data->get_listing_tax();
			$number = ! empty( $settings['posts_num'] ) ? absint( $settings['posts_num'] ) : 6;

			$args = array(
				'taxonomy' => $tax,
				'number'   => $number,
			);

			$keys = array(
				'terms_orderby',
				'terms_order',
				'terms_offset',
				'terms_child_of',
			);

			foreach ( $keys as $key ) {

				if ( empty( $settings[ $key ] ) ) {
					continue;
				}

				$args[ str_replace( 'terms_', '', $key ) ] = esc_attr( $settings[ $key ] );

			}

			if ( ! empty( $settings['terms_object_ids'] ) ) {

				$ids = jet_engine()->listings->macros->do_macros( $settings['terms_object_ids'], $tax );
				$ids = $this->explode_string( $ids );

				if ( 1 === count( $ids ) ) {
					$args['object_ids'] = $ids[0];
				} else {
					$args['object_ids'] = $ids;
				}

			}

			if ( ! empty( $settings['terms_hide_empty'] ) && 'true' === $settings['terms_hide_empty'] ) {
				$args['hide_empty'] = true;
			} else {
				$args['hide_empty'] = false;
			}

			if ( ! empty( $settings['terms_meta_query'] ) ) {
				foreach ( $settings['terms_meta_query'] as $query_item ) {
					$args = $this->add_meta_query_to_args( $args, $query_item );
				}
			}

			if ( ! empty( $args['meta_query'] ) && ( 1 < count( $args['meta_query'] ) ) ) {
				$rel = ! empty( $settings['term_meta_query_relation'] ) ? $settings['term_meta_query_relation'] : 'AND';
				$args['meta_query']['relation'] = $rel;
			}

			array_walk( $args, array( $this, 'apply_macros_in_query' ) );

			foreach ( array( 'terms_include', 'terms_exclude' ) as $key ) {

				$ids = jet_engine()->listings->macros->do_macros( $settings[ $key ], $tax );
				$ids = $this->explode_string( $ids );
				$arg = str_replace( 'terms_', '', $key );

				if ( 1 === count( $ids ) ) {
					$args[ $arg ] = $ids[0];
				} else {
					$args[ $arg ] = $ids;
				}
			}

			return $args;
		}

		/**
		 * Add post parameters to arguments
		 */
		public function add_posts_params_to_args( $args, $settings ) {

			$post_args = array(
				'posts_in'     => $settings['posts_in'],
				'posts_not_in' => $settings['posts_not_in'],
				'posts_parent' => $settings['posts_parent'],
			);

			array_walk( $post_args, array( $this, 'apply_macros_in_query' ) );

			if ( ! empty( $post_args['posts_in'] ) ) {
				$args['post__in'] = $this->explode_string( $post_args['posts_in'] );
			}

			if ( ! empty( $post_args['posts_not_in'] ) ) {
				$args['post__not_in'] = $this->explode_string( $post_args['posts_not_in'] );
			}

			if ( ! empty( $post_args['posts_parent'] ) ) {
				$parent = $this->explode_string( $post_args['posts_parent'] );

				if ( 1 === count( $parent ) ) {
					$args['post_parent'] = $parent[0];
				} else {
					$args['post_parent__in'] = $parent;
				}

			}

			if ( ! empty( $settings['posts_status'] ) ) {
				$args['post_status'] = esc_attr( $settings['posts_status'] );
			}

			return $args;

		}

		/**
		 * Add order and offset parameters to arguments
		 */
		public function add_order_offset_to_args( $args, $settings ) {

			if ( ! empty( $settings['offset'] ) ) {
				$args['offset'] = absint( $settings['offset'] );
			}

			if ( ! empty( $settings['order'] ) ) {
				$args['order'] = esc_attr( $settings['order'] );
			}

			$order_by = ! empty( $settings['order_by'] ) ? esc_attr( $settings['order_by'] ) : 'date';

			if ( 'meta_value' === $order_by ) {

				$meta_key  = ! empty( $settings['meta_key'] ) ? esc_attr( $settings['meta_key'] ) : 'CHAR';
				$meta_type = ! empty( $settings['meta_type'] ) ? esc_attr( $settings['meta_type'] ) : 'CHAR';

				if ( 'CHAR' === $meta_type ) {
					$args['orderby']  = $order_by;
					$args['meta_key'] = $meta_key;
				} else {
					$args['orderby']   = 'meta_value_num';
					$args['meta_key']  = $meta_key;
					$args['meta_type'] = $meta_type;
				}

			} else {
				$args['orderby'] = $order_by;
			}

			return $args;

		}

		/**
		 * Add tax query parameters to arguments
		 */
		public function add_tax_query_to_args( $args, $settings ) {

			$taxonomy = '';

			if ( ! empty( $settings['tax_query_taxonomy_meta'] ) ) {
				$taxonomy = get_post_meta( get_the_ID(), esc_attr( $settings['tax_query_taxonomy_meta'] ), true );
			} else {
				$taxonomy = ! empty( $settings['tax_query_taxonomy'] ) ? esc_attr( $settings['tax_query_taxonomy'] ) : '';
			}

			if ( ! $taxonomy ) {
				return $args;
			}

			if ( empty( $args['tax_query'] ) ) {
				$args['tax_query'] = array();
			}

			$compare = ! empty( $settings['tax_query_compare'] ) ? esc_attr( $settings['tax_query_compare'] ) : 'IN';
			$field   = ! empty( $settings['tax_query_field'] ) ? esc_attr( $settings['tax_query_field'] ) : 'IN';

			$terms = '';

			if ( ! empty( $settings['tax_query_terms_meta'] ) ) {
				$terms = get_post_meta( get_the_ID(), esc_attr( $settings['tax_query_terms_meta'] ), true );
			} else {

				$terms = ! empty( $settings['tax_query_terms'] ) ? esc_attr( $settings['tax_query_terms'] ) : '';
				$terms = jet_engine()->listings->macros->do_macros( $terms, $taxonomy );
				$terms = $this->explode_string( $terms );

			}

			if ( ! empty( $terms ) ) {
				$args['tax_query'][] = array(
					'taxonomy' => $taxonomy,
					'field'    => $field,
					'terms'    => $terms,
					'operator' => $compare,
				);
			}

			return $args;

		}

		/**
		 * Add meta query parameters to arguments
		 */
		public function add_meta_query_to_args( $args, $settings ) {

			$key = ! empty( $settings['meta_query_key'] ) ? esc_attr( $settings['meta_query_key'] ) : '';

			if ( ! $key ) {
				return $args;
			}

			$type    = ! empty( $settings['meta_query_type'] ) ? esc_attr( $settings['meta_query_type'] ) : 'CHAR';
			$compare = ! empty( $settings['meta_query_compare'] ) ? $settings['meta_query_compare'] : '=';
			$value   = isset( $settings['meta_query_val'] ) ? $settings['meta_query_val'] : '';

			if ( ! empty( $settings['meta_query_request_val'] ) ) {

				$query_var = $settings['meta_query_request_val'];

				if ( isset( $_GET[ $query_var ] ) ) {
					$request_val = $_GET[ $query_var ];
				} else {
					$request_val = get_query_var( $query_var );
				}

				if ( $request_val ) {
					$value = $request_val;
				}

			}

			$value = jet_engine()->listings->macros->do_macros( $value, $key );

			if ( in_array( $compare, array( 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN' ) ) ) {
				$value = $this->explode_string( $value );
			}

			if ( in_array( $type, array( 'DATE', 'DATETIME' ) ) ) {

				if ( is_array( $value ) ) {
					$value = array_map( 'strtotime', $value );
				} else {
					$value = strtotime( $value );
				}

				$type = 'NUMERIC';

			}

			$args['meta_query'][] = array(
				'key'     => $key,
				'value'   => $value,
				'compare' => $compare,
				'type'    => $type,
			);

			return $args;

		}

		/**
		 * Add date query parameters to args.
		 */
		public function add_date_query_to_args( $args, $settings ) {

			$column    = isset( $settings['date_query_column'] ) ? $settings['date_query_column'] : 'post_date';
			$after     = isset( $settings['date_query_after'] ) ? $settings['date_query_after'] : '';
			$before    = isset( $settings['date_query_before'] ) ? $settings['date_query_before'] : '';
			$after     = jet_engine()->listings->macros->do_macros( $after );
			$before    = jet_engine()->listings->macros->do_macros( $before );

			$args['date_query'][] = array(
				'column'    => $column,
				'after'     => $after,
				'before'    => $before,
			);

			return $args;

		}

		/**
		 * Explode string to array
		 *
		 * @param  [type] $string [description]
		 * @return [type]         [description]
		 */
		public function explode_string( $string ) {

			if ( is_array( $string ) ) {
				return $string;
			}

			$array = explode( ',', $string );

			if ( empty( $array ) ) {
				return array();
			}

			return array_filter( array_map( 'trim', $array ) );
		}

		/**
		 * Get listings to show
		 *
		 * @return void
		 */
		public function get_listings() {
			$listings = jet_engine()->listings->get_listings();
			return wp_list_pluck( $listings, 'post_title', 'ID' );
		}

		/**
		 * Get posts
		 *
		 * @return [type] [description]
		 */
		public function get_posts( $settings ) {

			if ( isset( $settings['is_archive_template'] ) && 'yes' === $settings['is_archive_template'] ) {

				global $wp_query;

				// Ensure jet-engine/listing/grid/posts-query-args hook correctly fires even for archive (For filters compat)
				$default_query = array(
					'post_status'    => 'publish',
					'found_posts'    => $wp_query->found_posts,
					'max_num_pages'  => $wp_query->max_num_pages,
					'post_type'      => $wp_query->get( 'post_type' ),
					'tax_query'      => $wp_query->get( 'tax_query' ),
					'orderby'        => $wp_query->get( 'orderby' ),
					'paged'          => $wp_query->get( 'paged' ),
					'posts_per_page' => $wp_query->get( 'posts_per_page' ),
				);

				if ( $wp_query->get( 'taxonomy' ) ) {
					$default_query['taxonomy'] = $wp_query->get( 'taxonomy' );
					$default_query['term']     = $wp_query->get( 'term' );
				}

				$default_query = apply_filters( 'jet-engine/listing/grid/posts-query-args', $default_query, $this );

				return $wp_query->posts;

			} else {

				$args  = $this->build_posts_query_args_array( $settings );
				$query = new \WP_Query( $args );

				return $query->posts;
			}

		}

		/**
		 * Get terms list
		 *
		 * @param  [type] $settings     [description]
		 * @return [type]               [description]
		 */
		public function get_terms( $settings ) {

			$args = $this->build_terms_query_args_array( $settings );

			return get_terms( $args );
		}

		/**
		 * Check widget visibility settings and hide if false
		 *
		 * @param  array  $query    Query array.
		 * @param  array  $settings Settings array.
		 * @return boolean
		 */
		public function is_widget_visible( $query, $settings ) {

			if ( ! empty( $settings['hide_widget_if'] ) ) {

				switch ( $settings['hide_widget_if'] ) {

					case 'empty_query':

						return empty( $query ) ? false : true;

						break;

					default:

						if ( is_callable( $settings['hide_widget_if'] ) ) {
							return call_user_func( $settings['hide_widget_if'], $query, $settings );
						} else {
							return apply_filters( 'jet-engine/listing/grid/widget-visibility', true, $query, $settings );
						}

						break;
				}

			}

			return true;

		}

		/**
		 * Returns widget settings or custom settings
		 *
		 * @return void
		 */
		public function get_widget_settings() {

			$custom_settings = apply_filters( 'jet-engine/listing/grid/custom-settings', false, $this );

			if ( ! empty( $custom_settings ) ) {
				return $custom_settings;
			} else {
				return $this->get_settings();
			}

		}

		/**
		 * Render grid posts
		 *
		 * @return [type] [description]
		 */
		public function render_posts() {

			$settings = $this->get_widget_settings();

			if ( empty( $settings['lisitng_id'] ) ) {
				_e( 'Please select listing to show.', 'jet-engine' );
				return;
			}

			jet_engine()->listings->data->set_listing(
				Plugin::$instance->documents->get_doc_for_frontend( $settings['lisitng_id'] )
			);

			$listing_source = jet_engine()->listings->data->get_listing_source();

			switch ( $listing_source ) {

				case 'posts':
					$query = $this->get_posts( $settings );
					break;

				case 'terms':
					$query = $this->get_terms( $settings );
					break;
			}

			if ( ! $this->is_widget_visible( $query, $settings ) ) {
				return;
			}

			$this->posts_template( $query, $settings );

			jet_engine()->listings->data->reset_listing();

		}

		/**
		 * Render posts template.
		 * Moved to separate function to be rewritten by other layouts
		 *
		 * @return [type] [description]
		 */
		public function posts_template( $query, $settings ) {

			$base_class  = $this->get_name();
			$desktop_col = ! empty( $settings['columns'] ) ? absint( $settings['columns'] ) : 3;
			$tablet_col  = ! empty( $settings['columns_tablet'] ) ? absint( $settings['columns_tablet'] ) : $desktop_col;
			$mobile_col  = ! empty( $settings['columns_mobile'] ) ? absint( $settings['columns_mobile'] ) : $tablet_col;
			$base        = 'grid-col-';

			$column_classes = array(
				$base . 'desk-' . $desktop_col,
				$base . 'tablet-' . $tablet_col,
				$base . 'mobile-' . $mobile_col,
			);

			$column_classes   = implode( ' ', $column_classes );
			$carousel_enabled = ! empty( $settings['carousel_enabled'] ) ? $settings['carousel_enabled'] : false;

			printf( '<div class="%1$s jet-listing">', $base_class );

				if ( ! empty( $query ) ) {

					do_action( 'jet-engine/listing/grid/before', $this );

					if ( $carousel_enabled ) {

						$is_rtl                  = is_rtl();
						$dir                     = $is_rtl ? 'rtl' : 'ltr';
						$settings['items_count'] = count( $query );

						printf(
							'<div class="%1$s__slider" data-slider_options="%2$s" dir="%3$s">',
							$base_class,
							$this->get_slider_options( $settings, $is_rtl ),
							$dir
						);

						// Enqueue script only if carousel is used
						wp_enqueue_script( 'jquery-slick' );

					}

					$equal_cols_class = '';
					$equal_cols_wrap_class = '';

					if ( ! empty( $settings['equal_columns_height'] ) ) {
						$equal_cols_class .= ' jet-equal-columns';
						$equal_cols_wrap_class .= 'jet-equal-columns__wrapper';
					}

					printf( '<div class="%1$s__items %2$s %3$s">', $base_class, $column_classes, $equal_cols_wrap_class );

					foreach ( $query as $post ) {

						jet_engine()->frontend->set_listing( $settings['lisitng_id'] );

						$content = jet_engine()->frontend->get_listing_item( $post );

						printf(
							'<div class="%1$s__item%4$s" data-post-id="%3$s">%2$s</div>',
							$base_class,
							$content,
							$post->ID,
							$equal_cols_class
						);

					}

					jet_engine()->frontend->reset_listing();

					echo '</div>';

					if ( $carousel_enabled ) {
						echo '</div>';
					}

					do_action( 'jet-engine/listing/grid/after', $this );

				} else {
					printf(
						'<div class="jet-listing-not-found">%s</div>',
						do_shortcode( wp_unslash( $settings['not_found_message'] ) )
					);
				}

			echo '</div>';

		}

		/**
		 * Returns formatted slider options
		 *
		 * @return [type] [description]
		 */
		public function get_slider_options( $settings = array(), $is_rtl = false ) {

			$prev_arrow_icon = sprintf(
				'<i class="%1$s__slider-icon prev-arrow %2$s"></i>',
				$this->get_name(),
				$settings['arrow_icon']
			);

			$next_arrow_icon = sprintf(
				'<i class="%1$s__slider-icon next-arrow %2$s"></i>',
				$this->get_name(),
				$settings['arrow_icon']
			);

			$options = apply_filters( 'jet-engine/listing/grid/slider-options', array(
				'slidesToShow'   => array(
					'desktop' => absint( $settings['columns'] ),
					'tablet'  => absint( $settings['columns_tablet'] ),
					'mobile'  => absint( $settings['columns_mobile'] ),
				),
				'autoplaySpeed'  => absint( $settings['autoplay_speed'] ),
				'autoplay'       => filter_var( $settings['autoplay'], FILTER_VALIDATE_BOOLEAN ),
				'infinite'       => filter_var( $settings['infinite'], FILTER_VALIDATE_BOOLEAN ),
				'speed'          => absint( $settings['speed'] ),
				'arrows'         => filter_var( $settings['arrows'], FILTER_VALIDATE_BOOLEAN ),
				'dots'           => filter_var( $settings['dots'], FILTER_VALIDATE_BOOLEAN ),
				'slidesToScroll' => absint( $settings['slides_to_scroll'] ),
				'prevArrow'      => $prev_arrow_icon,
				'nextArrow'      => $next_arrow_icon,
				'rtl'            => $is_rtl,
				'itemsCount'     => $settings['items_count'],
			) );

			return htmlspecialchars( json_encode( $options ) );

		}

		protected function render() {
			$this->render_posts();
		}

	}

}
