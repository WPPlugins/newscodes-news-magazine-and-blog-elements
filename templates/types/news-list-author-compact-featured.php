<?php

	global $newscodes_loop;

?>
<li <?php nc_post_class( $newscodes_loop['classes'] ); ?>>

	<?php if ( $newscodes_loop['loop'] == 1 ) { ?>

		<div class="nc-figure-meta-wrap">

			<?php nc_post_thumbnail(); ?>

			<div class="nc-author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), '100' ); ?>
			</div>

			<<?php nc_title_tag( $newscodes_loop['title_tag'] ); ?>>
				<a href="<?php the_permalink(); ?>">
					<?php the_title(); ?>
					<?php newscodes_post_format(); ?>
				</a>
			</<?php nc_title_tag( $newscodes_loop['title_tag'] ); ?>>

			<div class="nc-meta-compact-wrap">

				<?php do_action( 'newscodes_loop_meta' ); ?>

			</div>

		</div>

		<?php the_excerpt(); ?>

	<?php } else { ?>

		<<?php nc_title_tag( $newscodes_loop['title_tag'] ); ?>>
			<a href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
				<?php newscodes_post_format(); ?>
			</a>
		</<?php nc_title_tag( $newscodes_loop['title_tag'] ); ?>>

	<?php } ?>

</li>