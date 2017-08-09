<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $newscodes_loop, $nc_posts, $paged;

if ( $nc_posts->max_num_pages <= 1 ) {
	return;
}

?>
<nav class="newscodes-pagination">
	<?php
		echo '<span class="nc-pagination-pages">' . __( 'Page', 'nwscds' ) . ' ' . max( 1, get_query_var( 'paged' ) ) . ' ' . __( 'out of', 'nwscds' ) . ' ' . $nc_posts->max_num_pages . '</span>';
		echo paginate_links( apply_filters( 'newscodes_pagination_args', array(
			'base'         => $newscodes_loop['ajax'] === 'true' ? esc_url( add_query_arg( 'paged','%#%' ) ) : esc_url_raw( str_replace( 999999999, '%#%', get_pagenum_link( 999999999, false ) ) ),
			'format'       => '',
			'add_args'     => '',
			'current'      => max( 1, get_query_var( 'paged' ) ),
			'total'        => $nc_posts->max_num_pages,
			'prev_text'    => '&lt;',
			'next_text'    => '&gt;',
			'type'         => 'list',
			'end_size'     => 2,
			'mid_size'     => 2
		) ) );
	?>
</nav>