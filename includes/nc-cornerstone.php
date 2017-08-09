<?php

	class NewsCodesCornerstoneMulti extends Cornerstone_Element_Base {

		public function data() {

			return array(
				'name'        => 'newscodes-multi',
				'title'       => __( 'Newscodes Multi', 'nwscds' ),
				'description' => __( 'The ultimate shortcode set for all news sites in multi mode!', 'nwscds' ),
				'supports'    => array( 'class', 'animation' ),
				'render'      => true,
				'icon'        => 'newscodes-map/default'
			);

		}

		public function controls() {

			$choices = array();

			foreach( NC_Setup::$newscodes['types'] as $k => $v ) {
				$choices[] = array(
					'value' => $k,
					'label' => $v
				);
			}

			$this->addControl(
				'type',
				'select',
				__( 'Type', 'nwscds' ),
				__( 'Type', 'nwscds' ),
				'news-list-compact',
				array(
					'choices' => $choices
				)
			);

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

			$this->addControl(
				'style_cs',
				'select',
				__( 'Style', 'nwscds' ),
				__( 'Style', 'nwscds' ),
				'',
				array(
					'choices' => $choices
				)
			);

			$this->addControl(
				'elements',
				'sortable',
				__( 'Sections', 'nwscds' ),
				__( 'Sections', 'nwscds' ),
				array(),
				array(
					'element'   => 'newscodes-helper',
					'newTitle'  => __( 'Newscode #%s', 'nwscds' ),
				)
			);

		}

		public function render( $atts ) {

			$elements = isset( $atts['elements'] ) ? $atts['elements'] : array();

			$contents = array();

			if ( is_array( $elements ) && !empty( $elements ) ) {

				foreach( $elements as $elk => $el ) {

					if ( $el['_type'] == 'newscodes-helper' ) {

						$contents[] = self::render_alternative( $el );

					}

				}

			}

			$extra = '';

			if ( !empty( $atts['type'] ) ) {
				$extra .= ' type="' . $atts['type'] . '"';
			}

			if ( !empty( $atts['style_cs'] ) ) {
				$extra .= ' style_cs="' . $atts['style_cs'] . '"';
			}

			$shortcode = '[nc_multi_factory' . $extra . ']' . implode( $contents, '' ) . '[/nc_multi_factory]';

			return $shortcode;

		}

		public static function render_alternative( $atts ) {

			$valid = NC_Setup::$newscodes['atts'];

			$pass = array();

			if ( $atts['excerpt_more'] !== '' && strpos( $atts['excerpt_more'], '"' ) ) {
				$atts['excerpt_more'] = str_replace( '"', "'", $atts['excerpt_more'] );
			}

			if ( ( $tax = $atts['which_taxonomy_' . $atts['post_type']] ) !== '' && taxonomy_exists( $tax ) ) {
				$pass[] = ' which_taxonomy="' . $tax . '"';
			}

			foreach( $atts as $k => $v ) {
				if ( array_key_exists( $k, $valid ) && !empty( $v ) ) {
					$pass[] = ' ' . $k . '="' . $v . '"';
				}
				else if ( array_key_exists( $k, $valid ) && !empty( $valid[$k] ) ) {
					$pass[] = ' ' . $k . '="' . $valid[$k] . '"';
				}
			}

			$elements = isset( $atts['elements'] ) ? $atts['elements'] : array();

			if ( is_array( $elements ) && !empty( $elements ) ) {

				$add_taxonomies = array();
				$add_terms = array();

				$add_keys = array();
				$add_values = array();
				$add_compares = array();
				$add_types = array();

				foreach( $elements as $elk => $el ) {

					if ( $el['_type'] == 'newscodes-filters' ) {

						if ( $el['filter_type'] == 'taxonomy' ) {
							$add_taxonomies[] = $el[$atts['post_type'] . '_taxonomy'];
							$add_terms[] = isset( $el[$el[$atts['post_type'] . '_taxonomy'] . '_terms'] ) ? $el[$el[$atts['post_type'] . '_taxonomy'] . '_terms'] : '-1';
						}
						else if ( $el['filter_type'] == 'meta' ) {
							$add_keys[] = isset( $el['key'] ) ? $el['key'] : '';
							$add_values[] = isset( $el['value'] ) ? $el['value'] : '';
							$add_compares[] = isset( $el['compare'] ) ? $el['compare'] : '';
							$add_types[] = isset( $el['type'] ) ? $el['type'] : '';
						}
					}

				}

				if ( !empty( $add_taxonomies ) ) {
					$pass[] = ' filters="' . implode( $add_taxonomies, '|' ) . '"';
					$pass[] = ' filter_terms="' . implode( $add_terms, '|' ) . '"';
				}

				if ( !empty( $add_keys ) ) {
					$pass[] = ' meta_keys="' . implode( $add_keys, '|' ) . '"';
					$pass[] = ' meta_values="' . implode( $add_values, '|' ) . '"';
					$pass[] = ' meta_compares="' . implode( $add_compares, '|' ) . '"';
					$pass[] = ' meta_types="' . implode( $add_types, '|' ) . '"';
				}


			}

			$shortcode = '[nc_factory' . implode( $pass, '' ) . ']';

			return $shortcode;

		}

	}


	class NewsCodesCornerstoneMultiHelper extends Cornerstone_Element_Base {

		public function data() {
			return array(
				'name'        => 'newscodes-helper',
				'title'       => __( 'Newscodes', 'nwscds' ),
				'description' => __( 'The ultimate shortcode set for all news sites!', 'nwscds' ),
				'supports'    => array( 'class' ),
				'render'      => true,
				'delegate'    => true
			);
		}

		public function controls() {

			$this->addControl(
				'section_title',
				'text',
				__( 'Title', 'nwscds' ),
				__( 'Title', 'nwscds' ),
				__( 'Title', 'nwscds' ),
				array()
			);

			$args = array(
				'publicly_queryable' => true
			);

			$choices = array();

			$post_types = get_post_types( $args, 'objects' );

			foreach( $post_types as $v ) {
				$choices[] = array(
					'value' => $v->name,
					'label' => $v->labels->name
				);
			}

			$this->addControl(
				'post_type',
				'select',
				__( 'Post Type', 'nwscds' ),
				__( 'Post Type', 'nwscds' ),
				'post',
				array(
					'choices' => $choices
				)
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

			$this->addControl(
				'post_status',
				'select',
				__( 'Post Status', 'nwscds' ),
				__( 'Post Status', 'nwscds' ),
				'publish',
				array(
					'choices' => $choices
				)
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

			$this->addControl(
				'columns',
				'select',
				__( 'Columns', 'nwscds' ),
				__( 'Columns', 'nwscds' ),
				'1',
				array(
					'choices' => $choices,
					'condition' => array(
						'parent:type' => array( 'news-grid', 'news-grid-author' )
					)
				)
			);

			$this->addControl(
				'posts_per_page',
				'number',
				__( 'Posts Per Page', 'nwscds' ),
				__( 'Posts Per Page', 'nwscds' ),
				10
			);

			$this->addControl(
				'posts_per_column',
				'number',
				__( 'Posts Per Column', 'nwscds' ),
				__( 'Posts Per Column', 'nwscds' ),
				3,
				array(
					'condition' => array(
						'parent:type' => array( 'news-columned-featured-list', 'news-columned-featured-list-compact', 'news-columned-featured-list-tiny' )
					)
				)
			);

			$this->addControl(
				'offset',
				'number',
				__( 'Offset', 'nwscds' ),
				__( 'Offset', 'nwscds' ),
				''
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

			$this->addControl(
				'orderby',
				'select',
				__( 'Order By', 'nwscds' ),
				__( 'Order By', 'nwscds' ),
				'date',
				array(
					'choices' => $choices
				)
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

			$this->addControl(
				'order',
				'select',
				__( 'Order', 'nwscds' ),
				__( 'Order', 'nwscds' ),
				'DESC',
				array(
					'choices' => $choices
				)
			);

			$this->addControl(
				'elements',
				'sortable',
				__( 'Post Filters', 'nwscds' ),
				__( 'Post Filters', 'nwscds' ),
				array(),
				array(
					'element'   => 'newscodes-filters',
					'newTitle' => __( 'Filter #%s', 'nwscds' )
				)
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

			$this->addControl(
				'filter_relation',
				'select',
				__( 'Taxonomy Filter Relation', 'nwscds' ),
				__( 'Taxonomy Filter Relation', 'nwscds' ),
				'OR',
				array(
					'choices' => $choices
				)
			);

			$this->addControl(
				'meta_relation',
				'select',
				__( 'Meta Filter Relation', 'nwscds' ),
				__( 'Meta Filter Relation', 'nwscds' ),
				'OR',
				array(
					'choices' => $choices
				)
			);

			$this->addControl(
				'post_in',
				'text',
				__( 'Post In', 'nwscds' ),
				__( 'Post In', 'nwscds' )
			);

			$this->addControl(
				'post_notin',
				'text',
				__( 'Post Not In', 'nwscds' ),
				__( 'Post Not In', 'nwscds' )
			);

			$this->addControl(
				'http_query',
				'textarea',
				__( 'HTTP Query', 'nwscds' ),
				__( 'HTTP Query', 'nwscds' )
			);

			$choices = array(
				array(
					'value' => '1-1',
					'label' => '1 : 1',
					'icon' => fa_entity( 'image' )
				),
				array(
					'value' => '2-1',
					'label' => '2 : 1',
					'icon' => fa_entity( 'image' )
				),
				array(
					'value' => '1-2',
					'label' => '1 : 2',
					'icon' => fa_entity( 'image' )
				),
				array(
					'value' => '3-1',
					'label' => '3 : 1',
					'icon' => fa_entity( 'image' )
				),
				array(
					'value' => '1-3',
					'label' => '1 : 3',
					'icon' => fa_entity( 'image' )
				),
				array(
					'value' => '4-3',
					'label' => '4 : 3',
					'icon' => fa_entity( 'image' )
				),
				array(
					'value' => '3-4',
					'label' => '3 : 4',
					'icon' => fa_entity( 'image' )
				),
				array(
					'value' => '16-9',
					'label' => '16 : 9',
					'icon' => fa_entity( 'image' )
				),
				array(
					'value' => '9-16',
					'label' => '9 : 16',
					'icon' => fa_entity( 'image' )
				),
				array(
					'value' => '5-3',
					'label' => '5 : 3',
					'icon' => fa_entity( 'image' )
				),
				array(
					'value' => '3-5',
					'label' => '3 : 5',
					'icon' => fa_entity( 'image' )
				),
			);

			$this->addControl(
				'image_ratio',
				'choose',
				__( 'Image Ratio', 'nwscds' ),
				__( 'Image Ratio', 'nwscds' ),
				'4-3',
				array(
					'columns' => '4',
					'choices' => $choices,
					'condition' => array(
						'parent:type:not' => array( 'news-list-compact', 'news-list-tiny', 'news-marquee', 'news-ticker-compact', 'news-ticker-tiny' )
					)
				)
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

			$this->addControl(
				'image_size',
				'select',
				__( 'Override Image Resolution', 'nwscds' ),
				__( 'Override Image Resolution', 'nwscds' ),
				'',
				array(
					'choices' => $choices,
					'condition' => array(
						'parent:type:not' => array( 'news-list-compact', 'news-list-tiny', 'news-marquee', 'news-ticker-compact', 'news-ticker-tiny' )
					)
				)
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

			$this->addControl(
				'title_tag',
				'select',
				__( 'Title Tag', 'nwscds' ),
				__( 'Title Tag', 'nwscds' ),
				'h2',
				array(
					'choices' => $choices
				)
			);

			$this->addControl(
				'title_cut',
				'toggle',
				__( 'Cut Titles', 'nwscds' ),
				__( 'Cut Titles', 'nwscds' ),
				false,
				array(
					'condition' => array(
						'parent:type:not' => 'news-marquee'
					)
				)
			);

			$this->addControl(
				'show_date',
				'toggle',
				__( 'Show Date', 'nwscds' ),
				__( 'Show Date', 'nwscds' ),
				true
			);

			$this->addControl(
				'show_time',
				'toggle',
				__( 'Show Time', 'nwscds' ),
				__( 'Show Time', 'nwscds' ),
				true
			);

			$this->addControl(
				'show_taxonomy',
				'toggle',
				__( 'Show Taxonomy', 'nwscds' ),
				__( 'Show Taxonomy', 'nwscds' ),
				true
			);

			foreach( $post_types as $k ) {

				$choices = array();
				$first = '';

				$post_taxonomies = get_object_taxonomies( $k->name );

				foreach( $post_taxonomies as $v ) {

					if ( !isset( $first ) ) {
						$first = $v;
					}

					$choices[] = array(
						'value' => $v,
						'label' => $v
					);
				}

				$this->addControl(
					'which_taxonomy_' . $k->name,
					'select',
					__( 'Which Taxonomy?', 'nwscds' ),
					__( 'Which Taxonomy?', 'nwscds' ),
					$first,
					array(
						'choices' => $choices,
						'condition' => array(
							'post_type' => $k->name,
							'show_taxonomy' => true
						)
					)
				);

			}

			$this->addControl(
				'show_author',
				'toggle',
				__( 'Show Author', 'nwscds' ),
				__( 'Show Author', 'nwscds' ),
				true
			);

			$this->addControl(
				'show_format',
				'toggle',
				__( 'Show Format', 'nwscds' ),
				__( 'Show Format', 'nwscds' ),
				true
			);

			$this->addControl(
				'excerpt_length',
				'number',
				__( 'Excerpt Length', 'nwscds' ),
				__( 'Excerpt Length', 'nwscds' ),
				20,
				array(
					'condition' => array(
						'parent:type:not' => array( 'news-list-compact', 'news-marquee' )
					)
				)
			);

			$this->addControl(
				'excerpt_more',
				'textarea',
				__( 'Excerpt More', 'nwscds' ),
				__( 'Excerpt More', 'nwscds' ),
				'',
				array(
					'expandable' => __( 'Content', 'nwscds' ),
					'condition' => array(
						'parent:type:not' => array( 'news-list-compact', 'news-marquee' )
					)
				)
			);

			$this->addControl(
				'pagination',
				'toggle',
				__( 'Pagination', 'nwscds' ),
				__( 'Pagination', 'nwscds' ),
				true
			);

			$this->addControl(
				'ajax',
				'toggle',
				__( 'AJAX', 'nwscds' ),
				__( 'AJAX', 'nwscds' ),
				true,
				array(
					'condition' => array(
						'pagination' => true
					)
				)
			);

			$this->addControl(
				'load_more',
				'toggle',
				__( 'Load More', 'nwscds' ),
				__( 'Load More', 'nwscds' ),
				false,
				array(
					'condition' => array(
						'pagination' => true,
						'ajax' => true
					)
				)
			);

			$this->addControl(
				'ticker_visible',
				'number',
				__( 'Visible in Ticker', 'nwscds' ),
				__( 'Visible in Ticker', 'nwscds' ),
				3,
				array(
					'condition' => array(
						'parent:type' => array( 'news-ticker', 'news-ticker-compact', 'news-ticker-tiny' )
					)
				)
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

			$this->addControl(
				'ticker_direction',
				'select',
				__( 'Ticker Direction', 'nwscds' ),
				__( 'Ticker Direction', 'nwscds' ),
				'up',
				array(
					'choices' => $choices,
					'condition' => array(
						'parent:type' => array( 'news-ticker', 'news-ticker-compact', 'news-ticker-tiny' )
					)
				)
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

			$this->addControl(
				'marquee_direction',
				'select',
				__( 'Marquee Direction', 'nwscds' ),
				__( 'Marquee Direction', 'nwscds' ),
				'left',
				array(
					'choices' => $choices,
					'condition' => array(
						'parent:type' => array( 'news-marquee' )
					)
				)
			);

		}

		public function render( $atts ) {

			$valid = NC_Setup::$newscodes['atts'];

			$pass = array();

			if ( $atts['excerpt_more'] !== '' && strpos( $atts['excerpt_more'], '"' ) ) {
				$atts['excerpt_more'] = str_replace( '"', "'", $atts['excerpt_more'] );
			}

			if ( ( $tax = $atts['which_taxonomy_' . $atts['post_type']] ) !== '' && taxonomy_exists( $tax ) ) {
				$pass[] = ' which_taxonomy="' . $tax . '"';
			}

			foreach( $atts as $k => $v ) {
				if ( array_key_exists( $k, $valid ) && !empty( $v ) ) {
					$pass[] = ' ' . $k . '="' . $v . '"';
				}
				else if ( array_key_exists( $k, $valid ) && !empty( $valid[$k] ) ) {
					$pass[] = ' ' . $k . '="' . $valid[$k] . '"';
				}
			}

			$elements = isset( $atts['elements'] ) ? $atts['elements'] : array();

			if ( is_array( $elements ) && !empty( $elements ) ) {

				$add_taxonomies = array();
				$add_terms = array();

				$add_keys = array();
				$add_values = array();
				$add_compares = array();
				$add_types = array();

				foreach( $elements as $elk => $el ) {

					if ( $el['_type'] == 'newscodes-filters' ) {

						if ( $el['filter_type'] == 'taxonomy' ) {
							$add_taxonomies[] = $el[$atts['post_type'] . '_taxonomy'];
							$add_terms[] = isset( $el[$el[$atts['post_type'] . '_taxonomy'] . '_terms'] ) ? $el[$el[$atts['post_type'] . '_taxonomy'] . '_terms'] : '-1';
						}
						else if ( $el['filter_type'] == 'meta' ) {
							$add_keys[] = isset( $el['key'] ) ? $el['key'] : '';
							$add_values[] = isset( $el['value'] ) ? $el['value'] : '';
							$add_compares[] = isset( $el['compare'] ) ? $el['compare'] : '';
							$add_types[] = isset( $el['type'] ) ? $el['type'] : '';
						}
					}

				}

				if ( !empty( $add_taxonomies ) ) {
					$pass[] = ' filters="' . implode( $add_taxonomies, '|' ) . '"';
					$pass[] = ' filter_terms="' . implode( $add_terms, '|' ) . '"';
				}

				if ( !empty( $add_keys ) ) {
					$pass[] = ' meta_keys="' . implode( $add_keys, '|' ) . '"';
					$pass[] = ' meta_values="' . implode( $add_values, '|' ) . '"';
					$pass[] = ' meta_compares="' . implode( $add_compares, '|' ) . '"';
					$pass[] = ' meta_types="' . implode( $add_types, '|' ) . '"';
				}


			}

			$shortcode = '[nc_factory' . implode( $pass, '' ) . ']';

			return $shortcode;

		}

	}

	class NewsCodesCornerstone extends Cornerstone_Element_Base {

		public function data() {
			return array(
				'name'        => 'newscodes',
				'title'       => __( 'Newscodes', 'nwscds' ),
				'description' => __( 'The ultimate shortcode set for all news sites!', 'nwscds' ),
				'supports'    => array( 'class', 'animation' ),
				'render'      => true,
				'icon'        => 'newscodes-map/default'
			);
		}

		public function controls() {

			$choices = array();

			foreach( NC_Setup::$newscodes['types'] as $k => $v ) {
				$choices[] = array(
					'value' => $k,
					'label' => $v
				);
			}

			$this->addControl(
				'type',
				'select',
				__( 'Type', 'nwscds' ),
				__( 'Type', 'nwscds' ),
				'news-list-compact',
				array(
					'choices' => $choices
				)
			);

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

			$this->addControl(
				'style_cs',
				'select',
				__( 'Style', 'nwscds' ),
				__( 'Style', 'nwscds' ),
				'',
				array(
					'choices' => $choices
				)
			);

			$args = array(
				'publicly_queryable' => true
			);

			$choices = array();

			$post_types = get_post_types( $args, 'objects' );

			foreach( $post_types as $v ) {
				$choices[] = array(
					'value' => $v->name,
					'label' => $v->labels->name
				);
			}

			$this->addControl(
				'post_type',
				'select',
				__( 'Post Type', 'nwscds' ),
				__( 'Post Type', 'nwscds' ),
				'post',
				array(
					'choices' => $choices
				)
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

			$this->addControl(
				'post_status',
				'select',
				__( 'Post Status', 'nwscds' ),
				__( 'Post Status', 'nwscds' ),
				'publish',
				array(
					'choices' => $choices
				)
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

			$this->addControl(
				'columns',
				'select',
				__( 'Columns', 'nwscds' ),
				__( 'Columns', 'nwscds' ),
				'1',
				array(
					'choices' => $choices,
					'condition' => array(
						'type' => array( 'news-grid', 'news-grid-author' )
					)
				)
			);

			$this->addControl(
				'posts_per_page',
				'number',
				__( 'Posts Per Page', 'nwscds' ),
				__( 'Posts Per Page', 'nwscds' ),
				10
			);

			$this->addControl(
				'posts_per_column',
				'number',
				__( 'Posts Per Column', 'nwscds' ),
				__( 'Posts Per Column', 'nwscds' ),
				3,
				array(
					'condition' => array(
						'type' => array( 'news-columned-featured-list', 'news-columned-featured-list-compact', 'news-columned-featured-list-tiny' )
					)
				)
			);

			$this->addControl(
				'offset',
				'number',
				__( 'Offset', 'nwscds' ),
				__( 'Offset', 'nwscds' ),
				''
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

			$this->addControl(
				'orderby',
				'select',
				__( 'Order By', 'nwscds' ),
				__( 'Order By', 'nwscds' ),
				'date',
				array(
					'choices' => $choices
				)
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

			$this->addControl(
				'order',
				'select',
				__( 'Order', 'nwscds' ),
				__( 'Order', 'nwscds' ),
				'DESC',
				array(
					'choices' => $choices
				)
			);

			$this->addControl(
				'elements',
				'sortable',
				__( 'Post Filters', 'nwscds' ),
				__( 'Post Filters', 'nwscds' ),
				array(),
				array(
					'element'   => 'newscodes-filters',
					'newTitle' => __( 'Filter #%s', 'nwscds' )
				)
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

			$this->addControl(
				'filter_relation',
				'select',
				__( 'Taxonomy Filter Relation', 'nwscds' ),
				__( 'Taxonomy Filter Relation', 'nwscds' ),
				'OR',
				array(
					'choices' => $choices
				)
			);

			$this->addControl(
				'meta_relation',
				'select',
				__( 'Meta Filter Relation', 'nwscds' ),
				__( 'Meta Filter Relation', 'nwscds' ),
				'OR',
				array(
					'choices' => $choices
				)
			);

			$this->addControl(
				'post_in',
				'text',
				__( 'Post In', 'nwscds' ),
				__( 'Post In', 'nwscds' )
			);

			$this->addControl(
				'post_notin',
				'text',
				__( 'Post Not In', 'nwscds' ),
				__( 'Post Not In', 'nwscds' )
			);

			$this->addControl(
				'http_query',
				'textarea',
				__( 'HTTP Query', 'nwscds' ),
				__( 'HTTP Query', 'nwscds' )
			);

			$choices = array(
				array(
					'value' => '1-1',
					'label' => '1 : 1',
					'icon' => fa_entity( 'image' )
				),
				array(
					'value' => '2-1',
					'label' => '2 : 1',
					'icon' => fa_entity( 'image' )
				),
				array(
					'value' => '1-2',
					'label' => '1 : 2',
					'icon' => fa_entity( 'image' )
				),
				array(
					'value' => '3-1',
					'label' => '3 : 1',
					'icon' => fa_entity( 'image' )
				),
				array(
					'value' => '1-3',
					'label' => '1 : 3',
					'icon' => fa_entity( 'image' )
				),
				array(
					'value' => '4-3',
					'label' => '4 : 3',
					'icon' => fa_entity( 'image' )
				),
				array(
					'value' => '3-4',
					'label' => '3 : 4',
					'icon' => fa_entity( 'image' )
				),
				array(
					'value' => '16-9',
					'label' => '16 : 9',
					'icon' => fa_entity( 'image' )
				),
				array(
					'value' => '9-16',
					'label' => '9 : 16',
					'icon' => fa_entity( 'image' )
				),
				array(
					'value' => '5-3',
					'label' => '5 : 3',
					'icon' => fa_entity( 'image' )
				),
				array(
					'value' => '3-5',
					'label' => '3 : 5',
					'icon' => fa_entity( 'image' )
				),
			);

			$this->addControl(
				'image_ratio',
				'choose',
				__( 'Image Ratio', 'nwscds' ),
				__( 'Image Ratio', 'nwscds' ),
				'4-3',
				array(
					'columns' => '4',
					'choices' => $choices,
					'condition' => array(
						'type:not' => array( 'news-list-compact', 'news-list-tiny', 'news-marquee', 'news-ticker-compact', 'news-ticker-tiny' )
					)
				)
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

			$this->addControl(
				'image_size',
				'select',
				__( 'Override Image Resolution', 'nwscds' ),
				__( 'Override Image Resolution', 'nwscds' ),
				'',
				array(
					'choices' => $choices,
					'condition' => array(
						'type:not' => array( 'news-list-compact', 'news-list-tiny', 'news-marquee', 'news-ticker-compact', 'news-ticker-tiny' )
					)
				)
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

			$this->addControl(
				'title_tag',
				'select',
				__( 'Title Tag', 'nwscds' ),
				__( 'Title Tag', 'nwscds' ),
				'h2',
				array(
					'choices' => $choices
				)
			);

			$this->addControl(
				'title_cut',
				'toggle',
				__( 'Cut Titles', 'nwscds' ),
				__( 'Cut Titles', 'nwscds' ),
				false,
				array(
					'condition' => array(
						'type:not' => 'news-marquee'
					)
				)
			);

			$this->addControl(
				'show_date',
				'toggle',
				__( 'Show Date', 'nwscds' ),
				__( 'Show Date', 'nwscds' ),
				true
			);

			$this->addControl(
				'show_time',
				'toggle',
				__( 'Show Time', 'nwscds' ),
				__( 'Show Time', 'nwscds' ),
				true
			);

			$this->addControl(
				'show_taxonomy',
				'toggle',
				__( 'Show Taxonomy', 'nwscds' ),
				__( 'Show Taxonomy', 'nwscds' ),
				true
			);

			foreach( $post_types as $k ) {

				$choices = array();
				$first = '';

				$post_taxonomies = get_object_taxonomies( $k->name );

				foreach( $post_taxonomies as $v ) {

					if ( !isset( $first ) ) {
						$first = $v;
					}

					$choices[] = array(
						'value' => $v,
						'label' => $v
					);
				}

				$this->addControl(
					'which_taxonomy_' . $k->name,
					'select',
					__( 'Which Taxonomy?', 'nwscds' ),
					__( 'Which Taxonomy?', 'nwscds' ),
					$first,
					array(
						'choices' => $choices,
						'condition' => array(
							'post_type' => $k->name,
							'show_taxonomy' => true
						)
					)
				);

			}

			$this->addControl(
				'show_author',
				'toggle',
				__( 'Show Author', 'nwscds' ),
				__( 'Show Author', 'nwscds' ),
				true
			);

			$this->addControl(
				'show_format',
				'toggle',
				__( 'Show Format', 'nwscds' ),
				__( 'Show Format', 'nwscds' ),
				true
			);

			$this->addControl(
				'excerpt_length',
				'number',
				__( 'Excerpt Length', 'nwscds' ),
				__( 'Excerpt Length', 'nwscds' ),
				20,
				array(
					'condition' => array(
						'type:not' => array( 'news-list-compact', 'news-marquee' )
					)
				)
			);

			$this->addControl(
				'excerpt_more',
				'textarea',
				__( 'Excerpt More', 'nwscds' ),
				__( 'Excerpt More', 'nwscds' ),
				'',
				array(
					'expandable' => __( 'Content', 'nwscds' ),
					'condition' => array(
						'type:not' => array( 'news-list-compact', 'news-marquee' )
					)
				)
			);

			$this->addControl(
				'pagination',
				'toggle',
				__( 'Pagination', 'nwscds' ),
				__( 'Pagination', 'nwscds' ),
				true
			);

			$this->addControl(
				'ajax',
				'toggle',
				__( 'AJAX', 'nwscds' ),
				__( 'AJAX', 'nwscds' ),
				true,
				array(
					'condition' => array(
						'pagination' => true
					)
				)
			);

			$this->addControl(
				'load_more',
				'toggle',
				__( 'Load More', 'nwscds' ),
				__( 'Load More', 'nwscds' ),
				false,
				array(
					'condition' => array(
						'pagination' => true,
						'ajax' => true
					)
				)
			);

			$this->addControl(
				'ticker_visible',
				'number',
				__( 'Visible In Ticker', 'nwscds' ),
				__( 'Visible In Ticker', 'nwscds' ),
				3,
				array(
					'condition' => array(
						'type' => array( 'news-ticker', 'news-ticker-compact', 'news-ticker-tiny' )
					)
				)
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

			$this->addControl(
				'ticker_direction',
				'select',
				__( 'Ticker Direction', 'nwscds' ),
				__( 'Ticker Direction', 'nwscds' ),
				'up',
				array(
					'choices' => $choices,
					'condition' => array(
						'type' => array( 'news-ticker', 'news-ticker-compact', 'news-ticker-tiny' )
					)
				)
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

			$this->addControl(
				'marquee_direction',
				'select',
				__( 'Marquee Direction', 'nwscds' ),
				__( 'Marquee Direction', 'nwscds' ),
				'left',
				array(
					'choices' => $choices,
					'condition' => array(
						'type' => array( 'news-marquee' )
					)
				)
			);

		}

		public function render( $atts ) {

			$valid = NC_Setup::$newscodes['atts'];

			$pass = array();

			if ( $atts['excerpt_more'] !== '' && strpos( $atts['excerpt_more'], '"' ) ) {
				$atts['excerpt_more'] = str_replace( '"', "'", $atts['excerpt_more'] );
			}

			if ( ( $tax = $atts['which_taxonomy_' . $atts['post_type']] ) !== '' && taxonomy_exists( $tax ) ) {
				$pass[] = ' which_taxonomy="' . $tax . '"';
			}

			foreach( $atts as $k => $v ) {
				if ( array_key_exists( $k, $valid ) && !empty( $v ) ) {
					$pass[] = ' ' . $k . '="' . $v . '"';
				}
				else if ( array_key_exists( $k, $valid ) && !empty( $valid[$k] ) ) {
					$pass[] = ' ' . $k . '="' . $valid[$k] . '"';
				}
			}

			$elements = isset( $atts['elements'] ) ? $atts['elements'] : array();

			if ( is_array( $elements ) && !empty( $elements ) ) {

				$add_taxonomies = array();
				$add_terms = array();

				$add_keys = array();
				$add_values = array();
				$add_compares = array();
				$add_types = array();

				foreach( $elements as $elk => $el ) {

					if ( $el['_type'] == 'newscodes-filters' ) {

						if ( $el['filter_type'] == 'taxonomy' ) {
							$add_taxonomies[] = $el[$atts['post_type'] . '_taxonomy'];
							$add_terms[] = isset( $el[$el[$atts['post_type'] . '_taxonomy'] . '_terms'] ) ? $el[$el[$atts['post_type'] . '_taxonomy'] . '_terms'] : '-1';
						}
						else if ( $el['filter_type'] == 'meta' ) {
							$add_keys[] = isset( $el['key'] ) ? $el['key'] : '';
							$add_values[] = isset( $el['value'] ) ? $el['value'] : '';
							$add_compares[] = isset( $el['compare'] ) ? $el['compare'] : '';
							$add_types[] = isset( $el['type'] ) ? $el['type'] : '';
						}
					}

				}

				if ( !empty( $add_taxonomies ) ) {
					$pass[] = ' filters="' . implode( $add_taxonomies, '|' ) . '"';
					$pass[] = ' filter_terms="' . implode( $add_terms, '|' ) . '"';
				}

				if ( !empty( $add_keys ) ) {
					$pass[] = ' meta_keys="' . implode( $add_keys, '|' ) . '"';
					$pass[] = ' meta_values="' . implode( $add_values, '|' ) . '"';
					$pass[] = ' meta_compares="' . implode( $add_compares, '|' ) . '"';
					$pass[] = ' meta_types="' . implode( $add_types, '|' ) . '"';
				}


			}

			$shortcode = '[nc_factory' . implode( $pass, '' ) . ']';

			return $shortcode;

		}

	}


	class NewsCodesCornerstoneFilters extends Cornerstone_Element_Base {

		public function data() {
			return array(
				'name'        => 'newscodes-filters',
				'title'       => __( 'Post Filter', 'nwscds' ),
				'description' => __( 'Post Filter', 'nwscds' ),
				'render'      => true,
				'delegate'    => true
			);
		}

		public function controls() {

			$choices = array(
				array(
					'value' => 'taxonomy',
					'label' => __( 'Taxonomy Filter', 'nwscds' )
				),
				array(
					'value' => 'meta',
					'label' => __( 'Meta Filter', 'nwscds' )
				),

			);

			$this->addControl(
				'filter_type',
				'select',
				__( 'Filter Type', 'nwscds' ),
				__( 'Filter Type', 'nwscds' ),
				'taxonomy',
				array(
					'choices' => $choices
				)
			);

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

				$this->addControl(
					$post_type->name . '_taxonomy',
					'select',
					__( 'Taxonomy', 'nwscds' ),
					__( 'Taxonomy', 'nwscds' ),
					'',
					array(
						'choices' => $choices,
						'condition' => array(
							'filter_type' => 'taxonomy',
							'parent:post_type' => $post_type->name
						)
					)
				);

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
					}

					$this->addControl(
						$v . '_terms',
						'select',
						__( 'Terms', 'nwscds' ),
						__( 'Terms', 'nwscds' ),
						'',
						array(
							'choices' => $choices,
							'condition' => array(
								'filter_type' => 'taxonomy',
								$post_type->name . '_taxonomy' => $v,
								'parent:post_type' => $post_type->name
							)
						)
					);
				}

			}

			$this->addControl(
				'key',
				'text',
				__( 'Key', 'nwscds' ),
				__( 'Key', 'nwscds' ),
				'',
				array(
					'condition' => array(
						'filter_type' => 'meta'
					)
				)
			);

			$this->addControl(
				'value',
				'text',
				__( 'Value', 'nwscds' ),
				__( 'Value', 'nwscds' ),
				'',
				array(
					'condition' => array(
						'filter_type' => 'meta'
					)
				)
			);

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

			$this->addControl(
				'compare',
				'select',
				__( 'Compare', 'nwscds' ),
				__( 'Compare', 'nwscds' ),
				'=',
				array(
					'choices' => $choices,
					'condition' => array(
						'filter_type' => 'meta'
					)
				)
			);

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

			$this->addControl(
				'type',
				'select',
				__( 'Type', 'nwscds' ),
				__( 'Type', 'nwscds' ),
				'CHAR',
				array(
					'choices' => $choices,
					'condition' => array(
						'filter_type' => 'meta'
					)
				)
			);


		}

	}

	cornerstone_add_element( 'NewsCodesCornerstoneFilters' );
	cornerstone_add_element( 'NewsCodesCornerstoneMetaFilters' );
	cornerstone_add_element( 'NewsCodesCornerstone' );
	cornerstone_add_element( 'NewsCodesCornerstoneMultiHelper' );
	cornerstone_add_element( 'NewsCodesCornerstoneMulti' );

?>