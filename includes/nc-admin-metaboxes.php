<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NC_Page_Metaboxes {

	var $hook;
	var $title;
	var $menu;
	var $permissions;
	var $slug;
	var $page;

	function __construct( $hook, $title, $menu, $permissions, $slug, $body_content_cb = '__return_true' ) {
		$this->hook = $hook;
		$this->title = $title;
		$this->menu = $menu;
		$this->permissions = $permissions;
		$this->slug = $slug;
		$this->body_content_cb = $body_content_cb;

		add_action( 'admin_menu', array( $this, 'add_page' ) );
	}

	function add_page(){

		$this->page = add_submenu_page( $this->hook, $this->title, $this->menu, $this->permissions, $this->slug, array( $this, 'render_page' ) ,10 );

		add_action( 'load-' . $this->page, array( $this, 'page_actions' ),9 );
		add_action( 'admin_footer-' . $this->page, array( $this, 'footer_scripts' ) );

	}

	function footer_scripts(){
		?>
		<script>postboxes.add_postbox_toggles(pagenow);</script>
		<?php
	}

	function page_actions(){
		do_action( 'add_meta_boxes_' . $this->page, null );
		do_action( 'add_meta_boxes', $this->page, null );

		add_screen_option( 'layout_columns', array( 'max' => 2, 'default' => 2 ) );

		wp_enqueue_script( 'postbox' );
	}

	function render_page(){
		?>
		<div class="wrap">

			<?php screen_icon(); ?>

			<h2> <?php echo esc_html( $this->title );?> </h2>

			<form name="newscodes-settings" method="post">
				<input type="hidden" name="action" value="newscodes-default">
				<?php
					wp_nonce_field( 'newscodes' );

					wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
					wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
				?>
				<div id="poststuff">
		
					<div id="post-body" class="metabox-holder columns-<?php echo 1 == get_current_screen()->get_columns() ? '1' : '2'; ?>">

						<div id="post-body-content">
							<?php call_user_func( $this->body_content_cb ); ?>
						</div>

						<div id="postbox-container-1" class="postbox-container">
							<?php do_meta_boxes( '', 'side', null ); ?>
						</div>

						<div id="postbox-container-2" class="postbox-container">
							<?php do_meta_boxes( '', 'normal', null); ?>
							<?php do_meta_boxes( '', 'advanced', null); ?>
						</div>

					</div>

				</div>

			</form>

		</div>
		<?php
	}

}

?>