<?php

	function nc_composer_filters_field( $settings, $value ) {

		$html = '<div id="nc-composer-filters">';

			$html .= '<input id="nc-filter-manager-json" name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_field" type="text" value="' . esc_attr( $value ) . '" />';

			$html .= '<div id="nc-composer-filters-navigation">';

				$html .= '<span id="nc-add-filter" class="button">' . __( 'Add Filter', 'nwscds' ) . '</span>';
				$html .= '<span id="nc-update-filters" class="button-primary">' . __( 'Update Filters', 'nwscds' ) . '</span>';

			$html .= '</div>';

			$html .= '<div id="nc-composer-filters-default">';

				$html .= '<span id="nc-remove-filter" class="button">' . __( 'Remove Filter', 'nwscds' ) . '</span>';

				$choices = array(
					array(
						'value' => 'taxonomy',
						'label' => __( 'Taxonomy Filter', 'nwscds' )
					),
					array(
						'value' => 'meta',
						'label' => __( 'Meta Filter', 'nwscds' )
					)
				);

				$html .= '<select class="nc-filter-settings-collect nc-type" data-param="type">';

					foreach( $choices as $choice ) {
						$html .= '<option value="' . $choice['value'] . '">' . $choice['label'] . '</option>';
					}

				$html .= '</select>';

				$choices = array(
					array(
						'value' => '',
						'label' => __( 'None', 'nwscds' )
					)
				);

				$args = array(
					'publicly_queryable' => true
				);

				$post_types = get_post_types( $args, 'objects' );

				foreach( $post_types as $post_type ) {

					$choices = array(
						array(
							'value' => '',
							'label' => __( 'None', 'nwscds' )
						)
					);

					$post_taxonomies = get_object_taxonomies( $post_type->name );

					foreach( $post_taxonomies as $v ) {
						$choices[] = array(
							'value' => $v,
							'label' => $v
						);
					}

					$html .= '<select class="nc-filter-settings-collect type_taxonomy nc-taxonomy" data-param="post_type_' . esc_attr( $post_type->name ) . '">';

						foreach( $choices as $choice ) {
							$html .= '<option value="' . $choice['value'] . '">' . $choice['label'] . '</option>';
						}

					$html .= '</select>';

					foreach( $post_taxonomies as $v ) {

						$choices = array(
							array(
								'value' => '',
								'label' => __( 'None', 'nwscds' )
							)
						);

						$catalog_attrs = get_terms( $v );

						if ( !empty( $catalog_attrs ) && !is_wp_error( $catalog_attrs ) ){
							foreach ( $catalog_attrs as $term ) {
								$choices[] = array(
									'value' => $term->term_id,
									'label' => $term->name
								);
							}

							$html .= '<select class="nc-filter-settings-collect type_taxonomy nc-taxonomy-terms" data-param="taxonomy_' . esc_attr( $v ) . '">';

								foreach( $choices as $choice ) {
									$html .= '<option value="' . $choice['value'] . '">' . $choice['label'] . '</option>';
								}

							$html .= '</select>';

						}

					}

				}

				$html .= '<input class="nc-filter-settings-collect type_meta" type="text" data-param="meta_key" />';

				$html .= '<input class="nc-filter-settings-collect type_meta" type="text" data-param="meta_value" />';

				$choices = array(
					array(
						'value' => '=',
						'label' => '='
					),
					array(
						'value' => '!=',
						'label' => '!='
					),
					array(
						'value' => 'l',
						'label' => '>'
					),
					array(
						'value' => 'le',
						'label' => '>='
					),
					array(
						'value' => 's',
						'label' => '<'
					),
					array(
						'value' => 'se',
						'label' => '<='
					),
					array(
						'value' => 'LIKE',
						'label' => 'LIKE'
					),
					array(
						'value' => 'NOT LIKE',
						'label' => 'NOT LIKE'
					),
					array(
						'value' => 'IN',
						'label' => 'IN'
					),
					array(
						'value' => 'NOT IN',
						'label' => 'NOT IN'
					),
					array(
						'value' => 'EXISTS',
						'label' => 'EXISTS'
					),
					array(
						'value' => 'NOT EXISTS',
						'label' => 'NOT EXISTS'
					)
				);

				$html .= '<select class="nc-filter-settings-collect type_meta" data-param="meta_compare">';

					foreach( $choices as $choice ) {
						$html .= '<option value="' . $choice['value'] . '">' . $choice['label'] . '</option>';
					}

				$html .= '</select>';

				$choices = array(
					array(
						'value' => 'NUMERIC',
						'label' => 'NUMERIC'
					),
					array(
						'value' => 'BINARY',
						'label' => 'BINARY'
					),
					array(
						'value' => 'CHAR',
						'label' => 'CHAR'
					),
					array(
						'value' => 'DATE',
						'label' => 'DATE'
					),
					array(
						'value' => 'DATETIME',
						'label' => 'DATETIME'
					),
					array(
						'value' => 'DECIMAL',
						'label' => 'DECIMAL'
					),
					array(
						'value' => 'SIGNED',
						'label' => 'SIGNED'
					),
					array(
						'value' => 'TIME',
						'label' => 'TIME'
					),
					array(
						'value' => 'UNSIGNED',
						'label' => 'UNSIGNED'
					)
				);

				$html .= '<select class="nc-filter-settings-collect type_meta" data-param="meta_type">';

					foreach( $choices as $choice ) {
						$html .= '<option value="' . $choice['value'] . '">' . $choice['label'] . '</option>';
					}

				$html .= '</select>';

			$html .= '</div>';

			$html .= '<div id="nc-composer-filters-wrap">';

			$html .= '</div>';

		$html .= '</div>';

		return $html;

	}

	add_shortcode_param( 'nc_filter_field', 'nc_composer_filters_field', NC()->plugin_url() . '/lib/js/newscodes-composer-filters.js' );

	$choices_type = array();

	foreach( NC_Setup::$newscodes['types'] as $k => $v ) {
		$choices_type[$v] =$k;
	}

		$settings = get_option( 'newscodes_settings', array( 'styles' => array() ) );
		$styles = apply_filters( 'nc_supported_styles', $settings['styles'] );

		$choices = array(
			array(
				'value' => '',
				'label' => __( 'Default', 'nwscds' )
			)
		);

		foreach( $styles as $k => $v ) {
			foreach ( $v['styles'] as $kk => $vv ) {
				$choices[] = array(
					'value' => $kk,
					'label' => $vv['name']
				);
			}
		}

	$settings = get_option( 'newscodes_settings', array( 'styles' => array() ) );
	$styles = apply_filters( 'nc_supported_styles', $settings['styles'] );

	$choices_style[__( 'Default', 'nwscds' )] = '';

	foreach( $styles as $k => $v ) {
		foreach ( $v['styles'] as $kk => $vv ) {
			$choices_style[$vv['name']] = $kk;
		}
	}

	$choices_post_type = array();

	$args = array(
		'publicly_queryable' => true
	);

	$post_types = get_post_types( $args, 'objects' );

	foreach( $post_types as $v ) {
		$choices_post_type[$v->labels->name] = $v->name;
	}

	$choices_post_status[__( 'Published', 'nwscds' )] = 'publish';
	$choices_post_status[__( 'Pending', 'nwscds' )] = 'pending';
	$choices_post_status[__( 'Draft', 'nwscds' )] = 'draft';
	$choices_post_status[__( 'Future', 'nwscds' )] = 'future';
	$choices_post_status[__( 'Private', 'nwscds' )] = 'private';
	$choices_post_status[__( 'Trash', 'nwscds' )] = 'trash';
	$choices_post_status[__( 'Any', 'nwscds' )] = 'any';

	$choices_columns[1] = '1';
	$choices_columns[2] = '2';
	$choices_columns[3] = '3';
	$choices_columns[4] = '4';
	$choices_columns[5] = '5';
	$choices_columns[6] = '6';

	$choices_orderby[__( 'None', 'nwscds' )] = 'none';
	$choices_orderby[__( 'ID', 'nwscds' )] = 'ID';
	$choices_orderby[__( 'Author', 'nwscds' )] = 'author';
	$choices_orderby[__( 'Title', 'nwscds' )] = 'title';
	$choices_orderby[__( 'Name', 'nwscds' )] = 'name';
	$choices_orderby[__( 'Date', 'nwscds' )] = 'date';
	$choices_orderby[__( 'Modified', 'nwscds' )] = 'modified';
	$choices_orderby[__( 'Random', 'nwscds' )] = 'rand';
	$choices_orderby[__( 'Comment Count', 'nwscds' )] = 'comment_count';
	$choices_orderby[__( 'Menu Order', 'nwscds' )] = 'menu_order';
	$choices_orderby[__( 'Post In', 'nwscds' )] = 'post__in';

	$choices_order[__( 'Descending', 'nwscds' )] = 'DESC';
	$choices_order[__( 'Ascending', 'nwscds' )] = 'ASC';

	$choices_relation['AND'] = 'AND';
	$choices_relation['OR'] = 'OR';

	$choices_image_ratio['1 : 1'] = '1-1';
	$choices_image_ratio['2 : 1'] = '2-1';
	$choices_image_ratio['1 : 2'] = '1-2';
	$choices_image_ratio['3 : 1'] = '3-1';
	$choices_image_ratio['1 : 3'] = '1-3';
	$choices_image_ratio['4 : 3'] = '4-3';
	$choices_image_ratio['3 : 4'] = '3-4';
	$choices_image_ratio['16 : 9'] = '16-9';
	$choices_image_ratio['9 : 16'] = '9-16';
	$choices_image_ratio['9 : 16'] = '9-16';
	$choices_image_ratio['5 : 3'] = '5-3';
	$choices_image_ratio['3 : 5'] = '3-5';

	$choices_image_size[__( 'Default', 'nwscds' )] = '';
	$choices_image_size[__( 'Full', 'nwscds' )] = 'full';

	$image_sizes = get_intermediate_image_sizes();

	foreach ( $image_sizes as $image_size ) {
		$choices_image_size[$image_size] = $image_size;
	}

	$choices_title_tag[__( 'Heading 1', 'nwscds' )] = 'h1';
	$choices_title_tag[__( 'Heading 2', 'nwscds' )] = 'h2';
	$choices_title_tag[__( 'Heading 3', 'nwscds' )] = 'h3';
	$choices_title_tag[__( 'Heading 4', 'nwscds' )] = 'h4';
	$choices_title_tag[__( 'Heading 5', 'nwscds' )] = 'h5';
	$choices_title_tag[__( 'Heading 6', 'nwscds' )] = 'h6';

	$choices_ticker_direction[__( 'Up', 'nwscds' )] = 'up';
	$choices_ticker_direction[__( 'Down', 'nwscds' )] = 'down';

	$choices_marquee_direction[__( 'Left', 'nwscds' )] = 'left';
	$choices_marquee_direction[__( 'Right', 'nwscds' )] = 'right';

	vc_map( array(
		'name' => __( 'Newscodes Tabbed', 'newscds' ),
		'base' => 'nc_multi_factory',
		'is_container' => true,
		'show_settings_on_create' => false,
		'content_element' => true,
		'as_parent' => array(
			'only' => 'nc_multi_factory_helper',
		),
		'icon' => NC()->plugin_url() . '/lib/images/vc-icon.png',
		'category' => __( 'Content', 'newscds' ),
		'description' => __( 'Multi Mode - The ultimate shortcode set for all news sites!', 'newscds' ),
		'params' => array(
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Type', 'nwscds' ),
				'param_name'  => 'type',
				'value'       => $choices_type,
				'description' => '',
				'std'         => 'news-list-compact'
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Style', 'nwscds' ),
				'param_name'  => 'style',
				'value'       => $choices_style,
				'description' => ''
			),
		),
		'js_view' => 'VcColumnView'
	) );

	vc_map( array(
		'name'             => __( 'Newscodes Tab', 'nwscds' ),
		'base'             => 'nc_multi_factory_helper',
		'content_element' => true,
		'class'            => '',
		'category'         => __( 'Content', 'nwscds' ),
		'as_child' => array(
			'only' => 'nc_multi_factory',
		),
		'icon' => NC()->plugin_url() . '/lib/images/vc-icon.png',
		'description'      => __( 'The ultimate shortcode set for all news sites!', 'nwscds' ),
		'admin_enqueue_js' => NC()->plugin_url() . '/lib/js/newscodes-composer.js',
		'front_enqueue_js' => NC()->plugin_url() . '/lib/js/newscodes-composer.js',
		'admin_enqueue_css' => NC()->plugin_url() . '/lib/css/newscodes-composer.css',
		'front_enqueue_css' => NC()->plugin_url() . '/lib/css/newscodes-composer.css',
		'params' => array(
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Section Title', 'nwscds' ),
				'param_name'  => 'section_title',
				'value'       => '',
				'description' => '',
				'std'         => __( 'Title', 'nwscds' )
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Post Type', 'nwscds' ),
				'param_name'  => 'post_type',
				'value'       => $choices_post_type,
				'description' => '',
				'std'         => 'post'
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Post Status', 'nwscds' ),
				'param_name'  => 'post_status',
				'value'       => $choices_post_status,
				'description' => '',
				'std'         => 'publish'
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Columns', 'nwscds' ),
				'param_name'  => 'columns',
				'value'       => $choices_columns,
				'description' => '',
				'std'         => 1
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Posts Per Page', 'nwscds' ),
				'param_name'  => 'posts_per_page',
				'value'       => '',
				'description' => '',
				'std'         => 10
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Posts Per Column', 'nwscds' ),
				'param_name'  => 'posts_per_column',
				'value'       => '',
				'description' => '',
				'std'         => 3
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Offset', 'nwscds' ),
				'param_name'  => 'offset',
				'value'       => '',
				'description' => ''
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Order By', 'nwscds' ),
				'param_name'  => 'orderby',
				'value'       => $choices_orderby,
				'description' => '',
				'std'         => 'date'
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Order', 'nwscds' ),
				'param_name'  => 'order',
				'value'       => $choices_order,
				'description' => '',
				'std'         => 'DESC'
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Filters', 'nwscds' ),
				'param_name'  => 'filters',
				'value'       => '',
				'description' => ''
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Filter Terms', 'nwscds' ),
				'param_name'  => 'filter_terms',
				'value'       => '',
				'description' => ''
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Meta Keys', 'nwscds' ),
				'param_name'  => 'meta_keys',
				'value'       => '',
				'description' => ''
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Meta Values', 'nwscds' ),
				'param_name'  => 'meta_values',
				'value'       => '',
				'description' => ''
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Meta Compares', 'nwscds' ),
				'param_name'  => 'meta_compares',
				'value'       => '',
				'description' => ''
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Meta Types', 'nwscds' ),
				'param_name'  => 'meta_types',
				'value'       => '',
				'description' => ''
			),
			array(
				'type'        => 'nc_filter_field',
				'class'       => '',
				'heading'     => __( 'Post Filters', 'nwscds' ),
				'param_name'  => 'filters_manager',
				'value'       => '',
				'description' => ''
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Taxonomy Filter Relation', 'nwscds' ),
				'param_name'  => 'filter_relation',
				'value'       => $choices_relation,
				'description' => '',
				'std'         => 'OR'
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Meta Filter Relation', 'nwscds' ),
				'param_name'  => 'meta_relation',
				'value'       => $choices_relation,
				'description' => '',
				'std'         => 'OR'
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Post In', 'nwscds' ),
				'param_name'  => 'post_in',
				'value'       => '',
				'description' => ''
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Post Not In', 'nwscds' ),
				'param_name'  => 'post_notin',
				'value'       => '',
				'description' => ''
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'HTTP Query', 'nwscds' ),
				'param_name'  => 'http_query',
				'value'       => '',
				'description' => '',
				'std'         => ''
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Image Ratio', 'nwscds' ),
				'param_name'  => 'image_ratio',
				'value'       => $choices_image_ratio,
				'description' => '',
				'std'         => '4-3'
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Image Size', 'nwscds' ),
				'param_name'  => 'image_size',
				'value'       => $choices_image_size,
				'description' => '',
				'std'         => ''
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Title Tag', 'nwscds' ),
				'param_name'  => 'title_tag',
				'value'       => $choices_title_tag,
				'description' => '',
				'std'         => 'h2'
			),
			array(
				'type'        => 'checkbox',
				'class'       => '',
				'heading'     => __( 'Cut Titles', 'nwscds' ),
				'param_name'  => 'title_cut',
				'value'       => array( __( 'Cut Titles', 'nwscds' ) => 'true' ),
				'description' => '',
				'std'         => 'false'
			),
			array(
				'type'        => 'checkbox',
				'class'       => '',
				'heading'     => __( 'Show Date', 'nwscds' ),
				'param_name'  => 'show_date',
				'value'       => array( __( 'Show Date', 'nwscds' ) => 'true' ),
				'description' => '',
				'std'         => 'true'
			),
			array(
				'type'        => 'checkbox',
				'class'       => '',
				'heading'     => __( 'Show Time', 'nwscds' ),
				'param_name'  => 'show_time',
				'value'       => array( __( 'Show Time', 'nwscds' ) => 'true' ),
				'description' => '',
				'std'         => 'true'
			),
			array(
				'type'        => 'checkbox',
				'class'       => '',
				'heading'     => __( 'Show Taxonomy', 'nwscds' ),
				'param_name'  => 'show_taxonomy',
				'value'       => array( __( 'Show Taxonomy', 'nwscds' ) => 'true' ),
				'description' => '',
				'std'         => 'true'
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Which Taxonomy', 'nwscds' ),
				'param_name'  => 'which_taxonomy',
				'value'       => '',
				'description' => '',
				'std'         => ''
			),
			array(
				'type'        => 'checkbox',
				'class'       => '',
				'heading'     => __( 'Show Author', 'nwscds' ),
				'param_name'  => 'show_author',
				'value'       => array( __( 'Show Author', 'nwscds' ) => 'true' ),
				'description' => '',
				'std'         => 'true'
			),
			array(
				'type'        => 'checkbox',
				'class'       => '',
				'heading'     => __( 'Show Format', 'nwscds' ),
				'param_name'  => 'show_format',
				'value'       => array( __( 'Show Format', 'nwscds' ) => 'true' ),
				'description' => '',
				'std'         => 'true'
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Excerpt Length', 'nwscds' ),
				'param_name'  => 'excerpt_length',
				'value'       => '',
				'description' => '',
				'std'         => 20
			),
			array(
				'type'        => 'textarea',
				'class'       => '',
				'heading'     => __( 'Excerpt More', 'nwscds' ),
				'param_name'  => 'excerpt_more',
				'value'       => '',
				'description' => '',
				'std'         => ''
			),
			array(
				'type'        => 'checkbox',
				'class'       => '',
				'heading'     => __( 'Pagination', 'nwscds' ),
				'param_name'  => 'pagination',
				'value'       => array( __( 'Pagination', 'nwscds' ) => 'true' ),
				'description' => '',
				'std'         => 'true'
			),
			array(
				'type'        => 'checkbox',
				'class'       => '',
				'heading'     => __( 'Ajax', 'nwscds' ),
				'param_name'  => 'ajax',
				'value'       => array( __( 'Ajax', 'nwscds' ) => 'true' ),
				'description' => '',
				'std'         => 'true'
			),
			array(
				'type'        => 'checkbox',
				'class'       => '',
				'heading'     => __( 'Load More', 'nwscds' ),
				'param_name'  => 'load_more',
				'value'       => array( __( 'Load More', 'nwscds' ) => 'true' ),
				'description' => '',
				'std'         => 'false'
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Visible In Ticker', 'nwscds' ),
				'param_name'  => 'ticker_visible',
				'value'       => '',
				'description' => '',
				'std'         => 3
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Ticker Direction', 'nwscds' ),
				'param_name'  => 'ticker_direction',
				'value'       => $choices_ticker_direction,
				'description' => '',
				'std'         => 'up'
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Marquee Direction', 'nwscds' ),
				'param_name'  => 'marquee_direction',
				'value'       => $choices_marquee_direction,
				'description' => '',
				'std'         => 'left'
			)
		)
	) );


	vc_map( array(
		'name'             => __( 'Newscodes', 'nwscds' ),
		'base'             => 'nc_factory',
		'class'            => '',
		'category'         => __( 'Content', 'nwscds' ),
		'icon' => NC()->plugin_url() . '/lib/images/vc-icon.png',
		'description'      => __( 'The ultimate shortcode set for all news sites!', 'nwscds' ),
		'admin_enqueue_js' => NC()->plugin_url() . '/lib/js/newscodes-composer.js',
		'front_enqueue_js' => NC()->plugin_url() . '/lib/js/newscodes-composer.js',
		'admin_enqueue_css' => NC()->plugin_url() . '/lib/css/newscodes-composer.css',
		'front_enqueue_css' => NC()->plugin_url() . '/lib/css/newscodes-composer.css',
		'params' => array(
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Type', 'nwscds' ),
				'param_name'  => 'type',
				'value'       => $choices_type,
				'description' => '',
				'std'         => 'news-list-compact'
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Style', 'nwscds' ),
				'param_name'  => 'style',
				'value'       => $choices_style,
				'description' => ''
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Post Type', 'nwscds' ),
				'param_name'  => 'post_type',
				'value'       => $choices_post_type,
				'description' => '',
				'std'         => 'post'
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Post Status', 'nwscds' ),
				'param_name'  => 'post_status',
				'value'       => $choices_post_status,
				'description' => '',
				'std'         => 'publish'
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Columns', 'nwscds' ),
				'param_name'  => 'columns',
				'value'       => $choices_columns,
				'description' => '',
				'std'         => 1
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Posts Per Page', 'nwscds' ),
				'param_name'  => 'posts_per_page',
				'value'       => '',
				'description' => '',
				'std'         => 10
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Posts Per Column', 'nwscds' ),
				'param_name'  => 'posts_per_column',
				'value'       => '',
				'description' => '',
				'std'         => 3
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Offset', 'nwscds' ),
				'param_name'  => 'offset',
				'value'       => '',
				'description' => ''
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Order By', 'nwscds' ),
				'param_name'  => 'orderby',
				'value'       => $choices_orderby,
				'description' => '',
				'std'         => 'date'
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Order', 'nwscds' ),
				'param_name'  => 'order',
				'value'       => $choices_order,
				'description' => '',
				'std'         => 'DESC'
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Filters', 'nwscds' ),
				'param_name'  => 'filters',
				'value'       => '',
				'description' => ''
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Filter Terms', 'nwscds' ),
				'param_name'  => 'filter_terms',
				'value'       => '',
				'description' => ''
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Meta Keys', 'nwscds' ),
				'param_name'  => 'meta_keys',
				'value'       => '',
				'description' => ''
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Meta Values', 'nwscds' ),
				'param_name'  => 'meta_values',
				'value'       => '',
				'description' => ''
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Meta Compares', 'nwscds' ),
				'param_name'  => 'meta_compares',
				'value'       => '',
				'description' => ''
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Meta Types', 'nwscds' ),
				'param_name'  => 'meta_types',
				'value'       => '',
				'description' => ''
			),
			array(
				'type'        => 'nc_filter_field',
				'class'       => '',
				'heading'     => __( 'Post Filters', 'nwscds' ),
				'param_name'  => 'filters_manager',
				'value'       => '',
				'description' => ''
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Taxonomy Filter Relation', 'nwscds' ),
				'param_name'  => 'filter_relation',
				'value'       => $choices_relation,
				'description' => '',
				'std'         => 'OR'
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Meta Filter Relation', 'nwscds' ),
				'param_name'  => 'meta_relation',
				'value'       => $choices_relation,
				'description' => '',
				'std'         => 'OR'
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Post In', 'nwscds' ),
				'param_name'  => 'post_in',
				'value'       => '',
				'description' => ''
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Post Not In', 'nwscds' ),
				'param_name'  => 'post_notin',
				'value'       => '',
				'description' => ''
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'HTTP Query', 'nwscds' ),
				'param_name'  => 'http_query',
				'value'       => '',
				'description' => '',
				'std'         => ''
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Image Ratio', 'nwscds' ),
				'param_name'  => 'image_ratio',
				'value'       => $choices_image_ratio,
				'description' => '',
				'std'         => '4-3'
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Image Size', 'nwscds' ),
				'param_name'  => 'image_size',
				'value'       => $choices_image_size,
				'description' => '',
				'std'         => ''
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Title Tag', 'nwscds' ),
				'param_name'  => 'title_tag',
				'value'       => $choices_title_tag,
				'description' => '',
				'std'         => 'h2'
			),
			array(
				'type'        => 'checkbox',
				'class'       => '',
				'heading'     => __( 'Cut Titles', 'nwscds' ),
				'param_name'  => 'title_cut',
				'value'       => array( __( 'Cut Titles', 'nwscds' ) => 'true' ),
				'description' => '',
				'std'         => 'false'
			),
			array(
				'type'        => 'checkbox',
				'class'       => '',
				'heading'     => __( 'Show Date', 'nwscds' ),
				'param_name'  => 'show_date',
				'value'       => array( __( 'Show Date', 'nwscds' ) => 'true' ),
				'description' => '',
				'std'         => 'true'
			),
			array(
				'type'        => 'checkbox',
				'class'       => '',
				'heading'     => __( 'Show Time', 'nwscds' ),
				'param_name'  => 'show_time',
				'value'       => array( __( 'Show Time', 'nwscds' ) => 'true' ),
				'description' => '',
				'std'         => 'true'
			),
			array(
				'type'        => 'checkbox',
				'class'       => '',
				'heading'     => __( 'Show Taxonomy', 'nwscds' ),
				'param_name'  => 'show_taxonomy',
				'value'       => array( __( 'Show Taxonomy', 'nwscds' ) => 'true' ),
				'description' => '',
				'std'         => 'true'
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Which Taxonomy', 'nwscds' ),
				'param_name'  => 'which_taxonomy',
				'value'       => '',
				'description' => '',
				'std'         => ''
			),
			array(
				'type'        => 'checkbox',
				'class'       => '',
				'heading'     => __( 'Show Author', 'nwscds' ),
				'param_name'  => 'show_author',
				'value'       => array( __( 'Show Author', 'nwscds' ) => 'true' ),
				'description' => '',
				'std'         => 'true'
			),
			array(
				'type'        => 'checkbox',
				'class'       => '',
				'heading'     => __( 'Show Format', 'nwscds' ),
				'param_name'  => 'show_format',
				'value'       => array( __( 'Show Format', 'nwscds' ) => 'true' ),
				'description' => '',
				'std'         => 'true'
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Excerpt Length', 'nwscds' ),
				'param_name'  => 'excerpt_length',
				'value'       => '',
				'description' => '',
				'std'         => 20
			),
			array(
				'type'        => 'textarea',
				'class'       => '',
				'heading'     => __( 'Excerpt More', 'nwscds' ),
				'param_name'  => 'excerpt_more',
				'value'       => '',
				'description' => '',
				'std'         => ''
			),
			array(
				'type'        => 'checkbox',
				'class'       => '',
				'heading'     => __( 'Pagination', 'nwscds' ),
				'param_name'  => 'pagination',
				'value'       => array( __( 'Pagination', 'nwscds' ) => 'true' ),
				'description' => '',
				'std'         => 'true'
			),
			array(
				'type'        => 'checkbox',
				'class'       => '',
				'heading'     => __( 'Ajax', 'nwscds' ),
				'param_name'  => 'ajax',
				'value'       => array( __( 'Ajax', 'nwscds' ) => 'true' ),
				'description' => '',
				'std'         => 'true'
			),
			array(
				'type'        => 'checkbox',
				'class'       => '',
				'heading'     => __( 'Load More', 'nwscds' ),
				'param_name'  => 'load_more',
				'value'       => array( __( 'Load More', 'nwscds' ) => 'true' ),
				'description' => '',
				'std'         => 'false'
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __( 'Visible In Ticker', 'nwscds' ),
				'param_name'  => 'ticker_visible',
				'value'       => '',
				'description' => '',
				'std'         => 3
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Ticker Direction', 'nwscds' ),
				'param_name'  => 'ticker_direction',
				'value'       => $choices_ticker_direction,
				'description' => '',
				'std'         => 'up'
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __( 'Marquee Direction', 'nwscds' ),
				'param_name'  => 'marquee_direction',
				'value'       => $choices_marquee_direction,
				'description' => '',
				'std'         => 'left'
			)
		)
	) );

	if ( class_exists( 'WPBakeryShortCode' ) ) {
		class WPBakeryShortCode_Nc_Factory extends WPBakeryShortCode {
		}
	}
	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
		class WPBakeryShortCode_Nc_Multi_Factory extends WPBakeryShortCodesContainer {
		}
	}
	if ( class_exists( 'WPBakeryShortCode' ) ) {
		class WPBakeryShortCode_Nc_Multi_Factory_Helper extends WPBakeryShortCode {
		}
	}

?>