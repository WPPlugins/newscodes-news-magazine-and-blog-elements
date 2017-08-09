<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NC_Admin {

	public static $defaults;
	public static $generator;
	public static $settings;
	public static $styles;
	public static $shortcodes;
	public static $less_helper;

	public static function init() {

		self::$generator = array();

		$choices = array();
		foreach( NC_Setup::$newscodes['types'] as $k => $v ) {
			$choices[] = array(
				'value' => $k,
				'label' => $v
			);
		}

		self::$generator[] = array(
			'field_id'          => 'nc_type',
			'field_label'       => __( 'Type', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => 'news-list-compact',
			'field_type'        => 'select',
			'field_settings'    => '',
			'field_name'        => 'type',
			'field_class'       => '',
			'field_choices'     => $choices
		);

		self::$generator[] = array(
			'field_id'          => 'nc_style',
			'field_label'       => __( 'Style', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => '',
			'field_type'        => 'style',
			'field_settings'    => '',
			'field_name'        => 'style',
			'field_class'       => '',
			'field_choices'     => array()
		);


		$choices = array();
		$args = array(
			'public'   => true
		);

		$post_types = get_post_types( $args, 'objects' );
		foreach( $post_types as $v ) {
			$choices[] = array(
				'value' => $v->name,
				'label' => $v->labels->name
			);
		}
		self::$generator[] = array(
			'field_id'          => 'nc_post_type',
			'field_label'       => __( 'Post Type', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => 'post',
			'field_type'        => 'select',
			'field_settings'    => '',
			'field_name'        => 'post_type',
			'field_class'       => '',
			'field_choices'     => $choices
		);

		$choices = array(
			array(
				'value' => 'publish',
				'label' => __( 'Published', 'nwscds' )
			),
			array(
				'value' => 'pending',
				'label' => __( 'Pending', 'nwscds' )
			),
			array(
				'value' => 'draft',
				'label' =>__( 'Draft', 'nwscds' )
			),
			array(
				'value' => 'future',
				'label' => __( 'Future', 'nwscds' )
			),
			array(
				'value' => 'private',
				'label' => __( 'Private', 'nwscds' )
			),
			array(
				'value' => 'trash',
				'label' => __( 'Trash', 'nwscds' )
			),
			array(
				'value' => 'any',
				'label' => __( 'Any', 'nwscds' )
			)
		);
		self::$generator[] = array(
			'field_id'          => 'nc_post_status',
			'field_label'       => __( 'Post Status', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => 'publish',
			'field_type'        => 'select',
			'field_settings'    => '',
			'field_name'        => 'post_status',
			'field_class'       => '',
			'field_choices'     => $choices
		);

		$choices = array(
			array(
				'value' => '1',
				'label' => '1'
			),
			array(
				'value' => '2',
				'label' => '2'
			),
			array(
				'value' => '3',
				'label' => '3'
			),
			array(
				'value' => '4',
				'label' => '4'
			),
			array(
				'value' => '5',
				'label' => '5'
			),
			array(
				'value' => '6',
				'label' => '6'
			)
		);
		self::$generator[] = array(
			'field_id'          => 'nc_columns',
			'field_label'       => __( 'Columns', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => '1',
			'field_type'        => 'select',
			'field_settings'    => '',
			'field_name'        => 'columns',
			'field_class'       => '',
			'field_choices'     => $choices
		);

		self::$generator[] = array(
			'field_id'          => 'nc_posts_per_page',
			'field_label'       => __( 'Posts per Page', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => '10',
			'field_type'        => 'text',
			'field_settings'    => '',
			'field_name'        => 'posts_per_page',
			'field_class'       => ''
		);

		self::$generator[] = array(
			'field_id'          => 'nc_posts_per_column',
			'field_label'       => __( 'Posts per Column', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => '3',
			'field_type'        => 'text',
			'field_settings'    => '',
			'field_name'        => 'posts_per_column',
			'field_class'       => ''
		);

		self::$generator[] = array(
			'field_id'          => 'nc_offset',
			'field_label'       => __( 'Offset', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => '',
			'field_type'        => 'text',
			'field_settings'    => '',
			'field_name'        => 'offset',
			'field_class'       => ''
		);

		$choices = array(
			array(
				'value' => 'none',
				'label' => __( 'None', 'nwscds' )
			),
			array(
				'value' => 'ID',
				'label' => __( 'ID', 'nwscds' )
			),
			array(
				'value' => 'author',
				'label' =>__( 'Author', 'nwscds' )
			),
			array(
				'value' => 'title',
				'label' => __( 'Title', 'nwscds' )
			),
			array(
				'value' => 'name',
				'label' => __( 'Name', 'nwscds' )
			),
			array(
				'value' => 'date',
				'label' => __( 'Date', 'nwscds' )
			),
			array(
				'value' => 'modified',
				'label' => __( 'Modified', 'nwscds' )
			),
			array(
				'value' => 'rand',
				'label' => __( 'Random', 'nwscds' )
			),
			array(
				'value' => 'comment_count',
				'label' => __( 'Comment Count', 'nwscds' )
			),
			array(
				'value' => 'menu_order',
				'label' => __( 'Menu Order', 'nwscds' )
			),
			array(
				'value' => 'post__in',
				'label' => __( 'Post In', 'nwscds' )
			)
		);
		self::$generator[] = array(
			'field_id'          => 'nc_orderby',
			'field_label'       => __( 'Order By', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => 'date',
			'field_type'        => 'select',
			'field_settings'    => '',
			'field_name'        => 'orderby',
			'field_class'       => '',
			'field_choices'     => $choices
		);

		$choices = array(
			array(
				'value' => 'DESC',
				'label' => __( 'Descending', 'nwscds' )
			),
			array(
				'value' => 'ASC',
				'label' => __( 'Ascending', 'nwscds' )
			)
		);
		self::$generator[] = array(
			'field_id'          => 'nc_order',
			'field_label'       => __( 'Order', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => 'DESC',
			'field_type'        => 'select',
			'field_settings'    => '',
			'field_name'        => 'order',
			'field_class'       => '',
			'field_choices'     => $choices
		);

		self::$generator[] = array(
			'field_id'          => 'nc_post_in',
			'field_label'       => __( 'Post In', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => '',
			'field_type'        => 'text',
			'field_settings'    => '',
			'field_name'        => 'post_in',
			'field_class'       => ''
		);

		self::$generator[] = array(
			'field_id'          => 'nc_post_notin',
			'field_label'       => __( 'Post Not In', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => '',
			'field_type'        => 'text',
			'field_settings'    => '',
			'field_name'        => 'post_notin',
			'field_class'       => ''
		);

		self::$generator[] = array(
			'field_id'          => 'nc_filters',
			'field_label'       => __( 'Post Filters', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => '',
			'field_type'        => 'filters',
			'field_settings'    => '',
			'field_name'        => 'filters',
			'field_class'       => '',
			'field_choices'     => $choices
		);

		$choices = array(
			array(
				'value' => 'AND',
				'label' => __( 'AND', 'nwscds' )
			),
			array(
				'value' => 'OR',
				'label' => __( 'OR', 'nwscds' )
			)
		);
		self::$generator[] = array(
			'field_id'          => 'nc_filter_relation',
			'field_label'       => __( 'Taxonomy Filter Relation', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => 'OR',
			'field_type'        => 'select',
			'field_settings'    => '',
			'field_name'        => 'filter_relation',
			'field_class'       => '',
			'field_choices'     => $choices
		);


		self::$generator[] = array(
			'field_id'          => 'nc_meta_relation',
			'field_label'       => __( 'Meta Filter Relation', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => 'OR',
			'field_type'        => 'select',
			'field_settings'    => '',
			'field_name'        => 'meta_relation',
			'field_class'       => '',
			'field_choices'     => $choices
		);

		self::$generator[] = array(
			'field_id'          => 'nc_http_query',
			'field_label'       => __( 'HTTP Query', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => '',
			'field_type'        => 'textarea',
			'field_settings'    => '',
			'field_name'        => 'http_query',
			'field_class'       => ''
		);

		$choices = array(
			array(
				'value' => '1-1',
				'label' => '1 : 1'
			),
			array(
				'value' => '2-1',
				'label' => '2 : 1'
			),
			array(
				'value' => '1-2',
				'label' => '1 : 2'
			),
			array(
				'value' => '3-1',
				'label' => '3 : 1'
			),
			array(
				'value' => '1-3',
				'label' => '1 : 3'
			),
			array(
				'value' => '4-3',
				'label' => '4 : 3'
			),
			array(
				'value' => '3-4',
				'label' => '3 : 4'
			),
			array(
				'value' => '16-9',
				'label' => '16 : 9'
			),
			array(
				'value' => '9-16',
				'label' => '9 : 16'
			),
			array(
				'value' => '5-3',
				'label' => '5 : 3'
			),
			array(
				'value' => '3-5',
				'label' => '3 : 5'
			),
		);
		self::$generator[] = array(
			'field_id'          => 'nc_image_ratio',
			'field_label'       => __( 'Image Ratio', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => '4-3',
			'field_type'        => 'select',
			'field_settings'    => '',
			'field_name'        => 'image_ratio',
			'field_class'       => '',
			'field_choices'     => $choices
		);

		$choices = array(
			array(
				'value' => '',
				'label' => __( 'Default', 'nwscds' )
			),
			array(
				'value' => 'full',
				'label' => __( 'Full', 'nwscds' )
			)
		);

		$image_sizes = get_intermediate_image_sizes();

		foreach ( $image_sizes as $image_size ) {
			$choices[] = array(
				'value' => $image_size,
				'label' => $image_size
			);
		}
		self::$generator[] = array(
			'field_id'          => 'nc_image_size',
			'field_label'       => __( 'Image Size', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => '',
			'field_type'        => 'select',
			'field_settings'    => '',
			'field_name'        => 'image_size',
			'field_class'       => '',
			'field_choices'     => $choices
		);

		$choices = array(
			array(
				'value' => 'h1',
				'label' => __( 'Heading 1', 'nwscds' )
			),
			array(
				'value' => 'h2',
				'label' => __( 'Heading 2', 'nwscds' )
			),
			array(
				'value' => 'h3',
				'label' => __( 'Heading 3', 'nwscds' )
			),
			array(
				'value' => 'h4',
				'label' => __( 'Heading 4', 'nwscds' )
			),
			array(
				'value' => 'h5',
				'label' => __( 'Heading 5', 'nwscds' )
			),
			array(
				'value' => 'h6',
				'label' => __( 'Heading 6', 'nwscds' )
			)
		);
		self::$generator[] = array(
			'field_id'          => 'nc_title_tag',
			'field_label'       => __( 'Title Tag', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => 'h2',
			'field_type'        => 'select',
			'field_settings'    => '',
			'field_name'        => 'title_tag',
			'field_class'       => '',
			'field_choices'     => $choices
		);

		self::$generator[] = array(
			'field_id'          => 'nc_title_cut',
			'field_label'       => __( 'Title Cut', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => 'false',
			'field_type'        => 'checkbox',
			'field_settings'    => '',
			'field_name'        => 'title_cut',
			'field_class'       => ''
		);

		self::$generator[] = array(
			'field_id'          => 'nc_show_date',
			'field_label'       => __( 'Show Date', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => 'true',
			'field_type'        => 'checkbox',
			'field_settings'    => '',
			'field_name'        => 'show_date',
			'field_class'       => ''
		);

		self::$generator[] = array(
			'field_id'          => 'nc_show_time',
			'field_label'       => __( 'Show Time', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => 'true',
			'field_type'        => 'checkbox',
			'field_settings'    => '',
			'field_name'        => 'show_time',
			'field_class'       => ''
		);

		self::$generator[] = array(
			'field_id'          => 'nc_show_taxonomy',
			'field_label'       => __( 'Show Taxonomy', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => 'true',
			'field_type'        => 'checkbox',
			'field_settings'    => '',
			'field_name'        => 'show_taxonomy',
			'field_class'       => ''
		);

		self::$generator[] = array(
			'field_id'          => 'nc_which_taxonomy',
			'field_label'       => __( 'Which Taxonomy?', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => '',
			'field_type'        => 'text',
			'field_settings'    => '',
			'field_name'        => 'which_taxonomy',
			'field_class'       => ''
		);

		self::$generator[] = array(
			'field_id'          => 'nc_show_author',
			'field_label'       => __( 'Show Author', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => 'true',
			'field_type'        => 'checkbox',
			'field_settings'    => '',
			'field_name'        => 'show_author',
			'field_class'       => ''
		);

		self::$generator[] = array(
			'field_id'          => 'nc_show_format',
			'field_label'       => __( 'Show Format', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => 'true',
			'field_type'        => 'checkbox',
			'field_settings'    => '',
			'field_name'        => 'show_format',
			'field_class'       => ''
		);

		self::$generator[] = array(
			'field_id'          => 'nc_excerpt_length',
			'field_label'       => __( 'Excerpt Length?', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => '20',
			'field_type'        => 'text',
			'field_settings'    => '',
			'field_name'        => 'excerpt_length',
			'field_class'       => ''
		);

		self::$generator[] = array(
			'field_id'          => 'nc_excerpt_more',
			'field_label'       => __( 'Excerpt More?', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => '',
			'field_type'        => 'textarea',
			'field_settings'    => '',
			'field_name'        => 'excerpt_more',
			'field_class'       => ''
		);

		self::$generator[] = array(
			'field_id'          => 'nc_pagination',
			'field_label'       => __( 'Pagination', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => 'true',
			'field_type'        => 'checkbox',
			'field_settings'    => '',
			'field_name'        => 'pagination',
			'field_class'       => ''
		);

		self::$generator[] = array(
			'field_id'          => 'nc_ajax',
			'field_label'       => __( 'Ajax', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => 'true',
			'field_type'        => 'checkbox',
			'field_settings'    => '',
			'field_name'        => 'ajax',
			'field_class'       => ''
		);

		self::$generator[] = array(
			'field_id'          => 'nc_load_more',
			'field_label'       => __( 'Load More', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => 'false',
			'field_type'        => 'checkbox',
			'field_settings'    => '',
			'field_name'        => 'load_more',
			'field_class'       => ''
		);

		self::$generator[] = array(
			'field_id'          => 'nc_ticker_visible',
			'field_label'       => __( 'Visible in Ticker', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => '3',
			'field_type'        => 'text',
			'field_settings'    => '',
			'field_name'        => 'ticker_visible',
			'field_class'       => ''
		);

		$choices = array(
			array(
				'value' => 'up',
				'label' => __( 'Up', 'nwscds' )
			),
			array(
				'value' => 'down',
				'label' => __( 'Down', 'nwscds' )
			)
		);
		self::$generator[] = array(
			'field_id'          => 'nc_ticker_direction',
			'field_label'       => __( 'Ticker Direction', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => 'up',
			'field_type'        => 'select',
			'field_settings'    => '',
			'field_name'        => 'ticker_direction',
			'field_class'       => '',
			'field_choices'     => $choices
		);

		$choices = array(
			array(
				'value' => 'left',
				'label' => __( 'Left', 'nwscds' )
			),
			array(
				'value' => 'right',
				'label' => __( 'Right', 'nwscds' )
			)
		);
		self::$generator[] = array(
			'field_id'          => 'nc_marquee_direction',
			'field_label'       => __( 'Marquee Direction', 'nwscds' ),
			'field_desc'        => '',
			'field_value'       => 'left',
			'field_type'        => 'select',
			'field_settings'    => '',
			'field_name'        => 'marquee_direction',
			'field_class'       => '',
			'field_choices'     => $choices
		);

		self::$defaults = array(
			array(
				'field_id'          => 'nc_name',
				'field_label'       => __( 'Style name', 'nwscds' ),
				'field_desc'        => __( 'Enter style name', 'nwscds' ),
				'field_value'       => '',
				'field_type'        => 'text',
				'field_settings'    => '',
				'field_name'        => 'name',
				'field_class'       => ''
			),
			array(
				'field_id'          => 'nc_heading',
				'field_label'       => __( 'Heading', 'nwscds' ),
				'field_desc'        => __( 'Color, Font and Text settings', 'nwscds' ),
				'field_value'       => array(),
				'field_type'        => 'typography',
				'field_settings'    => '',
				'field_name'        => 'nc_heading',
				'field_class'       => '',
				'field_color'       => '#222'
			),
			array(
				'field_id'          => 'nc_heading_hover',
				'field_label'       => __( 'Heading', 'nwscds' ),
				'field_desc'        => __( 'Hover color', 'nwscds' ),
				'field_value'       => '',
				'field_type'        => 'color',
				'field_settings'    => '',
				'field_name'        => 'nc_heading_hover',
				'field_class'       => '',
				'field_std'         => '#f00'
			),
			array(
				'field_id'          => 'nc_excerpt',
				'field_label'       => __( 'Excerpt', 'nwscds' ),
				'field_desc'        => __( 'Color, Font and Text settings', 'nwscds' ),
				'field_value'       => array(),
				'field_type'        => 'typography',
				'field_settings'    => '',
				'field_name'        => 'nc_excerpt',
				'field_class'       => '',
				'field_color'       => '#777'
			),
			array(
				'field_id'          => 'nc_meta',
				'field_label'       => __( 'Meta', 'nwscds' ),
				'field_desc'        => __( 'Color, Font and Text settings', 'nwscds' ),
				'field_value'       => array(),
				'field_type'        => 'typography',
				'field_settings'    => '',
				'field_name'        => 'nc_meta',
				'field_class'       => '',
				'field_color'       => '#ccc'
			),
			array(
				'field_id'          => 'nc_meta_background',
				'field_label'       => __( 'Meta', 'nwscds' ),
				'field_desc'        => __( 'Background color', 'nwscds' ),
				'field_value'       => '',
				'field_type'        => 'color',
				'field_settings'    => '',
				'field_name'        => 'nc_meta_background',
				'field_class'       => '',
				'field_std'         => '#fff'
			),
			array(
				'field_id'          => 'nc_taxonomy_color',
				'field_label'       => __( 'Taxonomy', 'nwscds' ),
				'field_desc'        => __( 'Text color', 'nwscds' ),
				'field_value'       => '',
				'field_type'        => 'color',
				'field_settings'    => '',
				'field_name'        => 'nc_taxonomy_color',
				'field_class'       => '',
				'field_std'         => '#fff'
			),
			array(
				'field_id'          => 'nc_taxonomy_background',
				'field_label'       => __( 'Taxonomy', 'nwscds' ),
				'field_desc'        => __( 'Background color', 'nwscds' ),
				'field_value'       => '',
				'field_type'        => 'color',
				'field_settings'    => '',
				'field_name'        => 'nc_taxonomy_background',
				'field_class'       => '',
				'field_std'         => '#f00'
			),
			array(
				'field_id'          => 'nc_navigation',
				'field_label'       => __( 'Navigation', 'nwscds' ),
				'field_desc'        => __( 'Color, Font and Text settings', 'nwscds' ),
				'field_value'       => array(),
				'field_type'        => 'typography',
				'field_settings'    => '',
				'field_name'        => 'nc_navigation',
				'field_class'       => '',
				'field_color'       => '#ccc'
			),
			array(
				'field_id'          => 'nc_navigation_hover',
				'field_label'       => __( 'Navigation', 'nwscds' ),
				'field_desc'        => __( 'Hover color', 'nwscds' ),
				'field_value'       => '',
				'field_type'        => 'color',
				'field_settings'    => '',
				'field_name'        => 'nc_navigation_hover',
				'field_class'       => '',
				'field_std'         => '#f00'
			),
			array(
				'field_id'          => 'nc_navigation_style',
				'field_label'       => __( 'Navigation', 'nwscds' ),
				'field_desc'        => __( 'Style CSS', 'nwscds' ),
				'field_std'         => 'border',
				'field_type'        => 'select',
				'field_settings'    => '',
				'field_name'        => 'nc_navigation_style',
				'field_class'       => '',
				'field_choices'     => array(
					array(
						'value' => 'border',
						'label' => __( 'Border', 'nwscds' )
					),
					array(
						'value' => 'background-color',
						'label' => __( 'Background Color', 'nwscds' )
					),
					array(
						'value' => 'text-only',
						'label' => __( 'Text Only', 'nwscds' )
					),
					array(
						'value' => 'flat',
						'label' => __( 'Flat', 'nwscds' )
					),
					array(
						'value' => '3d',
						'label' => __( '3D', 'nwscds' )
					)
				)
			),
			array(
				'field_id'          => 'nc_tabs',
				'field_label'       => __( 'Tabs', 'nwscds' ),
				'field_desc'        => __( 'Color, Font and Text settings', 'nwscds' ),
				'field_value'       => array(),
				'field_type'        => 'typography',
				'field_settings'    => '',
				'field_name'        => 'nc_tabs',
				'field_class'       => '',
				'field_color'       => '#ccc'
			),
			array(
				'field_id'          => 'nc_tabs_hover',
				'field_label'       => __( 'Tabs', 'nwscds' ),
				'field_desc'        => __( 'Hover color', 'nwscds' ),
				'field_value'       => '',
				'field_type'        => 'color',
				'field_settings'    => '',
				'field_name'        => 'nc_tabs_hover',
				'field_class'       => '',
				'field_std'         => '#f00'
			),
			array(
				'field_id'          => 'nc_tabs_style',
				'field_label'       => __( 'Tabs', 'nwscds' ),
				'field_desc'        => __( 'Style CSS', 'nwscds' ),
				'field_std'         => 'border',
				'field_type'        => 'select',
				'field_settings'    => '',
				'field_name'        => 'nc_tabs_style',
				'field_class'       => '',
				'field_choices'     => array(
					array(
						'value' => 'border',
						'label' => __( 'Border', 'nwscds' )
					),
					array(
						'value' => 'background-color',
						'label' => __( 'Background Color', 'nwscds' )
					),
					array(
						'value' => 'text-only',
						'label' => __( 'Text Only', 'nwscds' )
					),
					array(
						'value' => 'flat',
						'label' => __( 'Flat', 'nwscds' )
					),
					array(
						'value' => '3d',
						'label' => __( '3D', 'nwscds' )
					)
				)
			),
			array(
				'field_id'          => 'nc_format_standard',
				'field_label'       => __( 'Standard', 'nwscds' ),
				'field_desc'        => __( 'Post format background color', 'nwscds' ),
				'field_value'       => '',
				'field_type'        => 'color',
				'field_settings'    => '',
				'field_name'        => 'nc_format_standard',
				'field_class'       => '',
				'field_std'         => '#888'
			),
			array(
				'field_id'          => 'nc_format_aside',
				'field_label'       => __( 'Aside', 'nwscds' ),
				'field_desc'        => __( 'Post format background color', 'nwscds' ),
				'field_value'       => '',
				'field_type'        => 'color',
				'field_settings'    => '',
				'field_name'        => 'nc_format_aside',
				'field_class'       => '',
				'field_std'         => '#4358ab'
			),
			array(
				'field_id'          => 'nc_format_chat',
				'field_label'       => __( 'Chat', 'nwscds' ),
				'field_desc'        => __( 'Post format background color', 'nwscds' ),
				'field_value'       => '',
				'field_type'        => 'color',
				'field_settings'    => '',
				'field_name'        => 'nc_format_chat',
				'field_class'       => '',
				'field_std'         => '#b1b1b1'
			),
			array(
				'field_id'          => 'nc_format_link',
				'field_label'       => __( 'Link', 'nwscds' ),
				'field_desc'        => __( 'Post format background color', 'nwscds' ),
				'field_value'       => '',
				'field_type'        => 'color',
				'field_settings'    => '',
				'field_name'        => 'nc_format_link',
				'field_class'       => '',
				'field_std'         => '#fb8c04'
			),
			array(
				'field_id'          => 'nc_format_gallery',
				'field_label'       => __( 'Gallery', 'nwscds' ),
				'field_desc'        => __( 'Post format background color', 'nwscds' ),
				'field_value'       => '',
				'field_type'        => 'color',
				'field_settings'    => '',
				'field_name'        => 'nc_format_gallery',
				'field_class'       => '',
				'field_std'         => '#b382e8'
			),
			array(
				'field_id'          => 'nc_format_image',
				'field_label'       => __( 'Image', 'nwscds' ),
				'field_desc'        => __( 'Post format background color', 'nwscds' ),
				'field_value'       => '',
				'field_type'        => 'color',
				'field_settings'    => '',
				'field_name'        => 'nc_format_image',
				'field_class'       => '',
				'field_std'         => '#4fc03f'
			),
			array(
				'field_id'          => 'nc_format_quote',
				'field_label'       => __( 'Quote', 'nwscds' ),
				'field_desc'        => __( 'Post format background color', 'nwscds' ),
				'field_value'       => '',
				'field_type'        => 'color',
				'field_settings'    => '',
				'field_name'        => 'nc_format_quote',
				'field_class'       => '',
				'field_std'         => '#332f53'
			),
			array(
				'field_id'          => 'nc_format_status',
				'field_label'       => __( 'Status', 'nwscds' ),
				'field_desc'        => __( 'Post format background color', 'nwscds' ),
				'field_value'       => '',
				'field_type'        => 'color',
				'field_settings'    => '',
				'field_name'        => 'nc_format_status',
				'field_class'       => '',
				'field_std'         => '#92836d'
			),
			array(
				'field_id'          => 'nc_format_video',
				'field_label'       => __( 'Video', 'nwscds' ),
				'field_desc'        => __( 'Post format background color', 'nwscds' ),
				'field_value'       => '',
				'field_type'        => 'color',
				'field_settings'    => '',
				'field_name'        => 'nc_format_video',
				'field_class'       => '',
				'field_std'         => '#f00'
			),
			array(
				'field_id'          => 'nc_format_audio',
				'field_label'       => __( 'Audio', 'nwscds' ),
				'field_desc'        => __( 'Post format background color', 'nwscds' ),
				'field_value'       => '',
				'field_type'        => 'color',
				'field_settings'    => '',
				'field_name'        => 'nc_format_video',
				'field_class'       => '',
				'field_std'         => '#1f80e0'
			),
			array(
				'field_id'          => 'nc_tabs_padding',
				'field_label'       => __( 'Tabs', 'nwscds' ),
				'field_desc'        => __( 'Padding', 'nwscds' ),
				'field_value'       => '',
				'field_type'        => 'padding',
				'field_settings'    => '',
				'field_name'        => 'nc_tabs_padding',
				'field_class'       => '',
				'field_std'         => '20px'
			),
			array(
				'field_id'          => 'nc_image_padding',
				'field_label'       => __( 'Image', 'nwscds' ),
				'field_desc'        => __( 'Padding', 'nwscds' ),
				'field_value'       => '',
				'field_type'        => 'padding',
				'field_settings'    => '',
				'field_name'        => 'nc_image_padding',
				'field_class'       => '',
				'field_std'         => '20px'
			),
			array(
				'field_id'          => 'nc_meta_padding',
				'field_label'       => __( 'Meta', 'nwscds' ),
				'field_desc'        => __( 'Padding', 'nwscds' ),
				'field_value'       => '',
				'field_type'        => 'padding',
				'field_settings'    => '',
				'field_name'        => 'nc_meta_padding',
				'field_class'       => '',
				'field_std'         => '20px'
			),
			array(
				'field_id'          => 'nc_heading_padding',
				'field_label'       => __( 'Heading', 'nwscds' ),
				'field_desc'        => __( 'Padding', 'nwscds' ),
				'field_value'       => '',
				'field_type'        => 'padding',
				'field_settings'    => '',
				'field_name'        => 'nc_heading_padding',
				'field_class'       => '',
				'field_std'         => '20px'
			),
			array(
				'field_id'          => 'nc_excerpt_padding',
				'field_label'       => __( 'Excerpt', 'nwscds' ),
				'field_desc'        => __( 'Padding', 'nwscds' ),
				'field_value'       => '',
				'field_type'        => 'padding',
				'field_settings'    => '',
				'field_name'        => 'nc_excerpt_padding',
				'field_class'       => '',
				'field_std'         => '20px'
			),
			array(
				'field_id'          => 'nc_pagination_padding',
				'field_label'       => __( 'Pagination', 'nwscds' ),
				'field_desc'        => __( 'Padding', 'nwscds' ),
				'field_value'       => '',
				'field_type'        => 'padding',
				'field_settings'    => '',
				'field_name'        => 'nc_pagination_padding',
				'field_class'       => '',
				'field_std'         => '20px'
			)
		);

		$page = new NC_Page_Metaboxes( 'options-general.php', __( 'Newscodes Settings', 'nwscds' ), __( 'Newscodes', 'nwscds' ), 'manage_options', 'newscodes_settings', 'NC_Admin::settings_content' );

		self::$settings = get_option( 'newscodes_settings', array( 'styles' => array() ) );
		self::$styles = self::$settings['styles'];

		self::$shortcodes = get_option( 'newscodes_shortcodes', array() );

		add_action( 'add_meta_boxes', __CLASS__ . '::add_metaboxes' );
		add_action( 'admin_enqueue_scripts', __CLASS__ . '::scripts' );

		add_action( 'wp_ajax_nc_admin_ajax_factory', __CLASS__ . '::ajax_factory' );

		add_filter ( 'nc_admin_less_variables_update', __CLASS__ . '::less_variables' );
		add_filter ( 'nc_admin_less_preview', __CLASS__ . '::less_variables_preview' );

		add_action( 'admin_head', __CLASS__ . '::add_icon' );

		$plugin = NC()->plugin_basename();
		add_filter( 'plugin_action_links_' . $plugin, __CLASS__ . '::settings_link' );

	}

	public static function settings_link( $links ) {

		$settings_link = '<a href="options-general.php?page=newscodes_settings">' . __( 'Settings', 'nwscds' ) . '</a>';
		array_push( $links, $settings_link );

		return $links;

	}

	public static function scripts() {

		if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'newscodes_settings' ) {

			wp_enqueue_style('wp-color-picker');
			wp_enqueue_script('wp-color-picker');

			wp_register_style( 'newscodes-admin', NC()->plugin_url() . '/lib/css/newscodes-admin.css', false, NC()->version() );
			wp_enqueue_style( 'newscodes-admin' );

			wp_register_script( 'newscodes-admin', NC()->plugin_url() . '/lib/js/newscodes-admin.js', array( 'jquery' ), NC()->version(), true );
			wp_enqueue_script( 'newscodes-admin' );

			$args = array(
				'ajax' => admin_url( 'admin-ajax.php' ),
				'sc_defaults' => NC_Setup::$newscodes['atts']
			);

			wp_localize_script( 'newscodes-admin', 'nc', $args );

		}

	}

	public static function settings_content() {
	?>
		<p>
			<?php echo 'This is a limited free version of the <strong>Newscodes - News, Magazine, Blog Elements for Wordpress</strong>! For full features, such as 20+ post list elements and style editor, to create and edit your own styles, please purchase the full version. Newscodes were coded by <a href="http://mihajlovicnenad.com" target="_blank">Mihajlovicnenad.com</a>! To get premium version please follow this <a href="http://codecanyon.net/item/newscodes-news-magazine-and-blog-elements-for-wordpress/14714969?ref=dzeriho">link</a>!'; ?>
		</p>
	<?php
	}

	public static function add_metaboxes() {
		add_meta_box( 'generator', __( 'Shortcode Generator', 'nwscds' ), 'NC_Admin::generator_metabox', 'settings_page_newscodes_settings', 'normal', 'high' );
		add_meta_box( 'styles', __( 'Style Manager', 'nwscds' ), 'NC_Admin::style_metabox', 'settings_page_newscodes_settings', 'normal', 'high' );
		add_meta_box( 'optimization', __( 'Frontend Optimizations', 'nwscds' ), 'NC_Admin::optimization_metabox', 'settings_page_newscodes_settings', 'normal', 'low' );
		add_meta_box( 'info', __( 'Information and Tips', 'nwscds' ), 'NC_Admin::side_metabox', 'settings_page_newscodes_settings', 'side', 'high' );
		add_meta_box( 'register', __( 'Registration', 'nwscds' ), 'NC_Admin::registration_metabox', 'settings_page_newscodes_settings', 'side', 'high' );
	}

	public static function generator_metabox() {
		$shortcodes = self::$shortcodes;
	?>
		<p>
			<?php _e( 'Use the shortcode generator to quickly create your Newscodes post grids!', 'nwscds' ); ?>
		</p>
		<p>
			<select id="newscodes-shortcodes">
			<?php
				if ( empty( $shortcodes ) ) {
			?>
				<option value=""><?php _e( 'No shortcodes found', 'nwscds' ); ?></option>
			<?php
				}
				else {
					foreach( $shortcodes as $slug => $parameters ) {
					?>
						<option value="<?php echo $slug; ?>"><?php echo $parameters['name']; ?></option>
					<?php
					}
				}
			?>
			</select>
		</p>
		<p>
			<span id="nc-generator" class="button-primary"><?php _e( 'Start Generator', 'nwscds' ); ?></span>
			<span id="nc-generator-edit" class="button"><?php _e( 'Edit Selected', 'nwscds' ); ?></span>
			<span id="nc-generator-delete" class="button"><?php _e( 'Delete Selected', 'nwscds' ); ?></span>
		</p>
	<?php
	}

	public static function optimization_metabox() {
	?>
		<div class="nc-admin-half">
			<h4><?php _e( 'Optimize Group CSS Loading' , 'nwscds' ); ?></h4>
			<p>
				<?php _e( 'Currently active CSS styles. Selected group CSS styles will not be loaded on the frontend.', 'nwscds' ); ?>
			</p>
		<?php

			$nc_styles = apply_filters( 'nc_supported_styles', get_option( '_nc_less_styles', array() ) );

			if ( !empty( $nc_styles ) && is_array( $nc_styles ) ) {

				$css_styles = get_option( '_nc_less_styles_exclude', array() );

			?>
				<p>
					<select id="nc-css-optimize" name="nc-css-optimize" class="nc-css-optimize" multiple="multiple">
					<?php
					foreach( $nc_styles as $group => $settings ) {
					?>
						<option class="nc-css-optimize" value="<?php echo $group; ?>" <?php echo in_array( $group, $css_styles ) ? 'selected="selected"' : ''; ?>><?php echo $group; ?>.css</option>
					<?php
						}
					?>
					</select>
				</p>
			<?php
			}
			else {
			?>
				<p>
					<?php _e( 'No style groups found.', 'nwscds' ); ?>
				</p>
			<?php
			}
		?>
			<p>
				<?php _e( 'Use CTRL+Click to select which resources will not be loaded on the frontend.', 'nwscds' ); ?>
			</p>
			<p>
				<span id="nc-update-optimizations" class="button-primary"><?php _e( 'Update Optimizations' , 'nwscds' ); ?></span>
			</p>
		</div>
	<?php
	}

	public static function style_metabox() {
		$styles = apply_filters( 'nc_supported_styles', self::$styles );
		$got_styles = array();

	?>
		<div class="nc-admin-half">
			<h4><?php _e( 'Style Groups' , 'nwscds' ); ?></h4>
			<p>
				<?php _e( 'Style groups manager. Each group generates its CSS and font dependencies.', 'nwscds' ); ?>
			</p>
			<p>
				<select id="newscodes-groups">
				<?php
					if ( empty( $styles ) ) {
				?>
					<option value=""><?php _e( 'No groups found', 'nwscds' ); ?></option>
				<?php
					}
					else {
						foreach( $styles as $slug => $style ) {
						?>
							<option value="<?php echo $slug; ?>"<?php echo isset( $style['type'] ) ? ' data-type="default"' : ''; ?>><?php echo $style['name']; ?></option>
						<?php
							if ( $style['styles'] && is_array( $style['styles'] ) ) {
								foreach( $style['styles'] as $sub_slug => $sub_settings ) {
									$got_styles[$sub_slug] = array( 'name' => $sub_settings['name'], 'group' => $slug );
								}
							}
						}
					}
				?>
				</select>
			</p>
			<p>
				<span id="nc-create-group" class="button-primary"><?php _e( 'Create New Group', 'nwscds' ); ?></span>
				<span id="nc-delete-group" class="button"><?php _e( 'Delete Group', 'nwscds' ); ?></span>
			</p>
		</div>
		<div class="nc-admin-half">
			<h4><?php _e( 'Styles' , 'nwscds' ); ?></h4>
			<p>
				<?php _e( 'Styles manager. Edit styles within a selected style group.', 'nwscds' ); ?>
			</p>
			<p>
				<select id="newscodes-styles">
					<option value=""><?php _e( 'No styles found', 'nwscds' ); ?></option>
				<?php
					if ( !empty( $got_styles ) ) {
						foreach( $got_styles as $slug => $style ) {
					?>
						<option value="<?php echo $slug; ?>" data-group="<?php echo $style['group']; ?>"><?php echo $style['name']; ?></option>
					<?php
						}
					}
				?>
				</select>
			</p>
			<p>
				<span id="nc-create-style" class="button-primary"><?php _e( 'Create New Style', 'nwscds' ); ?></span>
				<span id="nc-edit-style" class="button"><?php _e( 'Edit Selected', 'nwscds' ); ?></span>
				<span id="nc-delete-style" class="button"><?php _e( 'Delete Selected', 'nwscds' ); ?></span>
			</p>
		</div>
	<?php
	}

	public static function registration_metabox() {
	?>
		<p class="nc-not-registered nc-box green-box">
			<?php _e( 'Free Version!', 'nwscds' ); ?>
		</p>
		<p class="nc-not-registered">
			<?php echo 'Newscodes free version! This is a limited free version! For full features, such as <strong>20+ post list elements</strong> (including tickers, grids, lists, marquees, columned and tabbed posts) with <strong>style editor</strong>, to <strong>create and edit your own post styles</strong>, please purchase the full version.'; ?>
		</p>
		<p class="nc-free-version">
			<a href="http://codecanyon.net/item/newscodes-news-magazine-and-blog-elements-for-wordpress/14714969?ref=dzeriho" id="nc-free-version" class="button-primary" target="_blank"><?php _e( 'Get Full Version!', 'nwscds' ); ?></a>
		</p>
	<?php
	}

	public static function side_metabox() {
		$message = rand(0, 3);
		switch( $message ) {

			case 0 :

			?>
				<p class="nc-box blue-box">
					<?php _e( 'To create a new style click the <strong>Create New Style</strong> button!', 'newscds' ); ?>
				</p>
			<?php

			break;
			case 1 :

			?>
				<p class="nc-box blue-box">
					<?php _e( 'Be tidy! Use fewer CSS styles and fonts on the frontend for best performance. Use the <strong>Frontend Optimizations</strong>.', 'nwscds' ); ?>
				</p>
			<?php

			break;
			case 2 :

			?>
				<p class="nc-box blue-box">
					<?php _e( 'Thanks for purchasing <strong>Newscodes</strong>! Register your copy now and get automatic updates.', 'newscds' ); ?>
				</p>
			<?php

			break;
			case 3 :

			?>
				<p class="nc-box blue-box">
					<?php _e( 'Groups can have up to <strong>10</strong> styles!', 'newscds' ); ?>
				</p>
			<?php

			break;
			default :
			break;

		}

		?>
		<p>
			<a href="https://mihajlovicnenad.com/support/" target="_blank"><?php _e( 'Help and Support', 'nwscds' ); ?></a><br/>
			<a href="http://mihajlovicnenad.com/newscodes/documentation/" target="_blank"><?php _e( 'Documentation', 'nwscds' ); ?></a><br/>
			<?php _e( 'Plugin author', 'nwscds' ); ?> <a href="https://mihajlovicnenad.com/" target="_blank">Mihajlovicnenad.com!</a>
		</p>
		<p class="nc-box-author">
			<small>Newscodes v<?php echo NC()->version(); ?></small>
		</p>
		<?php
	}

	public static function get_empty_style() {
	?>
		<h4><?php _e( 'Style Editor', 'newscds' ); ?></h4>
		<div id="newscodes-style-editor" class="nc-empty-editor">
			<p>
				<?php _e( 'Please select your style to edit or create a new one using the Styles Manager!', 'nwscds' ); ?>
			</p>
		</div>
	<?php
	}

	public static function get_generator_settings( $settings = array() ) {

		$controls = self::$generator;
	?>
		<div class="nc-generator-editor">
			<strong><?php _e( 'Shortcode Parameters', 'nwscds' ); ?></strong>
			<p>
				<?php _e( 'Setup your element by setting these parameters', 'nwscds' ); ?>
			</p>
	<?php
			foreach( $controls as $control ) {
				$control['field_value'] = isset( $settings[$control['field_name']] ) ? $settings[$control['field_name']] : $control['field_value'];
				self::get_control( $control );
			}
		?>
		</div>
	<?php
	}

	public static function get_style_settings( $type = '', $style = '', $settings = '' ) {

		if ( !in_array( $type, array( 'new', 'edit' ) ) ) {
			return;
		}

		$controls = self::$defaults;
	?>
		<div class="nc-style-desc">
			<strong><?php _e( 'Edit Style', 'nwscds' ); ?></strong>
			<p>
				<?php _e( 'Set style settings and options', 'nwscds' ); ?>
			</p>
			<p>
				<span id="nc-save-style" class="button-primary"><?php _e( 'Save', 'nwscds' ); ?></span>
				<span id="nc-save-as-style" class="button"><?php _e( 'Save As', 'nwscds' ); ?></span>
				<select id="nc-choose-group">
					<option value=""><?php _e( 'To Save As choose a group', 'nwscds' ); ?></option>
				<?php
					foreach ( self::$styles as $k => $v ) {
						echo '<option value="' . $k . '">' . $v['name'] . '</option>';
					}
				?>
				</select>
				<span id="nc-discard-style" class="button"><?php _e( 'Discard', 'nwscds' ); ?></span>
			</p>
			<strong>
				<?php _e( 'Preview Settings', 'nwscds' ); ?>
			</strong>
			<p>
				<select id="nc-preview-type">
					<option value="news-list-compact-featured"><?php echo NC_Setup::$newscodes['types']['news-list-compact-featured']; ?></option>
				<?php
					foreach ( NC_Setup::$newscodes['types'] as $k => $v ) {
						if ( in_array( $k, array( 'news-poster', 'news-grid', 'news-list', 'news-list-featured', 'news-list-compact', 'news-list-tiny', 'news-list-tiny-featured', 'news-grid-author', 'news-list-author-featured', 'news-list-author-compact-featured', 'news-list-author-tiny-featured' ) ) ) {
							echo '<option value="' . $k . '">' . $v . '</option>';
						}
					}
				?>
				</select>
				<span id="nc-preview-style" class="button"><?php _e( 'Preview', 'nwscds' ); ?></span>
				<input id="nc-preview-background" type="checkbox" /> <?php _e( 'Dark Preview', 'nwscds' );?>
			</p>
		</div>
		<div class="nc-style-editor">
		<?php
			foreach( $controls as $control ) {
				$control['field_value'] = isset( $settings[$control['field_name']] ) ? $settings[$control['field_name']] : '';
				self::get_control( $control );
			}
		?>
		</div>
	<?php

	}

	public static function get_control( $control = array() ) {
		if ( empty( $control ) ) {
			return;
		}
		//$control['field_value'] = isset( $settings[$control['field_name']] ) ? $settings[$control['field_name']] : '';

		call_user_func( 'NC_Admin_Controls::' . $control['field_type'], $control );
	}

	public static function ajax_factory() {

		if ( !isset( $_POST['nc_settings'] ) && !isset( $_POST['nc_settings']['type'] ) ) {
			die();
			exit;
		}

		$type = $_POST['nc_settings']['type'];

		if ( $type == 'new' ) {
			$style = '';
			$settings = array();

			ob_start();

			self::get_style_settings( $type, $style, $settings );

			$settings = ob_get_clean();

			$html = '<div id="newscodes-edit">';

				$html .= '<span class="newscodes-edit-close"></span>';

				$html .= '<div class="newscodes-preview-inner">';

					$html .= '<div id="newscodes-preview"></div>';

				$html .= '</div>';

				$html .= '<div class="newscodes-edit-inner">';

					$html .= '<div id="newscodes-style-editor">' . $settings . '</div>';

				$html .= '</div>';

				$html .= '<div class="newscodes-controls-inner">';

					$html .= '<span id="nc-save-style-side" class="button-primary">' . __( 'Save', 'nwscds' ) . '</span>';
					$html .= '<span id="nc-save-as-style-side" class="button">' . __( 'Save As', 'nwscds' ) . '</span>';
					$html .= '<span id="nc-discard-style-side" class="button">' . __( 'Discard', 'nwscds' ) . '</span>';
					$html .= '<span id="nc-preview-style-side" class="button">' . __( 'Preview', 'nwscds' ) . '</span>';

				$html .= '</div>';

			$html .= '</div>';

			die( $html );
			exit;
		}
		else if ( $type == 'edit' ) {
			$style = $_POST['nc_settings']['style'];
			$group = $_POST['nc_settings']['group'];

			$styles = apply_filters( 'nc_supported_styles', self::$styles );

			$settings = isset( $styles[$group]['styles'][$style] ) ? $styles[$group]['styles'][$style] : array();

			ob_start();

			self::get_style_settings( $type, $style, $settings );

			$settings = ob_get_clean();

			$html = '<div id="newscodes-edit">';

				$html .= '<span class="newscodes-edit-close"></span>';

				$html .= '<div class="newscodes-preview-inner">';

					$html .= '<div id="newscodes-preview"></div>';

				$html .= '</div>';

				$html .= '<div class="newscodes-edit-inner">';

					$html .= '<div id="newscodes-style-editor">' . $settings . '</div>';

				$html .= '</div>';

				$html .= '<div class="newscodes-controls-inner">';

					$html .= '<span id="nc-save-style-side" class="button-primary">' . __( 'Save', 'nwscds' ) . '</span>';
					$html .= '<span id="nc-save-as-style-side" class="button">' . __( 'Save As', 'nwscds' ) . '</span>';
					$html .= '<span id="nc-discard-style-side" class="button">' . __( 'Discard', 'nwscds' ) . '</span>';
					$html .= '<span id="nc-preview-style-side" class="button">' . __( 'Preview', 'nwscds' ) . '</span>';

				$html .= '</div>';

			$html .= '</div>';

			die( $html );
			exit;
		}
/*		else if ( $type == 'save' ) {
			$style = sanitize_title( $settings = $_POST['nc_settings']['name'] );
			$group = $_POST['nc_settings']['group'];
			$settings = $_POST['nc_settings']['style'];

			if ( isset( self::$styles[$group]['styles'][$style] ) ) {
				unset( self::$styles[$group]['styles'][$style] );
				self::$styles[$group]['styles'][$style] = $settings;
			}
			else {
				self::$styles[$group]['styles'][$style] = $settings;
			}

			self::$settings['styles'] = self::$styles;

			$new_styles = update_option( 'newscodes_settings', self::$settings );

			if ( $new_styles === true ) {
				self::compile( $group );
			}

			die( json_encode( array(
				'slug' => $style,
				'name' => $settings['name'],
				'group' => $group,
				'msg' => __( 'Saved!', 'nwscds' )
			), 64 ) );
			exit;

		}
		else if ( $type == 'generator_save' ) {
			$shortcode = sanitize_title( $name = $_POST['nc_settings']['name'] );
			$parameters = $_POST['nc_settings']['parameters'];
			$parameters['name'] = $name;

			if ( isset( self::$shortcodes[$shortcode] ) ) {
				unset( self::$shortcodes[$shortcode] );
				self::$shortcodes[$shortcode] = $parameters;
			}
			else {
				self::$shortcodes[$shortcode] = $parameters;
			}

			$new_styles = update_option( 'newscodes_shortcodes', self::$shortcodes );

			die( json_encode( array(
				'slug' => $shortcode,
				'name' => $name,
				'msg' => __( 'Saved!', 'nwscds' )
			), 64 ) );
			exit;

		}
		else if ( $type == 'generator_delete' ) {
			$shortcode = $_POST['nc_settings']['slug'];

			if ( isset( self::$shortcodes[$shortcode] ) ) {
				unset( self::$shortcodes[$shortcode] );
				update_option( 'newscodes_shortcodes', self::$shortcodes );
			}

			die( json_encode( array(
				'msg' => __( 'Deleted!', 'nwscds' )
			), 64 ) );
			exit;

		}
		else if ( $type == 'delete' ) {
			$style = $_POST['nc_settings']['slug'];
			$group = $_POST['nc_settings']['group'];

			if ( isset( self::$settings['styles'][$group]['styles'][$style] ) ) {
				unset( self::$settings['styles'][$group]['styles'][$style] );
				update_option( 'newscodes_settings', self::$settings );
			}

			die( json_encode( array(
				'msg' => __( 'Deleted!', 'nwscds' )
			), 64 ) );
			exit;

		}
		else if ( $type == 'group' ) {

			$group = sanitize_title( $settings = $_POST['nc_settings']['name'] );
			$name = esc_sql( $_POST['nc_settings']['name'] );

			if ( !isset( self::$settings['styles'][$group] ) ) {
				self::$settings['styles'][$group] = array(
					'slug' => $group,
					'name' => $name,
					'styles' => array()
				);
				update_option( 'newscodes_settings', self::$settings );
			}

			die( json_encode( array(
				'group' => $group,
				'name' => $name,
				'msg' => __( 'Created!', 'nwscds' )
			), 64 ) );
			exit;

		}
		else if ( $type == 'delete_group' ) {
			$group = $_POST['nc_settings']['slug'];

			if ( isset( self::$settings['styles'][$group] ) ) {
				unset( self::$settings['styles'][$group] );
				update_option( 'newscodes_settings', self::$settings );
			}

			if ( false === ( $cached = get_option( '_nc_less_styles' ) ) ) {
			}
			else {

				if ( isset( $cached[$group] ) ) {

					$upload = wp_upload_dir();

					if ( $cached[$group]['id'] !== '' ) {
						$delete = untrailingslashit( $upload['basedir'] ) . '/nc-' . $group . '-' . $cached[$group]['id'] . '.css';
						if ( is_writable( $delete ) ) {
							unlink( $delete );
						}
					}

					if ( $cached[$group]['last_known'] !== '' ) {
						$delete = untrailingslashit( $upload['basedir'] ) . '/nc-' . $group . '-' . $cached[$group]['last_known'] . '.css';
						if ( is_writable( $delete ) ) {
							unlink( $delete );
						}
					}

					unset( $cached[$group] );

					update_option( '_nc_less_styles', $cached );

				}

			}

			die( json_encode( array(
				'msg' => __( 'Deleted!', 'nwscds' )
			), 64 ) );
			exit;

		}*/
		else if ( $type == 'generator' ) {

			ob_start();

			self::get_generator_settings( array() );

			$settings = ob_get_clean();

			$html = '<div id="newscodes-shortcode-generator">';

				$html .= '<span class="newscodes-edit-close"></span>';

				$html .= '<div class="newscodes-preview-inner">';

					$html .= '<div id="newscodes-inner-controls">';

						$html .= '<p>';
							$html .= '<strong>' . __( 'Shortcode', 'nwscds' ) . '</strong> - ' . __( 'Copy and paste this shortcode to use in pages and content areas', 'nwscds' );
						$html .= '</p>';
						$html .= '<p>';
							$html .= '<code id="nc-generated-shortcode">[nc_factory]</code>';
						$html .= '</p>';
						$html .= '<p>';
							$html .= '<span id="nc-generator-preview" class="button-primary">' . __( 'Preview', 'nwscds' ) . '</span>';
							$html .= '<span><input id="nc-generator-background" type="checkbox" />' . __( 'Dark Preview', 'nwscds' ) . '</span>';
							$html .= '<span id="nc-generator-discard" class="button">' . __( 'Discard', 'nwscds' ) . '</span>';
							$html .= '<span id="nc-generator-save" class="button">' . __( 'Save', 'nwscds' ) . '</span>';
							$html .= '<span id="nc-generator-save-as" class="button">' . __( 'Save As', 'nwscds' ) . '</span>';
						$html .= '</p>';

					$html .= '</div>';

					$html .= '<div id="newscodes-generator-preview"></div>';

				$html .= '</div>';

				$html .= '<div class="newscodes-edit-inner">';

					$html .= '<div id="newscodes-generator-parameters">' . $settings . '</div>';

				$html .= '</div>';

			$html .= '</div>';

			die( $html );
			exit;
		}
		else if ( $type == 'generator_edit' ) {

			$shortcode = $_POST['nc_settings']['shortcode'];
			$atts = self::$shortcodes[$shortcode];

			ob_start();

			self::get_generator_settings( $atts );

			$settings = ob_get_clean();

			$html = '<div id="newscodes-shortcode-generator">';

				$html .= '<span class="newscodes-edit-close"></span>';

				$html .= '<div class="newscodes-preview-inner">';

					$html .= '<div id="newscodes-inner-controls">';

						$html .= '<p>';
							$html .= '<strong>' . __( 'Full Shortcode', 'nwscds' ) . '</strong> - ' . __( 'Copy and paste this shortcode to use in pages and content areas', 'nwscds' );
						$html .= '</p>';
						$html .= '<p>';
							$html .= '<code id="nc-generated-shortcode">[nc_factory]</code>';
						$html .= '</p>';
						$html .= '<p>';
							$html .= '<span id="nc-generator-preview" class="button-primary">' . __( 'Preview', 'nwscds' ) . '</span>';
							$html .= '<span><input id="nc-generator-background" type="checkbox" />' . __( 'Dark Preview', 'nwscds' ) . '</span>';
							$html .= '<span id="nc-generator-discard" class="button">' . __( 'Discard', 'nwscds' ) . '</span>';
							$html .= '<span id="nc-generator-save" class="button">' . __( 'Save', 'nwscds' ) . '</span>';
							$html .= '<span id="nc-generator-save-as" class="button">' . __( 'Save As', 'nwscds' ) . '</span>';
							$html .= '<span><strong>' . __( 'Short Version', 'nwscds' ) . '</strong><code id="nc-generated-short" data-shortcode="' . $shortcode . '">[newscodes id="' . $shortcode . '"]</code></span>';
						$html .= '</p>';

					$html .= '</div>';

					$html .= '<div id="newscodes-generator-preview"></div>';

				$html .= '</div>';

				$html .= '<div class="newscodes-edit-inner">';

					$html .= '<div id="newscodes-generator-parameters">' . $settings . '</div>';

				$html .= '</div>';

			$html .= '</div>';

			die( $html );
			exit;
		}
		else if ( $type == 'generator_preview' ) {

			$atts = $_POST['nc_settings']['atts'];
			$style = $_POST['nc_settings']['style'];
			$group = $_POST['nc_settings']['group'];
			$atts['style'] = 'preview';

			$styles = apply_filters( 'nc_supported_styles', self::$styles );

			$settings = isset( $styles[$group]['styles'][$style] ) ? $styles[$group]['styles'][$style] : array();

			$css = self::compile_preview( $settings );

			$html = '';

			$src = NC()->plugin_url() . '/lib';

			$html .= '<link rel="stylesheet" href="' . $src . '/css/newscodes.css" type="text/css" media="all" />';

			if ( !empty( $settings ) ) {
				$html .= self::get_preview_font_includes( $settings );
			}

			$html .= '<style type="text/css">' . $css . '</style>';

			//$html .= '<div class="newscodes-multi">';

				//$html .= '<nav class="nc-multi-navigation nc-type-' . $atts['type'] . ' newscodes-style-preview">';

					//$html .= '<ul class="nc-multi-terms">';

						//$html .= '<li class="current">' . __( 'Active') . '</li>';

						//$html .= '<li>' . __( 'Inactive') . '</li>';

					//$html .= '</ul>';

				//$html .= '</nav>';

				//$html .= '<div class="nc-multi-tabs">';

					$html .= NC_Shortcodes::factory( $atts );

				//$html .= '</div>';

			//$html .= '</div>';

			die( $html );
			exit;

		}
		else if ( $type == 'preview' ) {

			$settings = $_POST['nc_settings']['style'];

			$css = self::compile_preview( $settings );

			$html = '';

			$src = NC()->plugin_url() . '/lib';

			$html .= '<link rel="stylesheet" href="' . $src . '/css/newscodes.css" type="text/css" media="all" />';

			if ( !empty( $settings ) ) {
				$html .= self::get_preview_font_includes( $settings );
			}

			$html .= '<style type="text/css">' . $css . '</style>';

			$atts = array(
				'type'           => isset( $_POST['nc_settings']['preview'] ) ? $_POST['nc_settings']['preview'] : 'news-list-compact-featured',
				'posts_per_page' => 4,
				'style'          => 'preview',
				'image_ratio'    => isset( $_POST['nc_settings']['preview'] ) && $_POST['nc_settings']['preview'] == 'news-poster' ? '1-1' :'16-9',
				'columns'        => isset( $_POST['nc_settings']['preview'] ) && $_POST['nc_settings']['preview'] == 'news-grid' ? '2' :'1',
				'which_taxonomy' => 'category',
				'excerpt_length' => 25
			);

			$html .= '<div class="newscodes-multi">';

				$html .= '<nav class="nc-multi-navigation nc-type-' . $atts['type'] . ' newscodes-style-preview">';

					$html .= '<ul class="nc-multi-terms">';

						$html .= '<li class="current">' . __( 'Active') . '</li>';

						$html .= '<li>' . __( 'Inactive') . '</li>';

					$html .= '</ul>';

				$html .= '</nav>';

				$html .= '<div class="nc-multi-tabs">';

					$html .= NC_Shortcodes::factory( $atts );

				$html .= '</div>';

			$html .= '</div>';

			die( $html );
			exit;

		}
		/*else if ( $type == 'purchase_code' ) {

			$purchase_code = isset( $_POST['nc_settings']['purchase_code'] ) ? $_POST['nc_settings']['purchase_code'] : null;

			if ( !$purchase_code ) {
				die();
				exit;
			}
			else {

				$url = "http://mihajlovicnenad.com/envato/get_confirmation.php?k={$purchase_code}&p=14714969";

				$ch = curl_init();

				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

				curl_setopt( $ch, CURLOPT_URL, $url );

				$result = curl_exec($ch);

				curl_close($ch);

				$result = json_decode( $result, true );

				if ( !isset( $result['error'] ) ) {
					update_option( '_newscodes_update_code', $purchase_code );

					die( json_encode( array(
						'msg' => __( 'Registered!', 'nwscds' ),
						'html' => '<p class="nc-box green-box">Registered Version</p><p class="nc-registered"><span id="nc-purcashe-code-remove" class="button-primary">Remove Registration Code</span></p>'
					), 64 ) );
					exit;

				}

			}

			die( json_encode( array(
				'error' => __( 'Not Valid!', 'nwscds' )
			), 64 ) );
			exit;

		}
		else if ( $type == 'purchase_code_remove' ) {

			$settings = isset( $_POST['nc_settings'] ) ? $_POST['nc_settings'] : null;

			if ( !$settings ) {
				die();
				exit;
			}
			else {
				delete_option( '_newscodes_update_code' );
			}

			die( json_encode( array(
				'msg' => __( 'Deleted!', 'nwscds' ),
				'html' => '<p class="nc-not-registered nc-box red-box">Unregistered Version</p><p class="nc-not-registered">Register Newscodes to get automatic updates! Enter your <a href="http://codecanyon.net" target="_blank">codecanyon.net</a> purchase code.</p><p class="nc-not-registered"><input type="text" id="newscodes-purchase-code" name="newscodes-purchase-code" class="newscodes-purchase-code" value=""></p><p class="nc-not-registered"><span id="nc-purcashe-code" class="button-primary">Confirm Code</span></p>'
			), 64 ) );
			exit;

		}*/
		else if ( $type == 'update_optimizations' ) {

			$css = isset( $_POST['nc_settings']['css'] ) ? $_POST['nc_settings']['css'] : array();

			update_option( '_nc_less_styles_exclude', $css);

			die( json_encode( array(
				'msg' => __( 'Optimized!', 'nwscds' ),
				'css' => $css,
				'font' => $font
			), 64 ) );
			exit;

		}
		else {
			die();
			exit;
		}

		die();
		exit;

	}

	public static function get_preview_font_includes( $settings ) {

		$fonts_array = self::font_families( 'nc-settings' );
		$fonts = array();
		$html = '';

		foreach ( $settings as $k => $v ) {
			if ( isset( $v['font-family'] ) && isset( $fonts_array[$v['font-family']] ) ) {
				$fonts[$v['font-family']]['name'] = $fonts_array[$v['font-family']];
				$fonts[$v['font-family']]['slug'] = substr( $v['font-family'], 4 );
				$fonts[$v['font-family']]['type'] = substr( $v['font-family'], 0, 3 );
				
			}
		}

		if ( !empty( $fonts ) ) {

			$src = NC()->plugin_url() . '/lib';
			$protocol = is_ssl() ? 'https' : 'http';

			foreach( $fonts as $k => $v ) {
				if ( !isset( $v['type'] ) ) {
					continue;
				}

				if ( $v['type'] == 'inc' ) {
					$html .= '<link rel="stylesheet" href="' . $src . '/fonts/' . $v['slug'] . '/style.css" type="text/css" media="all" />';
				}
				else if ( $v['type'] == 'ggl' ) {

					$slug = str_replace( ' ', '+', ucwords( str_replace( '-', ' ', $v['slug'] ) ) );

					$html .= '<link rel="stylesheet" href="' . $protocol . '://fonts.googleapis.com/css?family=' . $slug . '%3A100%2C200%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C700%2C700italic%2C800&amp;subset=all" type="text/css" media="all" />';

				}
			}
		}

		return $html;

	}

	public static function compile_preview( $settings ) {

		if ( empty( $settings ) ) {
			return;
		}

		self::$less_helper['preview'] = $settings;

		require 'less/lessc.inc.php';

		$src = NC()->plugin_url() . '/includes/less/nc.less';

		$src_scheme = parse_url( $src, PHP_URL_SCHEME );

		$wp_content_url_scheme = parse_url( WP_CONTENT_URL, PHP_URL_SCHEME );

		if ( $src_scheme != $wp_content_url_scheme ) {

			$src = set_url_scheme( $src, $wp_content_url_scheme );

		}

		$file = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $src );

		$less = new lessc;

		$less->setFormatter( 'compressed' );
		$less->setPreserveComments( 'false' );
		$less->setVariables( apply_filters( 'nc_admin_less_preview', array() ) );

		$compile = $less->cachedCompile( $file );

		return $compile['compiled'];

	}

	public static function compile( $group = '') {

		self::$less_helper['group'] = $group;

		require 'less/lessc.inc.php';

		$src = NC()->plugin_url() . '/includes/less/nc.less';

		$src_scheme = parse_url( $src, PHP_URL_SCHEME );

		$wp_content_url_scheme = parse_url( WP_CONTENT_URL, PHP_URL_SCHEME );

		if ( $src_scheme != $wp_content_url_scheme ) {

			$src = set_url_scheme( $src, $wp_content_url_scheme );

		}

		$file = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $src );

		$less = new lessc;

		$less->setFormatter( 'compressed' );
		$less->setPreserveComments( 'false' );
		$less->setVariables( apply_filters( 'nc_admin_less_variables_update', array() ) );

		$compile = $less->cachedCompile( $file );

		$upload = wp_upload_dir();

		$id = uniqid();

		$upload_dir = untrailingslashit( $upload['basedir'] ) . '/nc-' . $group . '-' . $id . '.css';
		$upload_url = untrailingslashit( $upload['baseurl'] ) . '/nc-' . $group . '-' . $id . '.css';

		if ( false === ( $cached = get_option( '_nc_less_styles' ) ) ) {
			$cached_transient = '';
		}
		else {
			if ( isset( $cached[$group] ) ) {
				$cached_transient = $cached[$group]['id'];
				if ( $cached[$group]['last_known'] !== '' ) {
					$delete = untrailingslashit( $upload['basedir'] ) . '/nc-' . $group . '-' . $cached[$group]['last_known'] . '.css';
					if ( is_writable( $delete ) ) {
						unlink( $delete );
					}
				}
			}
			else {
				$cached_transient = '';
			}

		}

		$transient = array(
			'last_known' => $cached_transient,
			'id' => $id,
			'url' => $upload_url
		);

		if ( isset( $cached[$group] ) ) {
			unset( $cached[$group] );
		}

		$cached[$group] = $transient;

		update_option( '_nc_less_styles', $cached );

		file_put_contents( $upload_dir, $compile['compiled'] );

		return true;

	}

	public static function less_variables_preview() {

		$vars = array();
		$styles['preview'] = self::$less_helper['preview'];

		$vars['url'] = '~"' . NC()->plugin_url() . '"';

		self::$less_helper['fonts'] = self::font_families( 'nc-settings' );

		if ( is_array( $styles ) ) {

			foreach( $styles as $k => $v ) {
				self::$less_helper['styles'][] = $k;
				$fonts = self::less_get_fonts( $k, $v['nc_heading'], 'nc_heading' );
				foreach( $fonts as $key => $value ) {
					$vars[$key] = $value;
				}
				$fonts = self::less_get_fonts( $k, $v['nc_meta'], 'nc_meta' );
				foreach( $fonts as $key => $value ) {
					$vars[$key] = $value;
				}
				$fonts = self::less_get_fonts( $k, $v['nc_excerpt'], 'nc_excerpt' );
				foreach( $fonts as $key => $value ) {
					$vars[$key] = $value;
				}
				$fonts = self::less_get_fonts( $k, $v['nc_navigation'], 'nc_navigation' );
				foreach( $fonts as $key => $value ) {
					$vars[$key] = $value;
				}
				$fonts = self::less_get_fonts( $k, $v['nc_tabs'], 'nc_tabs' );
				foreach( $fonts as $key => $value ) {
					$vars[$key] = $value;
				}
				$vars[$k . '_nc_heading_hover'] = $v['nc_heading_hover'] !== '' ? $v['nc_heading_hover'] : 'unset';
				$vars[$k . '_nc_meta_background'] = $v['nc_meta_background'] !== '' ? $v['nc_meta_background'] : 'unset';
				$vars[$k . '_nc_taxonomy_color'] = $v['nc_taxonomy_color'] !== '' ? $v['nc_taxonomy_color'] : 'unset';
				$vars[$k . '_nc_taxonomy_background'] = $v['nc_taxonomy_background'] !== '' ? $v['nc_taxonomy_background'] : 'unset';
				$vars[$k . '_nc_navigation_hover'] = $v['nc_navigation_hover'] !== '' ? $v['nc_navigation_hover'] : 'unset';
				$vars[$k . '_nc_navigation_style'] = $v['nc_navigation_style'] !== '' ? $v['nc_navigation_style'] : 'border';
				$vars[$k . '_nc_tabs_hover'] = $v['nc_tabs_hover'] !== '' ? $v['nc_tabs_hover'] : 'unset';
				$vars[$k . '_nc_tabs_style'] = $v['nc_tabs_style'] !== '' ? $v['nc_tabs_style'] : 'border';
				$vars[$k . '_nc_format_standard'] = $v['nc_format_standard'] !== '' ? $v['nc_format_standard'] : 'unset';
				$vars[$k . '_nc_format_aside'] = $v['nc_format_aside'] !== '' ? $v['nc_format_aside'] : 'unset';
				$vars[$k . '_nc_format_chat'] = $v['nc_format_chat'] !== '' ? $v['nc_format_chat'] : 'unset';
				$vars[$k . '_nc_format_gallery'] = $v['nc_format_gallery'] !== '' ? $v['nc_format_gallery'] : 'unset';
				$vars[$k . '_nc_format_link'] = $v['nc_format_link'] !== '' ? $v['nc_format_link'] : 'unset';
				$vars[$k . '_nc_format_image'] = $v['nc_format_image'] !== '' ? $v['nc_format_image'] : 'unset';
				$vars[$k . '_nc_format_quote'] = $v['nc_format_quote'] !== '' ? $v['nc_format_quote'] : 'unset';
				$vars[$k . '_nc_format_status'] = $v['nc_format_status'] !== '' ? $v['nc_format_status'] : 'unset';
				$vars[$k . '_nc_format_video'] = $v['nc_format_video'] !== '' ? $v['nc_format_video'] : 'unset';
				$vars[$k . '_nc_format_audio'] = $v['nc_format_audio'] !== '' ? $v['nc_format_audio'] : 'unset';
				$vars[$k . '_nc_tabs_padding'] = $v['nc_tabs_padding'] !== '' ? $v['nc_tabs_padding'] : 'default';
				$vars[$k . '_nc_image_padding'] = $v['nc_image_padding'] !== '' ? $v['nc_image_padding'] : 'default';
				$vars[$k . '_nc_meta_padding'] = $v['nc_meta_padding'] !== '' ? $v['nc_meta_padding'] : 'default';
				$vars[$k . '_nc_heading_padding'] = $v['nc_heading_padding'] !== '' ? $v['nc_heading_padding'] : 'default';
				$vars[$k . '_nc_excerpt_padding'] = $v['nc_excerpt_padding'] !== '' ? $v['nc_excerpt_padding'] : 'default';
				$vars[$k . '_nc_pagination_padding'] = $v['nc_pagination_padding'] !== '' ? $v['nc_pagination_padding'] : 'default';
			}

			if ( !empty( self::$less_helper['styles'] ) && is_array( self::$less_helper['styles'] ) ) {
				$vars['nc_styles'] = implode( self::$less_helper['styles'], ' ' );
				$vars['nc_count'] = count( self::$less_helper['styles'] );
			}
			else {
				$vars['nc_styles'] = '';
				$vars['nc_count'] = 0;
			}

		}

		return $vars;

	}

	public static function less_variables() {

		if ( !self::$less_helper['group'] ) {
			return false;
		}

		$vars = array();
		$styles = self::$styles[self::$less_helper['group']]['styles'];

		$vars['url'] = '~"' . NC()->plugin_url() . '"';

		self::$less_helper['fonts'] = self::font_families( '' );

		if ( is_array( $styles ) ) {

			foreach( $styles as $k => $v ) {
				self::$less_helper['styles'][] = $k;
				$fonts = self::less_get_fonts( $k, $v['nc_heading'], 'nc_heading' );
				foreach( $fonts as $key => $value ) {
					$vars[$key] = $value;
				}
				$fonts = self::less_get_fonts( $k, $v['nc_meta'], 'nc_meta' );
				foreach( $fonts as $key => $value ) {
					$vars[$key] = $value;
				}
				$fonts = self::less_get_fonts( $k, $v['nc_excerpt'], 'nc_excerpt' );
				foreach( $fonts as $key => $value ) {
					$vars[$key] = $value;
				}
				$fonts = self::less_get_fonts( $k, $v['nc_navigation'], 'nc_navigation' );
				foreach( $fonts as $key => $value ) {
					$vars[$key] = $value;
				}
				$fonts = self::less_get_fonts( $k, $v['nc_tabs'], 'nc_tabs' );
				foreach( $fonts as $key => $value ) {
					$vars[$key] = $value;
				}
				$vars[$k . '_nc_heading_hover'] = $v['nc_heading_hover'] !== '' ? $v['nc_heading_hover'] : 'unset';
				$vars[$k . '_nc_meta_background'] = $v['nc_meta_background'] !== '' ? $v['nc_meta_background'] : 'unset';
				$vars[$k . '_nc_taxonomy_color'] = $v['nc_taxonomy_color'] !== '' ? $v['nc_taxonomy_color'] : 'unset';
				$vars[$k . '_nc_taxonomy_background'] = $v['nc_taxonomy_background'] !== '' ? $v['nc_taxonomy_background'] : 'unset';
				$vars[$k . '_nc_navigation_hover'] = $v['nc_navigation_hover'] !== '' ? $v['nc_navigation_hover'] : 'unset';
				$vars[$k . '_nc_navigation_style'] = $v['nc_navigation_style'] !== '' ? $v['nc_navigation_style'] : 'border';
				$vars[$k . '_nc_tabs_hover'] = $v['nc_tabs_hover'] !== '' ? $v['nc_tabs_hover'] : 'unset';
				$vars[$k . '_nc_tabs_style'] = $v['nc_tabs_style'] !== '' ? $v['nc_tabs_style'] : 'border';
				$vars[$k . '_nc_format_standard'] = $v['nc_format_standard'] !== '' ? $v['nc_format_standard'] : 'unset';
				$vars[$k . '_nc_format_aside'] = $v['nc_format_aside'] !== '' ? $v['nc_format_aside'] : 'unset';
				$vars[$k . '_nc_format_chat'] = $v['nc_format_chat'] !== '' ? $v['nc_format_chat'] : 'unset';
				$vars[$k . '_nc_format_gallery'] = $v['nc_format_gallery'] !== '' ? $v['nc_format_gallery'] : 'unset';
				$vars[$k . '_nc_format_link'] = $v['nc_format_link'] !== '' ? $v['nc_format_link'] : 'unset';
				$vars[$k . '_nc_format_image'] = $v['nc_format_image'] !== '' ? $v['nc_format_image'] : 'unset';
				$vars[$k . '_nc_format_quote'] = $v['nc_format_quote'] !== '' ? $v['nc_format_quote'] : 'unset';
				$vars[$k . '_nc_format_status'] = $v['nc_format_status'] !== '' ? $v['nc_format_status'] : 'unset';
				$vars[$k . '_nc_format_video'] = $v['nc_format_video'] !== '' ? $v['nc_format_video'] : 'unset';
				$vars[$k . '_nc_format_audio'] = $v['nc_format_audio'] !== '' ? $v['nc_format_audio'] : 'unset';
				$vars[$k . '_nc_tabs_padding'] = $v['nc_tabs_padding'] !== '' ? $v['nc_tabs_padding'] : 'default';
				$vars[$k . '_nc_image_padding'] = $v['nc_image_padding'] !== '' ? $v['nc_image_padding'] : 'default';
				$vars[$k . '_nc_meta_padding'] = $v['nc_meta_padding'] !== '' ? $v['nc_meta_padding'] : 'default';
				$vars[$k . '_nc_heading_padding'] = $v['nc_heading_padding'] !== '' ? $v['nc_heading_padding'] : 'default';
				$vars[$k . '_nc_excerpt_padding'] = $v['nc_excerpt_padding'] !== '' ? $v['nc_excerpt_padding'] : 'default';
				$vars[$k . '_nc_pagination_padding'] = $v['nc_pagination_padding'] !== '' ? $v['nc_pagination_padding'] : 'default';
			}

			if ( !empty( self::$less_helper['styles'] ) && is_array( self::$less_helper['styles'] ) ) {
				$vars['nc_styles'] = implode( self::$less_helper['styles'], ' ' );
				$vars['nc_count'] = count( self::$less_helper['styles'] );
			}
			else {
				$vars['nc_styles'] = '';
				$vars['nc_count'] = 0;
			}

		}

		return $vars;

	}

	public static function less_get_fonts( $el, $font, $sufix ) {

		if ( isset( $font['font-family'] ) ) {
			$type = substr( $font['font-family'], 0, 3 );

			if ( in_array( $type, array( 'inc', 'ggl' ) ) ) {
				if ( !isset( self::$less_helper['included_fonts'][$font['font-family']] ) ) {
					self::$less_helper['included_fonts'][$font['font-family']]['slug'] = substr( $font['font-family'], 4 );
					self::$less_helper['included_fonts'][$font['font-family']]['name'] = self::$less_helper['fonts'][$font['font-family']];
				}
			}
		}

		$prefix = $el . '_' . $sufix;

		$vars[$prefix . '_font_family'] = isset( $font['font-family'] ) && $font['font-family'] !== '' ? self::$less_helper['fonts'][$font['font-family']] : 'unset';
		$vars[$prefix . '_font_color'] = isset( $font['font-color'] ) && $font['font-color'] !== '' ? $font['font-color'] : 'unset';
		$vars[$prefix . '_font_size'] = isset( $font['font-size'] ) && $font['font-size'] !== '' ? $font['font-size'] : 'unset';
		$vars[$prefix . '_font_style'] = isset( $font['font-style'] ) && $font['font-style'] !== '' ? $font['font-style'] : 'unset';
		$vars[$prefix . '_font_variant'] = isset( $font['font-variant'] ) && $font['font-variant'] !== '' ? $font['font-variant'] : 'unset';
		$vars[$prefix . '_font_weight'] = isset( $font['font-weight'] ) && $font['font-weight'] !== '' ? $font['font-weight'] : 'unset';
		$vars[$prefix . '_letter_spacing'] = isset( $font['letter-spacing'] ) && $font['letter-spacing'] !== '' ? $font['letter-spacing'] : 'unset';
		$vars[$prefix . '_line_height'] = isset( $font['line-height'] ) && $font['line-height'] !== '' ? $font['line-height'] : 'unset';
		$vars[$prefix . '_text_decoration'] = isset( $font['text-decoration'] ) && $font['text-decoration'] !== '' ? $font['text-decoration'] : 'unset';
		$vars[$prefix . '_text_transform'] = isset( $font['text-transform'] ) && $font['text-transform'] !== '' ? $font['text-transform'] : 'unset';
		$vars[$prefix . '_text_align'] = isset( $font['text-align'] ) && $font['text-align'] !== '' ? $font['text-align'] : 'unset';

		return $vars;

	}

	public static function font_families( $id = '' ) {

		$fonts = array(
			'inc-opensans' => '"Open Sans", sans-serif',
			'inc-raleway' => '"Raleway", sans-serif',
			'inc-lato' => '"Lato", sans-serif',
			'inc-ptsans' => '"PT Sans", sans-serif',
			'inc-ubuntu' => '"Ubuntu", sans-serif',
			'sys-arial' => 'Arial, Helvetica, sans-serif',
			'sys-black' => '"Arial Black", Gadget, sans-serif',
			'sys-georgia' => 'Georgia, serif',
			'sys-impact' => 'Impact, Charcoal, sans-serif',
			'sys-lucida' => '"Lucida Sans Unicode", "Lucida Grande", sans-serif',
			'sys-palatino' => '"Palatino Linotype", "Book Antiqua", Palatino, serif',
			'sys-tahoma' => 'Tahoma, Geneva, sans-serif',
			'sys-times' => '"Times New Roman", Times, serif',
			'sys-trebuchet' => '"Trebuchet MS", Helvetica, sans-serif',
			'sys-verdana' => 'Verdana, Geneva, sans-serif',
		);

		$google_fonts = array( 'ggl-abel' => '"Abel", sans-serif', 'ggl-abril-fatface' => '"Abril Fatface", cursive', 'ggl-aclonica' => '"Aclonica", sans-serif', 'ggl-actor' => '"Actor", sans-serif', 'ggl-adamina' => '"Adamina", serif', 'ggl-aguafina-script' => '"Aguafina Script", cursive', 'ggl-aladin' => '"Aladin", cursive', 'ggl-aldrich' => '"Aldrich", sans-serif', 'ggl-alice' => '"Alice", serif', 'ggl-alike-angular' => '"Alike Angular", serif', 'ggl-alike' => '"Alike", serif', 'ggl-allan' => '"Allan", cursive', 'ggl-allerta-stencil' => '"Allerta Stencil", sans-serif', 'ggl-allerta' => '"Allerta", sans-serif', 'ggl-amaranth' => '"Amaranth", sans-serif', 'ggl-amatic-sc' => '"Amatic SC", cursive', 'ggl-andada' => '"Andada", serif', 'ggl-andika' => '"Andika", sans-serif', 'ggl-annie-use-your-telescope' => '"Annie Use Your Telescope", cursive', 'ggl-anonymous-pro' => '"Anonymous Pro", sans-serif', 'ggl-antic' => '"Antic", sans-serif', 'ggl-anton' => '"Anton", sans-serif', 'ggl-arapey' => '"Arapey", serif', 'ggl-architects-daughter' => '"Architects Daughter", cursive', 'ggl-arimo' => '"Arimo", sans-serif', 'ggl-artifika' => '"Artifika", serif', 'ggl-arvo' => '"Arvo", serif', 'ggl-asset' => '"Asset", cursive', 'ggl-astloch' => '"Astloch", cursive', 'ggl-atomic-age' => '"Atomic Age", cursive', 'ggl-aubrey' => '"Aubrey", cursive', 'ggl-bangers' => '"Bangers", cursive', 'ggl-bentham' => '"Bentham", serif', 'ggl-bevan' => '"Bevan", serif', 'ggl-bigshot-one' => '"Bigshot One", cursive', 'ggl-bitter' => '"Bitter", serif', 'ggl-black-ops-one' => '"Black Ops One", cursive', 'ggl-bowlby-one-sc' => '"Bowlby One SC", sans-serif', 'ggl-bowlby-one' => '"Bowlby One", sans-serif', 'ggl-brawler' => '"Brawler", serif', 'ggl-bubblegum-sans' => '"Bubblegum Sans", cursive', 'ggl-buda' => '"Buda", sans-serif', 'ggl-butcherman-caps' => '"Butcherman Caps", cursive', 'ggl-cabin-condensed' => '"Cabin Condensed", sans-serif', 'ggl-cabin-sketch' => '"Cabin Sketch", cursive', 'ggl-cabin' => '"Cabin", sans-serif', 'ggl-cagliostro' => '"Cagliostro", sans-serif', 'ggl-calligraffitti' => '"Calligraffitti", cursive', 'ggl-candal' => '"Candal", sans-serif', 'ggl-cantarell' => '"Cantarell", sans-serif', 'ggl-cardo' => '"Cardo", serif', 'ggl-carme' => '"Carme", sans-serif', 'ggl-carter-one' => '"Carter One", sans-serif', 'ggl-caudex' => '"Caudex", serif', 'ggl-cedarville-cursive' => '"Cedarville Cursive", cursive', 'ggl-changa-one' => '"Changa One", cursive', 'ggl-cherry-cream-soda' => '"Cherry Cream Soda", cursive', 'ggl-chewy' => '"Chewy", cursive', 'ggl-chicle' => '"Chicle", cursive', 'ggl-chivo' => '"Chivo", sans-serif', 'ggl-coda-caption' => '"Coda Caption", sans-serif', 'ggl-coda' => '"Coda", cursive', 'ggl-comfortaa' => '"Comfortaa", cursive', 'ggl-coming-soon' => '"Coming Soon", cursive', 'ggl-contrail-one' => '"Contrail One", cursive', 'ggl-convergence' => '"Convergence", sans-serif', 'ggl-cookie' => '"Cookie", cursive', 'ggl-copse' => '"Copse", serif', 'ggl-corben' => '"Corben", cursive', 'ggl-cousine' => '"Cousine", sans-serif', 'ggl-coustard' => '"Coustard", serif', 'ggl-covered-by-your-grace' => '"Covered By Your Grace", cursive', 'ggl-crafty-girls' => '"Crafty Girls", cursive', 'ggl-creepster-caps' => '"Creepster Caps", cursive', 'ggl-crimson-text' => '"Crimson Text", serif', 'ggl-crushed' => '"Crushed", cursive', 'ggl-cuprum' => '"Cuprum", sans-serif', 'ggl-damion' => '"Damion", cursive', 'ggl-dancing-script' => '"Dancing Script", cursive', 'ggl-dawning-of-a-new-day' => '"Dawning of a New Day", cursive', 'ggl-days-one' => '"Days One", sans-serif', 'ggl-delius-swash-caps' => '"Delius Swash Caps", cursive', 'ggl-delius-unicase' => '"Delius Unicase", cursive', 'ggl-delius' => '"Delius", cursive', 'ggl-devonshire' => '"Devonshire", cursive', 'ggl-didact-gothic' => '"Didact Gothic", sans-serif', 'ggl-dorsa' => '"Dorsa", sans-serif', 'ggl-dr-sugiyama' => '"Dr Sugiyama", cursive', 'ggl-droid-sans-mono' => '"Droid Sans Mono", sans-serif', 'ggl-droid-sans' => '"Droid Sans", sans-serif', 'ggl-droid-serif' => '"Droid Serif", serif', 'ggl-eb-garamond' => '"EB Garamond", serif', 'ggl-eater-caps' => '"Eater Caps", cursive', 'ggl-expletus-sans' => '"Expletus Sans", cursive', 'ggl-fanwood-text' => '"Fanwood Text", serif', 'ggl-federant' => '"Federant", cursive', 'ggl-federo' => '"Federo", sans-serif', 'ggl-fjord-one' => '"Fjord One", serif', 'ggl-fondamento' => '"Fondamento", cursive', 'ggl-fontdiner-swanky' => '"Fontdiner Swanky", cursive', 'ggl-forum' => '"Forum", cursive', 'ggl-francois-one' => '"Francois One", sans-serif', 'ggl-gentium-basic' => '"Gentium Basic", serif', 'ggl-gentium-book-basic' => '"Gentium Book Basic", serif', 'ggl-geo' => '"Geo", sans-serif', 'ggl-geostar-fill' => '"Geostar Fill", cursive', 'ggl-geostar' => '"Geostar", cursive', 'ggl-give-you-glory' => '"Give You Glory", cursive', 'ggl-gloria-hallelujah' => '"Gloria Hallelujah", cursive', 'ggl-goblin-one' => '"Goblin One", cursive', 'ggl-gochi-hand' => '"Gochi Hand", cursive', 'ggl-goudy-bookletter-1911' => '"Goudy Bookletter 1911", serif', 'ggl-gravitas-one' => '"Gravitas One", cursive', 'ggl-gruppo' => '"Gruppo", sans-serif', 'ggl-hammersmith-one' => '"Hammersmith One", sans-serif', 'ggl-herr-von-muellerhoff' => '"Herr Von Muellerhoff", cursive', 'ggl-holtwood-one-sc' => '"Holtwood One SC", serif', 'ggl-homemade-apple' => '"Homemade Apple", cursive', 'ggl-iM-fell-dw-pica-sc' => '"IM Fell DW Pica SC", serif', 'ggl-iM-fell-dw-pica' => '"IM Fell DW Pica", serif', 'ggl-iM-fell-double-pica-sc' => '"IM Fell Double Pica SC", serif', 'ggl-iM-fell-double-pica' => '"IM Fell Double Pica", serif', 'ggl-iM-fell-english-sc' => '"IM Fell English SC", serif', 'ggl-iM-fell-english' => '"IM Fell English", serif', 'ggl-iM-fell-french-canon-sc' => '"IM Fell French Canon SC", serif', 'ggl-iM-fell-french-canon' => '"IM Fell French Canon", serif', 'ggl-iM-fell-great-primer-sc' => '"IM Fell Great Primer SC", serif', 'ggl-iM-fell-great-primer' => '"IM Fell Great Primer", serif', 'ggl-iceland' => '"Iceland", cursive', 'ggl-inconsolata' => '"Inconsolata", sans-serif', 'ggl-indie-flower' => '"Indie Flower", cursive', 'ggl-irish-grover' => '"Irish Grover", cursive', 'ggl-istok-web' => '"Istok Web", sans-serif', 'ggl-jockey-one' => '"Jockey One", sans-serif', 'ggl-josefin-sans' => '"Josefin Sans", sans-serif', 'ggl-josefin-slab' => '"Josefin Slab", serif', 'ggl-judson' => '"Judson", serif', 'ggl-julee' => '"Julee", cursive', 'ggl-jura' => '"Jura", sans-serif', 'ggl-just-another-hand' => '"Just Another Hand", cursive', 'ggl-just-me-again-down-here' => '"Just Me Again Down Here", cursive', 'ggl-kameron' => '"Kameron", serif', 'ggl-kelly-slab' => '"Kelly Slab", cursive', 'ggl-kenia' => '"Kenia", sans-serif', 'ggl-knewave' => '"Knewave", cursive', 'ggl-kranky' => '"Kranky", cursive', 'ggl-kreon' => '"Kreon", serif', 'ggl-kristi' => '"Kristi", cursive', 'ggl-la-belle-aurore' => '"La Belle Aurore", cursive', 'ggl-lancelot' => '"Lancelot", cursive', 'ggl-lato' => '"Lato", sans-serif', 'ggl-league-script' => '"League Script", cursive', 'ggl-leckerli-one' => '"Leckerli One", cursive', 'ggl-lekton' => '"Lekton", sans-serif', 'ggl-lemon' => '"Lemon", cursive', 'ggl-limelight' => '"Limelight", cursive', 'ggl-linden-hill' => '"Linden Hill", serif', 'ggl-lobster-two' => '"Lobster Two", cursive', 'ggl-lobster' => '"Lobster", cursive', 'ggl-lora' => '"Lora", serif', 'ggl-love-ya-like-a-sister' => '"Love Ya Like A Sister", cursive', 'ggl-loved-by-the-king' => '"Loved by the King", cursive', 'ggl-luckiest-guy' => '"Luckiest Guy", cursive', 'ggl-maiden-orange' => '"Maiden Orange", cursive', 'ggl-mako' => '"Mako", sans-serif', 'ggl-marck-script' => '"Marck Script", cursive', 'ggl-marvel' => '"Marvel", sans-serif', 'ggl-mate-sc' => '"Mate SC", serif', 'ggl-mate' => '"Mate", serif', 'ggl-maven-pro' => '"Maven Pro", sans-serif', 'ggl-meddon' => '"Meddon", cursive', 'ggl-medievalsharp' => '"MedievalSharp", cursive', 'ggl-megrim' => '"Megrim", cursive', 'ggl-merienda-one' => '"Merienda One", cursive', 'ggl-merriweather' => '"Merriweather", serif', 'ggl-metrophobic' => '"Metrophobic", sans-serif', 'ggl-michroma' => '"Michroma", sans-serif', 'ggl-miltonian-tattoo' => '"Miltonian Tattoo", cursive', 'ggl-miltonian' => '"Miltonian", cursive', 'ggl-miss-fajardose' => '"Miss Fajardose", cursive', 'ggl-miss-saint-delafield' => '"Miss Saint Delafield", cursive', 'ggl-modern-antiqua' => '"Modern Antiqua", cursive', 'ggl-molengo' => '"Molengo", sans-serif', 'ggl-monofett' => '"Monofett", cursive', 'ggl-monoton' => '"Monoton", cursive', 'ggl-monsieur-la-doulaise' => '"Monsieur La Doulaise", cursive', 'ggl-montez' => '"Montez", cursive', 'ggl-mountains-of-christmas' => '"Mountains of Christmas", cursive', 'ggl-mr-bedford' => '"Mr Bedford", cursive', 'ggl-mr-dafoe' => '"Mr Dafoe", cursive', 'ggl-mr-de-haviland' => '"Mr De Haviland", cursive', 'ggl-mrs-sheppards' => '"Mrs Sheppards", cursive', 'ggl-muli' => '"Muli", sans-serif', 'ggl-neucha' => '"Neucha", cursive', 'ggl-neuton' => '"Neuton", serif', 'ggl-news-cycle' => '"News Cycle", sans-serif', 'ggl-niconne' => '"Niconne", cursive', 'ggl-nixie-one' => '"Nixie One", cursive', 'ggl-nobile' => '"Nobile", sans-serif', 'ggl-nosifer-caps' => '"Nosifer Caps", cursive', 'ggl-nothing-you-could-do' => '"Nothing You Could Do", cursive', 'ggl-nova-cut' => '"Nova Cut", cursive', 'ggl-nova-flat' => '"Nova Flat", cursive', 'ggl-nova-mono' => '"Nova Mono", cursive', 'ggl-nova-oval' => '"Nova Oval", cursive', 'ggl-nova-round' => '"Nova Round", cursive', 'ggl-nova-script' => '"Nova Script", cursive', 'ggl-nova-slim' => '"Nova Slim", cursive', 'ggl-nova-square' => '"Nova Square", cursive', 'ggl-numans' => '"Numans", sans-serif', 'ggl-nunito' => '"Nunito", sans-serif', 'ggl-old-standard-tt' => '"Old Standard TT", serif', 'ggl-open-sans-condensed' => '"Open Sans Condensed", sans-serif', 'ggl-open-sans' => '"Open Sans", sans-serif', 'ggl-orbitron' => '"Orbitron", sans-serif', 'ggl-oswald' => '"Oswald", sans-serif', 'ggl-over-the-rainbow' => '"Over the Rainbow", cursive', 'ggl-ovo' => '"Ovo", serif', 'ggl-pT-sans-caption' => '"PT Sans Caption", sans-serif', 'ggl-pT-sans-narrow' => '"PT Sans Narrow", sans-serif', 'ggl-pT-sans' => '"PT Sans", sans-serif', 'ggl-pT-serif-caption' => '"PT Serif Caption", serif', 'ggl-pT-serif' => '"PT Serif", serif', 'ggl-pacifico' => '"Pacifico", cursive', 'ggl-passero-one' => '"Passero One", cursive', 'ggl-patrick-hand' => '"Patrick Hand", cursive', 'ggl-paytone-one' => '"Paytone One", sans-serif', 'ggl-permanent-marker' => '"Permanent Marker", cursive', 'ggl-petrona' => '"Petrona", serif', 'ggl-philosopher' => '"Philosopher", sans-serif', 'ggl-piedra' => '"Piedra", cursive', 'ggl-pinyon-script' => '"Pinyon Script", cursive', 'ggl-play' => '"Play", sans-serif', 'ggl-playfair-display' => '"Playfair Display", serif', 'ggl-podkova' => '"Podkova", serif', 'ggl-poller-one' => '"Poller One", cursive', 'ggl-poly' => '"Poly", serif', 'ggl-pompiere' => '"Pompiere", cursive', 'ggl-prata' => '"Prata", serif', 'ggl-prociono' => '"Prociono", serif', 'ggl-puritan' => '"Puritan", sans-serif', 'ggl-quattrocento-sans' => '"Quattrocento Sans", sans-serif', 'ggl-quattrocento' => '"Quattrocento", serif', 'ggl-questrial' => '"Questrial", sans-serif', 'ggl-quicksand' => '"Quicksand", sans-serif', 'ggl-radley' => '"Radley", serif', 'ggl-raleway' => '"Raleway", cursive', 'ggl-rammetto-one' => '"Rammetto One", cursive', 'ggl-rancho' => '"Rancho", cursive', 'ggl-rationale' => '"Rationale", sans-serif', 'ggl-redressed' => '"Redressed", cursive', 'ggl-reenie-beanie' => '"Reenie Beanie", cursive', 'ggl-ribeye-marrow' => '"Ribeye Marrow", cursive', 'ggl-ribeye' => '"Ribeye", cursive', 'ggl-righteous' => '"Righteous", cursive', 'ggl-rochester' => '"Rochester", cursive', 'ggl-rock-salt' => '"Rock Salt", cursive', 'ggl-rokkitt' => '"Rokkitt", serif', 'ggl-rosario' => '"Rosario", sans-serif', 'ggl-ruslan-display' => '"Ruslan Display", cursive', 'ggl-salsa' => '"Salsa", cursive', 'ggl-sancreek' => '"Sancreek", cursive', 'ggl-sansita-one' => '"Sansita One", cursive', 'ggl-satisfy' => '"Satisfy", cursive', 'ggl-schoolbell' => '"Schoolbell", cursive', 'ggl-shadows-into-light' => '"Shadows Into Light", cursive', 'ggl-shanti' => '"Shanti", sans-serif', 'ggl-short-stack' => '"Short Stack", cursive', 'ggl-sigmar-one' => '"Sigmar One", sans-serif', 'ggl-signika-negative' => '"Signika Negative", sans-serif', 'ggl-signika' => '"Signika", sans-serif', 'ggl-six-caps' => '"Six Caps", sans-serif', 'ggl-slackey' => '"Slackey", cursive', 'ggl-smokum' => '"Smokum", cursive', 'ggl-smythe' => '"Smythe", cursive', 'ggl-sniglet' => '"Sniglet", cursive', 'ggl-snippet' => '"Snippet", sans-serif', 'ggl-sorts-mill-goudy' => '"Sorts Mill Goudy", serif', 'ggl-special-elite' => '"Special Elite", cursive', 'ggl-spinnaker' => '"Spinnaker", sans-serif', 'ggl-spirax' => '"Spirax", cursive', 'ggl-stardos-stencil' => '"Stardos Stencil", cursive', 'ggl-sue-ellen-francisco' => '"Sue Ellen Francisco", cursive', 'ggl-sunshiney' => '"Sunshiney", cursive', 'ggl-supermercado-one' => '"Supermercado One", cursive', 'ggl-swanky-and-moo-moo' => '"Swanky and Moo Moo", cursive', 'ggl-syncopate' => '"Syncopate", sans-serif', 'ggl-tangerine' => '"Tangerine", cursive', 'ggl-tenor-sans' => '"Tenor Sans", sans-serif', 'ggl-terminal-dosis' => '"Terminal Dosis", sans-serif', 'ggl-the-girl-next-door' => '"The Girl Next Door", cursive', 'ggl-tienne' => '"Tienne", serif', 'ggl-tinos' => '"Tinos", serif', 'ggl-tulpen-one' => '"Tulpen One", cursive', 'ggl-ubuntu-condensed' => '"Ubuntu Condensed", sans-serif', 'ggl-ubuntu-mono' => '"Ubuntu Mono", sans-serif', 'ggl-ubuntu' => '"Ubuntu", sans-serif', 'ggl-ultra' => '"Ultra", serif', 'ggl-unifrakturcook' => '"UnifrakturCook", cursive', 'ggl-unifrakturmaguntia' => '"UnifrakturMaguntia", cursive', 'ggl-unkempt' => '"Unkempt", cursive', 'ggl-unlock' => '"Unlock", cursive', 'ggl-unna' => '"Unna", serif', 'ggl-vt323' => '"VT323", cursive', 'ggl-varela-round' => '"Varela Round", sans-serif', 'ggl-varela' => '"Varela", sans-serif', 'ggl-vast-shadow' => '"Vast Shadow", cursive', 'ggl-vibur' => '"Vibur", cursive', 'ggl-vidaloka' => '"Vidaloka", serif', 'ggl-volkhov' => '"Volkhov", serif', 'ggl-vollkorn' => '"Vollkorn", serif', 'ggl-voltaire' => '"Voltaire", sans-serif', 'ggl-waiting-for-the-sunrise' => '"Waiting for the Sunrise", cursive', 'ggl-wallpoet' => '"Wallpoet", cursive', 'ggl-walter-turncoat' => '"Walter Turncoat", cursive', 'ggl-wire-one' => '"Wire One", sans-serif', 'ggl-yanone-kaffeesatz' => '"Yanone Kaffeesatz", sans-serif', 'ggl-yellowtail' => '"Yellowtail", cursive', 'ggl-yeseva-one' => '"Yeseva One", serif', 'ggl-zeyada' => '"Zeyada", cursive' );

		$fonts = $fonts + $google_fonts;

		if ( $id == 'nc-settings' ) {
			array_unshift( $fonts, array( '', 'false' ) );
		}

		return $fonts;

	}

	public static function add_icon() {
		$icon = Newscodes::plugin_url() . '/lib/images/vc-icon.png';
	?>
		<style type="text/css">
		#menu-settings a[href="options-general.php?page=newscodes_settings"] {
			position:relative;
		}
		#menu-settings a[href="options-general.php?page=newscodes_settings"]:after {content: '';display: inline-block;margin-top: -2px;float: left;height: 20px;width: 20px;background: url(<?php echo $icon; ?>) center center no-repeat;margin-right: 5px;border-radius: 50%;}
		</style>
	<?php
	}

}

add_action( 'init', 'NC_Admin::init', 100 );

?>