<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $newscodes_loop, $nc_posts, $paged;

if ( $nc_posts->max_num_pages <= 1 ) {
	return;
}

?>
<nav class="newscodes-load-more">
	<span class="nc-load-more"><?php _e( 'LOAD MORE', 'nwscds' ); ?></span>
</nav>