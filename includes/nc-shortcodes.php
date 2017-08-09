<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NC_Shortcodes {

	public static $instances;
	public static $curr_instance;
	public static $curr_section;
	public static $styles;

	public static function init() {
		$shortcodes = array(
			'newscodes' => __CLASS__ . '::generate',
			'nc_factory' => __CLASS__ . '::factory',
			'nc_multi_factory' => __CLASS__ . '::multi_factory',
			'nc_multi_factory_helper' => __CLASS__ . '::multi_factory_helper'
		);

		foreach ( $shortcodes as $shortcode => $function ) {

			add_shortcode( $shortcode, $function );

		}

		add_action( 'wp_ajax_nopriv_nc_ajax_factory', __CLASS__ . '::ajax_factory' );
		add_action( 'wp_ajax_nc_ajax_factory', __CLASS__ . '::ajax_factory' );

		add_action( 'newscodes_shortcode_pagination', __CLASS__ . '::pagination', 10 );

		add_filter( 'newscodes_shortcode_query', __CLASS__ . '::query_filter', 10, 2 );

		add_action( 'newscodes_shortcode_before_news_poster_loop', __CLASS__ . '::add_image_before_loop' );

	}

	private static function loop( $args, $atts, $loop_name ) {
		global $newscodes_loop, $nc_posts;

		$nc_posts = new WP_Query( ( $query = apply_filters( 'newscodes_shortcode_query', $args, $atts ) ) );
		self::$curr_instance['processed_query'] = $query;

		$newscodes_loop['loop'] = isset( $args['offset'] ) ? $args['offset'] : 0;
		$newscodes_loop['columns'] = 1;
		$class = ' columns-1 nc-type-' . $atts['type'];

		if ( in_array( $atts['type'], array( 'news-grid', 'news-grid-author' ) ) ) {
			$columns = isset( $atts['columns'] ) && $atts['columns'] !== '' ? absint( $atts['columns'] ) : 1;
			$range = range( 1, 6 );

			if ( !in_array( $columns, $range ) ) {
				$columns = 1;
			}

			$newscodes_loop['columns'] = $columns;
			$class = ' columns-' . $columns . ' nc-type-' . $atts['type'];
		}

		if ( $atts['type'] == 'news-poster' ) {
			$class .= ' nc-wrap-ratio-' . $atts['image_ratio'];
		}

		if ( $atts['title_cut'] == 'true' ) {
			$class .= ' nc-cut-titles';
		}

		if ( $atts['style'] !== '' ) {
			$class .= ' newscodes-style-' . $atts['style'];
		}
		else if ( $atts['style_cs'] !== '' ) {
			$class .= ' newscodes-style-' . $atts['style_cs'];
		}
		else {
			$class .= ' newscodes-style';
		}

		$newscodes_loop['ajax'] = $atts['ajax'];
		$newscodes_loop['title_tag'] = $atts['title_tag'];

		self::$instances[$atts['unique_id']]['args'] = $args;
		self::$instances[$atts['unique_id']]['atts'] = $atts;

		self::$curr_instance = self::$instances[$atts['unique_id']];

		ob_start();

		if ( $nc_posts->have_posts() ) : ?>

			<?php self::add_filters( $atts ); ?>

			<?php do_action( "newscodes_shortcode_before_{$loop_name}_loop" ); ?>

			<?php newscodes_loop_start(); ?>

				<?php

					while ( $nc_posts->have_posts() ) : $nc_posts->the_post();

						$newscodes_loop['loop']++;

						$newscodes_loop['classes'] = array( 'nc-regular' );

						if ( $newscodes_loop['columns'] !== 1 ) {
							if ( 0 == ( $newscodes_loop['loop'] - 1 ) % $newscodes_loop['columns'] || 1 == $newscodes_loop['columns'] ) {
								$newscodes_loop['classes'][] = 'first';
							}
							if ( 0 == $newscodes_loop['loop'] % $newscodes_loop['columns'] ) {
								$newscodes_loop['classes'][] = 'last';
							}
						}
						else if ( $newscodes_loop['loop'] == 1 ) {
							if ( strpos( $atts['type'], 'featured' ) > 0 || $atts['type'] == 'news-poster' ) {
								$newscodes_loop['classes'] = array( 'nc-featured' );
							}
						}

						$newscodes_loop['classes'][] = ( $newscodes_loop['loop'] % 2 == 0 ) ? 'nc-post-even' : 'nc-post-odd';

						nc_get_template_part( 'types', $atts['type'] );

						if ( strpos( $atts['type'], 'columned' ) > 0 && $newscodes_loop['loop'] == absint(  $atts['posts_per_column'] ) ) {
							newscodes_loop_end();
							newscodes_loop_start();
						}

					endwhile;

			?>

			<?php newscodes_loop_end(); ?>

			<?php do_action( "newscodes_shortcode_after_{$loop_name}_loop" ); ?>

			<?php do_action( 'newscodes_shortcode_pagination' ); ?>

			<?php self::remove_filters( $atts ); ?>

		<?php endif;

		self::reset_loop();
		wp_reset_postdata();

		return '<div id="' . $atts['unique_id'] . '" class="newscodes' . $class . '" data-ajax="' . $atts['ajax'] . '">' . ob_get_clean() . '</div>';
	}

	public static function reset_loop() {
		global $newscodes_loop;
		$newscodes_loop = array();

		self::$curr_instance = null;
	}

	public static function multi_factory( $atts, $content = null ) {
		$atts = shortcode_atts( NC_Setup::$newscodes['atts_multi'], $atts );

		self::$curr_section = null;

		$titles = '';
		$nav_class = array();

		if ( $atts['type'] !== '' ) {
			self::$curr_section['type'] = $atts['type'];
			$nav_class[] = 'nc-type-' . $atts['type'];
		}

		if ( $atts['style'] !== '' ) {
			self::$curr_section['style'] = $atts['style'];
			$nav_class[] = 'newscodes-style-' . $atts['style'];
		}
		else {
			$nav_class[] = 'newscodes-style';
		}

		$fetch = '<div class="nc-multi-tabs">' . do_shortcode( $content ) . '</div>';

		if ( isset( self::$curr_section['titles'] ) ) {

			$titles .= '<nav class="nc-multi-navigation ' . implode( $nav_class, ' ' ) . '">';

				$titles .= '<ul class="nc-multi-terms">';

					$i=1;
					foreach ( self::$curr_section['titles'] as $v ) {
						$titles .= '<li ' . ( $i == 1 ? 'class="current"' : '' ) . '>' . $v . '</li>';
						$i++;
					}

				$titles .= '</ul>';

			$titles .= '</nav>';

		}

		$html = '<div class="newscodes-multi">' . $titles . $fetch . '</div>';

		self::$curr_section = null;

		return $html;
	}

	public static function multi_factory_helper( $atts, $content = null ) {
		return self::factory( $atts, $content );
	}

	public static function generate( $atts, $content = null ) {

		$atts = shortcode_atts( array( 'id' => '' ) , $atts );
		$id = $atts['id'];

		if ( $id == '' ) {
			return __( 'Shortcode ID is not set.');
		}

		$shortcodes = get_option( 'newscodes_shortcodes', array() );
		if ( isset( $shortcodes[$id] ) && is_array( $shortcodes[$id] ) ) {
			$atts = $shortcodes[$id];
			return self::factory( $atts );
		}

	}

	public static function factory( $atts, $content = null ) {

		$atts = shortcode_atts( NC_Setup::$newscodes['atts'], $atts );

		if ( !array_key_exists( $atts['type'], NC_Setup::$newscodes['types'] ) ) {
			return;
		}

		if ( empty( $atts['unique_id'] ) ) {
			$atts['unique_id'] = uniqid( 'nc-post-' );
		}

		$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
		$offset = ( get_query_var( 'offset' ) ) ? absint( get_query_var( 'offset' ) ) : '';

		$args = array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'orderby'             => 'date',
			'order'               => 'desc',
			'posts_per_page'      => -1,
			'paged'               => $paged,
			'offset'              => $offset
		);

		if ( isset( self::$curr_section['type'] ) ) {
			$atts['type'] = self::$curr_section['type'];
		}

		if ( isset( self::$curr_section['style'] ) ) {
			$atts['style'] = self::$curr_section['style'];
		}
		if ( $atts['style'] !== '' ) {
			self::$styles[] = $atts['style'];
		}

		if ( $atts['section_title'] !== '' ) {
			self::$curr_section['titles'][] = $atts['section_title'];
		}

		$loop_name = str_replace( '-', '_', $atts['type'] );

		return self::loop( $args, $atts, $loop_name );

	}

	public static function pagination() {

		if ( self::$curr_instance['atts']['pagination'] == 'true' ) {

			if ( self::$curr_instance['atts']['load_more'] == 'true' ) {
				nc_get_template( 'load-more.php' );
			}
			else {
				nc_get_template( 'pagination.php' );
			}

		}

	}

	public static function ajax_factory() {

		if ( !isset( $_POST['nc_settings'] ) ) {
			die();
			exit;
		}

		$atts = $_POST['nc_settings']['instance']['atts'];

		if ( isset( $_POST['nc_settings']['paged'] ) ) {
			set_query_var( 'paged', $_POST['nc_settings']['paged'] );
		}

		if ( isset( $_POST['nc_settings']['offset'] ) ) {
			set_query_var( 'offset', $_POST['nc_settings']['offset'] );
		}

		$html = self::factory( $atts );

		die( $html );
		exit;

	}

	public static function add_filters( $atts ) {

		if ( absint( $atts['excerpt_length'] ) > -1 ) {
			add_filter( 'excerpt_length' , __CLASS__ . '::excerpt_length', 99999 );
		}

		if ( !empty( $atts['excerpt_more'] ) ) {
			if ( $atts['excerpt_more'] !== 'inherit' ) {
				add_filter( 'excerpt_more' , __CLASS__ . '::excerpt_more', 99999 );
			}
		}
		else {
			add_filter( 'excerpt_more' , __CLASS__ . '::excerpt_more_default', 99999 );
		}

		if ( $atts['show_taxonomy'] == 'true' ) {
			add_filter( 'newscodes_loop_meta' , __CLASS__ . '::show_taxonomy', 99996 );
		}
		if ( $atts['show_date'] == 'true' ) {
			add_filter( 'newscodes_loop_meta' , __CLASS__ . '::show_date', 99997 );
		}
		if ( $atts['show_time'] == 'true' ) {
			add_filter( 'newscodes_loop_meta' , __CLASS__ . '::show_time', 99998 );
		}
		if ( $atts['show_author'] == 'true' ) {
			add_filter( 'newscodes_loop_meta' , __CLASS__ . '::show_author', 99999 );
		}

	}

	public static function remove_filters( $atts ) {

		if ( absint( $atts['excerpt_length'] ) > -1 ) {
			remove_filter( 'excerpt_length' , __CLASS__ . '::excerpt_length', 99999 );
		}

		if ( !empty( $atts['excerpt_more'] ) ) {
			if ( $atts['excerpt_more'] !== 'inherit' ) {
				remove_filter( 'excerpt_more' , __CLASS__ . '::excerpt_more', 99999 );
			}
		}
		else {
			remove_filter( 'excerpt_more' , __CLASS__ . '::excerpt_more_default', 99999 );
		}

		if ( $atts['show_taxonomy'] == 'true' ) {
			remove_filter( 'newscodes_loop_meta' , __CLASS__ . '::show_taxonomy', 99996 );
		}
		if ( $atts['show_date'] == 'true' ) {
			remove_filter( 'newscodes_loop_meta' , __CLASS__ . '::show_date', 99997 );
		}
		if ( $atts['show_time'] == 'true' ) {
			remove_filter( 'newscodes_loop_meta' , __CLASS__ . '::show_time', 99998 );
		}
		if ( $atts['show_author'] == 'true' ) {
			remove_filter( 'newscodes_loop_meta' , __CLASS__ . '::show_author', 99999 );
		}

	}

	public static function excerpt_length( $length ) {

		global $newscodes_loop;

		if ( is_array( $newscodes_loop['classes'] ) && in_array( 'nc-featured', $newscodes_loop['classes'] ) ) {
			return absint( self::$curr_instance['atts']['excerpt_length'] )*3;
		}
		else {
			return absint( self::$curr_instance['atts']['excerpt_length'] );
		}

	}

	public static function excerpt_more( $more ) {
		return ' <a href="' . get_the_permalink() . '" class="nc-read-more-link">' . self::$curr_instance['atts']['excerpt_more'] . '</a>';
	}

	public static function excerpt_more_default( $more ) {
		return '<a href="' . get_the_permalink() . '" class="nc-read-more">+</a>';
	}

	public static function query_filter( $args, $atts ) {

		$allowed = array(
			'posts_per_page',
			'offset',
		);

		foreach ( $allowed as $key ) {
			if ( !empty( $atts[$key] ) ) {
				if ( $key == 'offset' ) {
					$args[$key] = $args[$key] + absint( $atts[$key] );
				}
				else {
					$args[$key] = absint( $atts[$key] );
				}
			}
		}

		$allowed = array(
			'post_type',
			'post_status',
			'order',
			'orderby',
		);

		foreach ( $allowed as $key ) {
			if ( !empty( $atts[$key] ) ) {
				$args[$key] = $atts[$key];
			}
		}

		if ( $atts['filters'] !== '' && $atts['filter_terms'] !== '' ) {

			if ( strpos( $atts['filters'], '|' ) > 0 && strpos( $atts['filter_terms'], '|' ) > 0 ) {
				$filters = explode( '|', $atts['filters'] );
				$filter_terms = explode( '|', $atts['filter_terms'] );
			}
			else {
				$filters = array( $atts['filters'] );
				$filter_terms = array( $atts['filter_terms'] );
			}

			$tax_query = array();

			if ( $atts['filter_relation'] !== '' ) {
				$tax_query['relation'] = $atts['filter_relation'];
			}

			for ( $i = 0; $i < count( $filters ); $i++ ) {

				if ( taxonomy_exists( $filters[$i] ) ) {
					$tax_query[] = array(
						'taxonomy' => $filters[$i],
						'field' => 'term_id',
						'terms' => isset( $filter_terms[$i] ) ? $filter_terms[$i] : '-1'
					);
				}

			}

			if ( !empty( $tax_query ) ) {
				$args['tax_query'] = $tax_query;
			}

		}

		if ( $atts['meta_keys'] !== '' && $atts['meta_values'] !== '' ) {

			if ( strpos( $atts['meta_keys'], '|' ) > 0 ) {
				$meta_keys = explode( '|', $atts['meta_keys'] );
				$meta_values = explode( '|', $atts['meta_values'] );
				$meta_compares = explode( '|', $atts['meta_compares'] );
				$meta_types = explode( '|', $atts['meta_types'] );
			}
			else {
				$meta_keys = $atts['meta_keys'];
				$meta_values = $atts['meta_values'];
				$meta_compares = $atts['meta_compares'];
				$meta_types = $atts['meta_types'];
			}

			$meta_query = array();

			if ( $atts['meta_relation'] !== '' ) {
				$meta_query['relation'] = $atts['meta_relation'];
			}

			if ( is_array( $meta_keys ) ) {
				for ( $i = 0; $i < count( $meta_keys ); $i++ ) {
					$meta_query[] = array(
						'key' => $meta_keys[$i],
						'value' => $meta_values[$i],
						'compare' => str_replace( array('l','le','s','se'), array('>','>=','<','<='), $meta_compares[$i] ),
						'type' => $meta_types[$i]
					);
				}
			}
			else {
				$meta_query[] = array(
					'key' => $meta_keys,
					'value' => $meta_values,
					'compare' => str_replace( array('l','le','s','se'), array('>','>=','<','<='), $meta_compares ),
					'type' => $meta_types
				);
			}

			if ( !empty( $meta_query ) ) {
				$args['meta_query'] = $meta_query;
			}

		}

		if ( $atts['post_in'] !== '' ) {

			if ( strpos( $atts['post_in'], ',' ) > 0 ) {
				$post_in = explode( ',', $atts['post_in'] );
			}
			else {
				$post_in = array( $atts['post_in'] );
			}

			$args['post__in'] = $post_in;

		}

		if ( $atts['post_notin'] !== '' ) {

			if ( strpos( $atts['post_notin'], ',' ) > 0 ) {
				$post_in = explode( ',', $atts['post_notin'] );
			}
			else {
				$post_in = array( $atts['post_notin'] );
			}

			$args['post__not_in'] = $post_in;

		}

		if ( $atts['http_query'] !== '' ) {
			parse_str( html_entity_decode( $atts['http_query'] ), $add );
			$args = array_merge( $args, $add );
		}

		return $args;

	}

	public static function add_image_before_loop( $size = 'full' ) {
		global $nc_posts;

		$id = $nc_posts->posts[0]->ID;

		if ( has_post_thumbnail( $id ) ) {

			$size_override = self::$curr_instance['atts']['image_size'];
			$size = $size_override == '' ? $size : $size_override;

			$thumb_id = get_post_thumbnail_id( $id );
			$set = wp_get_attachment_image_src( $thumb_id, $size, true );
			$url = $set[0];

			$w = $set[1];
			$h = $set[2];

			$ratio = explode( '-', self::$curr_instance['atts']['image_ratio'] );

			$x = isset( $ratio[0] ) ? $ratio[0] : 4;
			$y = isset( $ratio[1] ) ? $ratio[1] : 3;

			if ( in_array( self::$curr_instance['atts']['image_ratio'], array( '2-1', '1-2', '3-1', '1-3' ) ) ) {
				$orientation = $w/$h > $x/$y ? 'nc-figure-y' : 'nc-figure-x';
			}
			else {
				$orientation = $w/$h > $x/$y ? 'nc-figure-x' : 'nc-figure-y';
			}

			$class = 'class="nc-figure nc-image-ratio-' . self::$curr_instance['atts']['image_ratio'] . ' ' . $orientation . '"';

		?>
			<div class="nc-figure-wrapper">
				<figure <?php echo $class;?> style="background-image:url(<?php echo $url; ?>);">
					<a href="<?php the_permalink(); ?>"></a>
				</figure>
			</div>
		<?php

		}

	}

	public static function show_time( $echo = true ) {

		ob_start();

		nc_get_template( 'loop/show-time.php' );

		echo ob_get_clean();

	}

	public static function show_date( $echo = true ) {

		ob_start();

		nc_get_template( 'loop/show-date.php' );

		echo ob_get_clean();

	}

	public static function show_taxonomy( $echo = true ) {

		ob_start();

		nc_get_template( 'loop/show-taxonomy.php' );

		echo ob_get_clean();

	}

	public static function show_author( $echo = true ) {

		ob_start();

		nc_get_template( 'loop/show-author.php' );

		echo ob_get_clean();

	}

}

?>