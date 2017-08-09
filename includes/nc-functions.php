<?php

	if ( ! function_exists( 'newscodes_loop_start' ) ) {

		function newscodes_loop_start( $echo = true ) {

			ob_start();

			nc_get_template( 'loop-start.php' );

			if ( $echo )
				echo ob_get_clean();
			else
				return ob_get_clean();

		}

	}

	if ( ! function_exists( 'newscodes_loop_end' ) ) {

		function newscodes_loop_end( $echo = true ) {

			ob_start();

			nc_get_template( 'loop-end.php' );

			if ( $echo )
				echo ob_get_clean();
			else
				return ob_get_clean();

		}

	}

	if ( ! function_exists( 'newscodes_post_format' ) ) {

		function newscodes_post_format( $echo = true ) {

			$size_ratio = NC_Shortcodes::$curr_instance['atts']['show_format'];

			if ( $size_ratio !== 'true' ) {
				return;
			}

			ob_start();

			nc_get_template( 'loop/post-format.php' );

			if ( $echo )
				echo ob_get_clean();
			else
				return ob_get_clean();

		}

	}

?>