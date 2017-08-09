<?php

	if ( ! function_exists( 'nc_get_template_part' ) ) {
		function nc_get_template_part( $slug, $name = '' ) {
			$template = '';

			if ( $name ) {
				$template = locate_template(
					array(
						"{$slug}/{$name}.php",
						NC()->template_path() . "/{$slug}/{$name}.php"
					)
				);
			}

			if ( !$template && $name && file_exists( NC()->plugin_path() . "/templates/{$slug}/{$name}.php" ) ) {
				$template = NC()->plugin_path() . "/templates/{$slug}/{$name}.php";
			}

			if ( ( !$template ) || $template ) {
				$template = apply_filters( 'nc_get_template_part', $template, $slug, $name );
			}

			if ( $template ) {
				load_template( $template, false );
			}
		}
	}

	if ( ! function_exists( 'nc_get_template' ) ) {
		function nc_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
			if ( $args && is_array( $args ) ) {
				extract( $args );
			}

			$located = nc_locate_template( $template_name, $template_path, $default_path );

			if ( ! file_exists( $located ) ) {
				_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '2.1' );
				return;
			}

			$located = apply_filters( 'nc_get_template', $located, $template_name, $args, $template_path, $default_path );

			do_action( 'newscodes_before_template_part', $template_name, $template_path, $located, $args );

			include( $located );

			do_action( 'newscodes_after_template_part', $template_name, $template_path, $located, $args );
		}
	}

	if ( ! function_exists( 'nc_locate_template' ) ) {
		function nc_locate_template( $template_name, $template_path = '', $default_path = '' ) {
			if ( ! $template_path ) {
				$template_path = NC()->template_path();
			}

			if ( ! $default_path ) {
				$default_path = NC()->plugin_path() . '/templates/';
			}

			$template = locate_template(
				array(
					trailingslashit( $template_path ) . $template_name,
					$template_name
				)
			);

			if ( ! $template || WC_TEMPLATE_DEBUG_MODE ) {
				$template = $default_path . $template_name;
			}

			return apply_filters( 'newscodes_locate_template', $template, $template_name, $template_path );
		}
	}

	if ( ! function_exists( 'nc_post_class' ) ) {
		function nc_post_class( $class = '', $post_id = null ) {
			echo 'class="' . join( ' ', nc_get_post_class( $class, $post_id ) ) . '"';
		}
	}

	if ( ! function_exists( 'nc_get_post_class' ) ) {
		function nc_get_post_class( $class = '', $post_id = null ) {
			$post = get_post( $post_id );

			$classes = array();

			if ( $class ) {
				if ( ! is_array( $class ) ) {
					$class = preg_split( '#\s+#', $class );
				}
				$classes = array_map( 'esc_attr', $class );
			}

			if ( ! $post ) {
				return $classes;
			}

			$classes[] = 'nc-post-' . $post->ID;
			$classes[] = 'nc-' . $post->post_type;
			$classes[] = 'nc-type-' . $post->post_type;
			$classes[] = 'nc-status-' . $post->post_status;

			if ( post_type_supports( $post->post_type, 'post-formats' ) ) {
				$post_format = get_post_format( $post->ID );

				if ( $post_format && !is_wp_error($post_format) )
					$classes[] = 'nc-format-' . sanitize_html_class( $post_format );
				else
					$classes[] = 'nc-format-standard';
			}

			if ( post_password_required( $post->ID ) ) {
				$classes[] = 'nc-post-password-required';
			} elseif ( ! is_attachment( $post ) && current_theme_supports( 'post-thumbnails' ) && has_post_thumbnail( $post->ID ) ) {
				$classes[] = 'nc-has-post-thumbnail';
			}

			if ( is_sticky( $post->ID ) ) {
				if ( is_home() && ! is_paged() ) {
					$classes[] = 'nc-sticky';
				} elseif ( is_admin() ) {
					$classes[] = 'nc-status-sticky';
				}
			}

			$classes[] = 'nc-hentry';

			$taxonomies = get_taxonomies( array( 'public' => true ) );
			foreach ( (array) $taxonomies as $taxonomy ) {
				if ( is_object_in_taxonomy( $post->post_type, $taxonomy ) ) {
					foreach ( (array) get_the_terms( $post->ID, $taxonomy ) as $term ) {
						if ( empty( $term->slug ) ) {
							continue;
						}

						$term_class = sanitize_html_class( $term->slug, $term->term_id );
						if ( is_numeric( $term_class ) || ! trim( $term_class, '-' ) ) {
							$term_class = $term->term_id;
						}

						if ( 'post_tag' == $taxonomy ) {
							$classes[] = 'tag-' . $term_class;
						} else {
							$classes[] = sanitize_html_class( $taxonomy . '-' . $term_class, $taxonomy . '-' . $term->term_id );
						}
					}
				}
			}

			$classes = array_map( 'esc_attr', $classes );

			$classes = apply_filters( 'newscodes_post_class', $classes, $class, $post->ID );

			return array_unique( $classes );
		}
	}

	if ( ! function_exists( 'nc_post_thumbnail' ) ) {
		function nc_post_thumbnail( $size = 'large' ) {

			global $newscodes_loop;

			if ( has_post_thumbnail() ) {
				$thumb_id = get_post_thumbnail_id();
			}
			
			if ( !isset( $thumb_id ) ) {
				$cache = wp_get_attachment_image_src( get_the_ID(), $size );
				if ( !empty( $cache ) ) {
					$thumb_id = get_the_ID();
				}
			}

			if ( isset( $thumb_id ) ) {

				$size_ratio = NC_Shortcodes::$curr_instance['atts']['image_ratio'];
				$size_override = NC_Shortcodes::$curr_instance['atts']['image_size'];
				$size = $size_override == '' ? $size : $size_override;

				$set = isset( $cache ) ? $cache : wp_get_attachment_image_src( $thumb_id, $size, true );
				$url = $set[0];

				$w = $set[1];
				$h = $set[2];

				$ratio = explode( '-', $size_ratio );

				$x = isset( $ratio[0] ) ? $ratio[0] : 4;
				$y = isset( $ratio[1] ) ? $ratio[1] : 3;

				if ( in_array( $size_ratio, array( '2-1', '1-2', '3-1', '1-3' ) ) ) {
					$orientation = $w/$h > $x/$y ? 'nc-figure-y' : 'nc-figure-x';
				}
				else {
					$orientation = $w/$h > $x/$y ? 'nc-figure-x' : 'nc-figure-y';
				}

				$class = 'class="nc-figure nc-image-ratio-' . $size_ratio . ' ' . $orientation . '"';

			?>
				<div class="nc-figure-wrapper">
					<figure <?php echo $class;?> style="background-image:url(<?php echo $url; ?>);">
						<a href="<?php the_permalink(); ?>"></a>
					</figure>
				</div>
			<?php

			}
			else if ( in_array( 'nc-featured', $newscodes_loop['classes'] ) ) {

				$size_ratio = NC_Shortcodes::$curr_instance['atts']['image_ratio'];

			?>
				<div class="nc-figure-wrapper">
					<figure class="nc-figure nc-empty-figure nc-image-ratio-<?php echo $size_ratio . ' nc-figure-x'; ?>">
						<a href="<?php the_permalink(); ?>"></a>
					</figure>
				</div>
			<?php

			}

		}
	}

	if ( ! function_exists( 'nc_post_format' ) ) {
		function nc_post_format() {

			$format = get_post_format();

			if ( false === $format || 'standard' == $format ) {
				echo __( 'Text', 'nwscds' );
			}
			else {
				echo get_post_format_string( $format );
			}

		}
	}

	if ( ! function_exists( 'nc_title_tag' ) ) {
		function nc_title_tag( $tag = 'h2' ) {

			if ( in_array( $tag, array( 'h1','h2','h3','h4','h5','h6' ) ) ) {
				$output = $tag;
			}
			else {
				$output = 'h2';
			}
			echo apply_filters( 'newscodes_title', $output, $tag );

		}
	}

?>