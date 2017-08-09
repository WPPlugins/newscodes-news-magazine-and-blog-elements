<?php

	$post_type = NC_Shortcodes::$curr_instance['atts']['post_type'];
	$category = NC_Shortcodes::$curr_instance['atts']['which_taxonomy'];

	if ( $category == '' ) {
		$post_taxonomies = get_object_taxonomies( $post_type );
		$category = isset( $post_taxonomies[0] ) ? $post_taxonomies[0] : '';
	}

	if ( $category == '' ) {
		return '';
	}

	$terms = get_the_terms( get_the_ID(), $category );

	$add_terms = '';

	if ( $terms ) {

		shuffle( $terms );

		foreach ( $terms as $term ) {

			$link = get_term_link( $term->term_id, $category );
			$add_terms .= "<a href='{$link}' title='{$term->name} Tag' class='nc-show-taxonomy nc-taxonomy-term-{$term->slug}'>{$term->name}</a>";

		}

	}

?>
<span class="nc-taxonomy-wrap">
	<?php echo $add_terms; ?>
</span>