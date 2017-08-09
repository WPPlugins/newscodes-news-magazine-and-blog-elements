<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class NC_Admin_Controls {

		public static function typography( $args = array() ) {

			extract( $args );

			$has_desc = $field_desc ? true : false;

			echo '<div class="format-setting type-typography ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

				echo '<div class="description">';

					echo '<strong>' . $field_label . '</strong>' . ( $has_desc ? ' - <em class="">' . htmlspecialchars_decode( $field_desc ) . '</em>' : '' );

				echo '</div>';

				echo '<div class="format-setting-inner">';

					$nc_recognized_typography_fields = apply_filters( 'nc_recognized_typography_fields', array(
						'font-color',
						'font-family',
						'font-size',
						'font-style',
						'font-variant',
						'font-weight',
						'letter-spacing',
						'line-height',
						'text-decoration',
						'text-transform',
						'text-align'
					), $field_id );

					if ( in_array( 'font-color', $nc_recognized_typography_fields ) ) {

						echo '<div class="newscodes-ui-colorpicker-input-wrap">';

							$background_color = isset( $field_value['font-color'] ) ? esc_attr( $field_value['font-color'] ) : $field_color;

							$std = $field_color ? 'data-default-color="' . $field_color . '"' : '';

							echo '<input type="text" name="' . esc_attr( $field_name ) . '[font-color]" id="' . esc_attr( $field_id ) . '-picker" value="' . esc_attr( $background_color ) . '" class="hide-color-picker ' . esc_attr( $field_class ) . '" ' . $std . ' />';

						echo '</div>';

					}

					if ( in_array( 'font-family', $nc_recognized_typography_fields ) ) {

						$font_family = isset( $field_value['font-family'] ) ? $field_value['font-family'] : '';
						echo '<select name="' . esc_attr( $field_name ) . '[font-family]" id="' . esc_attr( $field_id ) . '-font-family" class="newscodes-ui-select ' . esc_attr( $field_class ) . '">';
							echo '<option value="">font-family</option>';
							foreach ( self::nc_recognized_font_families( $field_id ) as $key => $value ) {
								echo '<option value="' . esc_attr( $key ) . '" ' . selected( $font_family, $key, false ) . '>' . esc_attr( $value ) . '</option>';
							}
						echo '</select>';

					}

					if ( in_array( 'font-size', $nc_recognized_typography_fields ) ) {

						$font_size = isset( $field_value['font-size'] ) ? esc_attr( $field_value['font-size'] ) : '';
						echo '<select name="' . esc_attr( $field_name ) . '[font-size]" id="' . esc_attr( $field_id ) . '-font-size" class="newscodes-ui-select ' . esc_attr( $field_class ) . '">';
							echo '<option value="">font-size</option>';
							foreach( self::nc_recognized_font_sizes( $field_id ) as $option ) {
								echo '<option value="' . esc_attr( $option ) . '" ' . selected( $font_size, $option, false ) . '>' . esc_attr( $option ) . '</option>';
							}
						echo '</select>';

					}

					if ( in_array( 'font-style', $nc_recognized_typography_fields ) ) {

						$font_style = isset( $field_value['font-style'] ) ? esc_attr( $field_value['font-style'] ) : '';
						echo '<select name="' . esc_attr( $field_name ) . '[font-style]" id="' . esc_attr( $field_id ) . '-font-style" class="newscodes-ui-select ' . esc_attr( $field_class ) . '">';
							echo '<option value="">font-style</option>';
							foreach ( self::nc_recognized_font_styles( $field_id ) as $key => $value ) {
								echo '<option value="' . esc_attr( $key ) . '" ' . selected( $font_style, $key, false ) . '>' . esc_attr( $value ) . '</option>';
							}
						echo '</select>';

					}

					if ( in_array( 'font-variant', $nc_recognized_typography_fields ) ) {

						$font_variant = isset( $field_value['font-variant'] ) ? esc_attr( $field_value['font-variant'] ) : '';
						echo '<select name="' . esc_attr( $field_name ) . '[font-variant]" id="' . esc_attr( $field_id ) . '-font-variant" class="newscodes-ui-select ' . esc_attr( $field_class ) . '">';
							echo '<option value="">font-variant</option>';
							foreach ( self::nc_recognized_font_variants( $field_id ) as $key => $value ) {
								echo '<option value="' . esc_attr( $key ) . '" ' . selected( $font_variant, $key, false ) . '>' . esc_attr( $value ) . '</option>';
							}
						echo '</select>';

					}

					if ( in_array( 'font-weight', $nc_recognized_typography_fields ) ) {

						$font_weight = isset( $field_value['font-weight'] ) ? esc_attr( $field_value['font-weight'] ) : '';
						echo '<select name="' . esc_attr( $field_name ) . '[font-weight]" id="' . esc_attr( $field_id ) . '-font-weight" class="newscodes-ui-select ' . esc_attr( $field_class ) . '">';
							echo '<option value="">font-weight</option>';
							foreach ( self::nc_recognized_font_weights( $field_id ) as $key => $value ) {
								echo '<option value="' . esc_attr( $key ) . '" ' . selected( $font_weight, $key, false ) . '>' . esc_attr( $value ) . '</option>';
							}
						echo '</select>';

					}

					if ( in_array( 'letter-spacing', $nc_recognized_typography_fields ) ) {

						$letter_spacing = isset( $field_value['letter-spacing'] ) ? esc_attr( $field_value['letter-spacing'] ) : '';
						echo '<select name="' . esc_attr( $field_name ) . '[letter-spacing]" id="' . esc_attr( $field_id ) . '-letter-spacing" class="newscodes-ui-select ' . esc_attr( $field_class ) . '">';
							echo '<option value="">letter-spacing</option>';
							foreach( self::nc_recognized_letter_spacing( $field_id ) as $option ) {
								echo '<option value="' . esc_attr( $option ) . '" ' . selected( $letter_spacing, $option, false ) . '>' . esc_attr( $option ) . '</option>';
							}
						echo '</select>';

					}

					if ( in_array( 'line-height', $nc_recognized_typography_fields ) ) {

						$line_height = isset( $field_value['line-height'] ) ? esc_attr( $field_value['line-height'] ) : '';
						echo '<select name="' . esc_attr( $field_name ) . '[line-height]" id="' . esc_attr( $field_id ) . '-line-height" class="newscodes-ui-select ' . esc_attr( $field_class ) . '">';
							echo '<option value="">line-height</option>';
							foreach( self::nc_recognized_line_heights( $field_id ) as $option ) {
								echo '<option value="' . esc_attr( $option ) . '" ' . selected( $line_height, $option, false ) . '>' . esc_attr( $option ) . '</option>';
							}
						echo '</select>';
					}

					if ( in_array( 'text-decoration', $nc_recognized_typography_fields ) ) {

						$text_decoration = isset( $field_value['text-decoration'] ) ? esc_attr( $field_value['text-decoration'] ) : '';
						echo '<select name="' . esc_attr( $field_name ) . '[text-decoration]" id="' . esc_attr( $field_id ) . '-text-decoration" class="newscodes-ui-select ' . esc_attr( $field_class ) . '">';
							echo '<option value="">text-decoration</option>';
							foreach ( self::nc_recognized_text_decorations( $field_id ) as $key => $value ) {
								echo '<option value="' . esc_attr( $key ) . '" ' . selected( $text_decoration, $key, false ) . '>' . esc_attr( $value ) . '</option>';
							}
						echo '</select>';

					}

					if ( in_array( 'text-transform', $nc_recognized_typography_fields ) ) {

						$text_transform = isset( $field_value['text-transform'] ) ? esc_attr( $field_value['text-transform'] ) : '';
						echo '<select name="' . esc_attr( $field_name ) . '[text-transform]" id="' . esc_attr( $field_id ) . '-text-transform" class="newscodes-ui-select ' . esc_attr( $field_class ) . '">';
							echo '<option value="">text-transform</option>';
							foreach ( self::nc_recognized_text_transformations( $field_id ) as $key => $value ) {
								echo '<option value="' . esc_attr( $key ) . '" ' . selected( $text_transform, $key, false ) . '>' . esc_attr( $value ) . '</option>';
							}
						echo '</select>';

					}

					if ( in_array( 'text-align', $nc_recognized_typography_fields ) ) {

						$text_transform = isset( $field_value['text-align'] ) ? esc_attr( $field_value['text-align'] ) : '';
						echo '<select name="' . esc_attr( $field_name ) . '[text-align]" id="' . esc_attr( $field_id ) . '-text-align" class="newscodes-ui-select ' . esc_attr( $field_class ) . '">';
							echo '<option value="">text-align</option>';
							foreach ( self::nc_recognized_text_aligns( $field_id ) as $key => $value ) {
								echo '<option value="' . esc_attr( $key ) . '" ' . selected( $text_transform, $key, false ) . '>' . esc_attr( $value ) . '</option>';
							}
						echo '</select>';

					}

				echo '</div>';

			echo '</div>';

		}

		public static function text( $args = array() ) {

			extract( $args );

			$has_desc = $field_desc ? true : false;

			echo '<div class="format-setting type-text ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

				echo '<div class="description">';

					echo '<strong>' . $field_label . '</strong>' . ( $has_desc ? ' - <em class="">' . htmlspecialchars_decode( $field_desc ) . '</em>' : '' );

				echo '</div>';

				echo '<div class="format-setting-inner">';

					echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="widefat newscodes-ui-input ' . esc_attr( $field_class ) . '" />';

				echo '</div>';

			echo '</div>';

		}

		public static function filters( $args = array() ) {

			extract( $args );

			$has_desc = $field_desc ? true : false;

			echo '<div class="format-setting type-filters ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

				echo '<div class="description">';

					echo '<strong>' . $field_label . '</strong>' . ( $has_desc ? ' - <em class="">' . htmlspecialchars_decode( $field_desc ) . '</em>' : '' );

				echo '</div>';

				echo '<div class="format-setting-inner">';

					echo '<div id="nc-composer-filters">';

						echo '<input id="nc-filter-manager-json" name="nc-filter-manager-json" type="text" />';

						echo '<div id="nc-composer-filters-navigation">';

							echo '<span id="nc-add-filter" class="button">' . __( 'Add Filter', 'nwscds' ) . '</span>';
							echo '<span id="nc-update-filters" class="button-primary">' . __( 'Update Filters', 'nwscds' ) . '</span>';

						echo '</div>';

						echo '<div id="nc-composer-filters-default">';

							echo '<span id="nc-remove-filter" class="button">' . __( 'Remove Filter', 'nwscds' ) . '</span>';

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

							echo '<select class="nc-filter-settings-collect nc-type" data-param="type">';

								foreach( $choices as $choice ) {
									echo '<option value="' . $choice['value'] . '">' . $choice['label'] . '</option>';
								}

							echo '</select>';

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

								echo '<select class="nc-filter-settings-collect type_taxonomy nc-taxonomy" data-param="post_type_' . esc_attr( $post_type->name ) . '">';

									foreach( $choices as $choice ) {
										echo '<option value="' . $choice['value'] . '">' . $choice['label'] . '</option>';
									}

								echo '</select>';

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

										echo '<select class="nc-filter-settings-collect type_taxonomy nc-taxonomy-terms" data-param="taxonomy_' . esc_attr( $v ) . '">';

											foreach( $choices as $choice ) {
												echo '<option value="' . $choice['value'] . '">' . $choice['label'] . '</option>';
											}

										echo '</select>';

									}

								}

							}

							echo '<input class="nc-filter-settings-collect type_meta" type="text" data-param="meta_key" />';

							echo '<input class="nc-filter-settings-collect type_meta" type="text" data-param="meta_value" />';

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

							echo '<select class="nc-filter-settings-collect type_meta" data-param="meta_compare">';

								foreach( $choices as $choice ) {
									echo '<option value="' . $choice['value'] . '">' . $choice['label'] . '</option>';
								}

							echo '</select>';

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

							echo '<select class="nc-filter-settings-collect type_meta" data-param="meta_type">';

								foreach( $choices as $choice ) {
									echo '<option value="' . $choice['value'] . '">' . $choice['label'] . '</option>';
								}

							echo '</select>';

						echo '</div>';

						echo '<div id="nc-composer-filters-wrap">';

						echo '</div>';

					echo '</div>';

				echo '</div>';

			echo '</div>';

		}

		public static function textarea( $args = array() ) {

			extract( $args );

			$has_desc = $field_desc ? true : false;

			echo '<div class="format-setting type-textarea ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

				echo '<div class="description">';

					echo '<strong>' . $field_label . '</strong>' . ( $has_desc ? ' - <em class="">' . htmlspecialchars_decode( $field_desc ) . '</em>' : '' );

				echo '</div>';

				echo '<div class="format-setting-inner">';

					echo '<textarea name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="widefat newscodes-ui-textarea ' . esc_attr( $field_class ) . '">' . esc_attr( $field_value ) . '</textarea>';

				echo '</div>';

			echo '</div>';

		}

		public static function checkbox( $args = array() ) {

			extract( $args );

			$has_desc = $field_desc ? true : false;

			echo '<div class="format-setting type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

				echo '<div class="description">';

					echo '<strong>' . $field_label . '</strong>' . ( $has_desc ? ' - <em class="">' . htmlspecialchars_decode( $field_desc ) . '</em>' : '' );

				echo '</div>';

				echo '<div class="format-setting-inner">';

					echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '"  value="true" ' . ( isset( $field_value ) ? checked( $field_value, 'true', false ) : '' ) . ' class="newscodes-ui-checkbox ' . esc_attr( $field_class ) . '" />';

					echo '<label for="' . esc_attr( $field_id ) . '">' . esc_attr( $choice['label'] ) . '</label>';

				echo '</div>';

			echo '</div>';

		}


		public static function color( $args = array() ) {

			extract( $args );

			$has_desc = $field_desc ? true : false;

			echo '<div class="format-setting type-colorpicker ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

				echo '<div class="description">';

					echo '<strong>' . $field_label . '</strong>' . ( $has_desc ? ' - <em class="">' . htmlspecialchars_decode( $field_desc ) . '</em>' : '' );

				echo '</div>';

				echo '<div class="format-setting-inner">';

					echo '<div class="newscodes-ui-colorpicker-input-wrap">';

						$std = $field_std ? 'data-default-color="' . $field_std . '"' : '';

						$value = 'value="' . ( isset( $field_value ) && $field_value !== '' ? esc_attr( $field_value ) : $field_std ) . '"';

						echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" ' . $value . ' class="hide-color-picker ' . esc_attr( $field_class ) . '" ' . $std . ' />';

					echo '</div>';

				echo '</div>';

			echo '</div>';

		}

		public static function select( $args = array() ) {

			extract( $args );

			$has_desc = $field_desc ? true : false;

			echo '<div class="format-setting type-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

				echo '<div class="description">';

					echo '<strong>' . $field_label . '</strong>' . ( $has_desc ? ' - <em class="">' . htmlspecialchars_decode( $field_desc ) . '</em>' : '' );

				echo '</div>';

				$field_choices = apply_filters( 'nc_type_select_choices', $field_choices, $field_id );

				echo '<div class="format-setting-inner">';

					echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="newscodes-ui-select ' . esc_attr( $field_class ) . '">';

						foreach ( (array) $field_choices as $choice ) {
							if ( isset( $choice['value'] ) && isset( $choice['label'] ) ) {
								echo '<option value="' . esc_attr( $choice['value'] ) . '"' . selected( $field_value, $choice['value'], false ) . '>' . esc_attr( $choice['label'] ) . '</option>';
							}
						}

					echo '</select>';

				echo '</div>';

			echo '</div>';

		}

		public static function style( $args = array() ) {

			extract( $args );

			$styles = apply_filters( 'nc_supported_styles', NC_Admin::$styles );
			$field_choices = array();
			if ( !empty( $styles ) ) {
				foreach( $styles as $slug => $style ) {
					if ( $style['styles'] && is_array( $style['styles'] ) ) {
						foreach( $style['styles'] as $sub_slug => $sub_settings ) {
							$field_choices[] = array( 'label' => $sub_settings['name'], 'value' => $sub_slug, 'group' => $slug );
						}
					}
				}
			}

			$has_desc = $field_desc ? true : false;

			echo '<div class="format-setting type-style ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

				echo '<div class="description">';

					echo '<strong>' . $field_label . '</strong>' . ( $has_desc ? ' - <em class="">' . htmlspecialchars_decode( $field_desc ) . '</em>' : '' );

				echo '</div>';

				$field_choices = apply_filters( 'nc_type_style_choices', $field_choices, $field_id );

				echo '<div class="format-setting-inner">';

					echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="newscodes-ui-select ' . esc_attr( $field_class ) . '">';

						echo '<option value="" data-group="">' . __( 'Default', 'nwscds' ) . '</option>';

						foreach ( (array) $field_choices as $choice ) {
							if ( isset( $choice['value'] ) && isset( $choice['label'] ) ) {
								echo '<option value="' . esc_attr( $choice['value'] ) . '"' . selected( $field_value, $choice['value'], false ) . ' data-group="' . $choice['group'] . '">' . esc_attr( $choice['label'] ) . '</option>';
							}
						}

					echo '</select>';

				echo '</div>';

			echo '</div>';

		}

		public static function padding( $args = array() ) {

			extract( $args );

			$has_desc = $field_desc ? true : false;

			echo '<div class="format-setting type-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

				echo '<div class="description">';

					echo '<strong>' . $field_label . '</strong>' . ( $has_desc ? ' - <em class="">' . htmlspecialchars_decode( $field_desc ) . '</em>' : '' );

				echo '</div>';

				echo '<div class="format-setting-inner">';

					echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="newscodes-ui-select ' . esc_attr( $field_class ) . '">';

						echo '<option value="default"' . selected( $field_value, '', false ) . '>' . __( 'Default', 'nwscds' ) . '</option>';

						foreach ( self::nc_recognized_paddings( $field_id ) as $option ) {
							echo '<option value="' . esc_attr( $option ) . '"' . selected( $field_value, $option, false ) . '>' . esc_attr( $option ) . '</option>';
						}

					echo '</select>';

				echo '</div>';

			echo '</div>';

		}

		public static function nc_range( $start, $limit, $step = 1 ) {

			if ( $step < 0 ) {
				$step = 1;
			}

			$range = range( $start, $limit, $step );

			foreach( $range as $k => $v ) {
				if ( strpos( $v, 'E' ) ) {
					$range[$k] = 0;
				}
			}

			return $range;
		}


		public static function nc_recognized_font_families( $field_id = '' ) {

			$families = NC_Admin::font_families();

			return apply_filters( 'nc_recognized_font_families', $families, $field_id );

		}


		public static function nc_recognized_font_sizes( $field_id ) {

			$range = self::nc_range(
				apply_filters( 'nc_font_size_low_range', 12, $field_id ),
				apply_filters( 'nc_font_size_high_range', 150, $field_id ),
				apply_filters( 'nc_font_size_range_interval', 1, $field_id )
			);

			$unit = apply_filters( 'nc_font_size_unit_type', 'px', $field_id );

			foreach( $range as $k => $v ) {
				$range[$k] = $v . $unit;
			}

			return apply_filters( 'nc_recognized_font_sizes', $range, $field_id );
		}


		public static function nc_recognized_font_styles( $field_id = '' ) {

			return apply_filters( 'nc_recognized_font_styles', array(
				'normal'  => 'Normal',
				'italic'  => 'Italic',
				'oblique' => 'Oblique',
				'inherit' => 'Inherit'
			), $field_id );

		}


		public static function nc_recognized_font_variants( $field_id = '' ) {

			return apply_filters( 'nc_recognized_font_variants', array(
				'normal'      => 'Normal',
				'small-caps'  => 'Small Caps',
				'inherit'     => 'Inherit'
			), $field_id );

		}


		public static function nc_recognized_font_weights( $field_id = '' ) {

			return apply_filters( 'nc_recognized_font_weights', array(
				'normal'    => 'Normal',
				'bold'      => 'Bold',
				'bolder'    => 'Bolder',
				'lighter'   => 'Lighter',
				'100'       => '100',
				'200'       => '200',
				'300'       => '300',
				'400'       => '400',
				'500'       => '500',
				'600'       => '600',
				'700'       => '700',
				'800'       => '800',
				'900'       => '900',
				'inherit'   => 'Inherit'
			), $field_id );

		}

		public static function nc_recognized_text_aligns( $field_id = '' ) {

			return apply_filters( 'nc_recognized_text_aligns', array(
				'left'      => 'Left',
				'right'     => 'Right',
				'center'    => 'Center',
				'justify'   => 'Justify',
				'inherit'   => 'Inherit'
			), $field_id );

		}


		public static function nc_recognized_letter_spacing( $field_id ) {

			$range = self::nc_range(
				apply_filters( 'nc_letter_spacing_low_range', -0.15, $field_id ),
				apply_filters( 'nc_letter_spacing_high_range', 0.15, $field_id ),
				apply_filters( 'nc_letter_spacing_range_interval', 0.01, $field_id )
			);

			$unit = apply_filters( 'nc_letter_spacing_unit_type', 'em', $field_id );

			foreach( $range as $k => $v ) {
				$range[$k] = $v . $unit;
			}

			return apply_filters( 'nc_recognized_letter_spacing', $range, $field_id );
		}

		public static function nc_recognized_line_heights( $field_id ) {

			$range = self::nc_range(
				apply_filters( 'nc_line_height_low_range', 12, $field_id ),
				apply_filters( 'nc_line_height_high_range', 150, $field_id ),
				apply_filters( 'nc_line_height_range_interval', 1, $field_id )
			);

			$unit = apply_filters( 'nc_line_height_unit_type', 'px', $field_id );

			foreach( $range as $k => $v ) {
				$range[$k] = $v . $unit;
			}

			return apply_filters( 'nc_recognized_line_heights', $range, $field_id );
		}

		public static function nc_recognized_text_decorations( $field_id = '' ) {

			return apply_filters( 'nc_recognized_text_decorations', array(
				'blink'         => 'Blink',
				'inherit'       => 'Inherit',
				'line-through'  => 'Line Through',
				'none'          => 'None',
				'overline'      => 'Overline',
				'underline'     => 'Underline'
			), $field_id );

		}


		public static function nc_recognized_text_transformations( $field_id = '' ) {

			return apply_filters( 'nc_recognized_text_transformations', array(
				'capitalize'  => 'Capitalize',
				'inherit'     => 'Inherit',
				'lowercase'   => 'Lowercase',
				'none'        => 'None',
				'uppercase'   => 'Uppercase'
			), $field_id );

		}

		public static function nc_recognized_paddings( $field_id ) {

			$range = self::nc_range(
				apply_filters( 'nc_paddings_low_range', 0, $field_id ),
				apply_filters( 'nc_paddings_high_range', 100, $field_id ),
				apply_filters( 'nc_paddings_range_interval', 1, $field_id )
			);

			$unit = apply_filters( 'nc_paddings_unit_type', 'px', $field_id );

			foreach( $range as $k => $v ) {
				$range[$k] = $v . $unit;
			}

			return apply_filters( 'nc_recognized_paddings', $range, $field_id );
		}

	}