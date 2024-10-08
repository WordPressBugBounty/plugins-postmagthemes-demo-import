<?php
/**
 * The plugin page view - the "settings" page of the plugin.
 *
 * @package pmdi
 */

namespace PMDI;

$predefined_themes = $this->import_files;

if ( ! empty( $this->import_files ) && isset( $_GET['import-mode'] ) && 'manual' === isset( $_GET['import-mode'] ) ) {
	$predefined_themes = array();
}

/**
 * Hook for adding the custom plugin page header
 */
do_action( 'pt-pmdi/plugin_page_header' );
?>

<div class="pmdi  wrap  about-wrap">

	<?php ob_start(); ?>
		<h1 class="pmdi__title  dashicons-before  dashicons-upload"><?php esc_html_e( 'PostmagThemes Demo Import', 'pt-pmdi' ); ?></h1>
	<?php
	$plugin_title = ob_get_clean();
	// Display the plugin title (can be replaced with custom title text through the filter below).
	echo wp_kses_post( apply_filters( 'pt-pmdi/plugin_page_title', $plugin_title ) );
	if ( isset( $_REQUEST['author'] ) ) {
		$_SESSION['imprter_user_id'] = absint( $_REQUEST['author'] );
	}
	?>
	<div>
		<form method="get" action="">
			<input type="hidden" name="page" value="pt-one-click-demo-import">
			<?php
			echo wp_dropdown_users(
				array(
					'name'     => 'author',
					'role'     => 'administrator',
					'selected' => isset( $_SESSION['imprter_user_id'] ) ? absint( $_SESSION['imprter_user_id'] ) : 0,
					'echo'     => false,
				)
			);
			?>
					
			<input type="submit" name="save">
		</form>
		<?php esc_html_e( 'Note:- please select the username from above dropdown to download the post as per demo, if not select then current logged in user will be used to create the post. Only Administrator authority username displays here.' ); ?>
		
	</div>
	<?php

	// Display warrning if PHP safe mode is enabled, since we wont be able to change the max_execution_time.
	if ( ini_get( 'safe_mode' ) ) {
		printf(
			esc_html__( '%1$sWarning: your server is using %2$sPHP safe mode%3$s. This means that you might experience server timeout errors.%4$s', 'pt-pmdi' ),
			'<div class="notice  notice-warning  is-dismissible"><p>',
			'<strong>',
			'</strong>',
			'</p></div>'
		);
	}

	// Start output buffer for displaying the plugin intro text.
	ob_start();
	?>

	<div class="pmdi__intro-notice  notice  notice-warning  is-dismissible">
		<p><?php esc_html_e( 'Before you begin, make sure all the required plugins are activated.', 'pt-pmdi' ); ?></p>
	</div>

	<div class="pmdi__intro-text">
		<p class="about-description">
			<?php esc_html_e( 'Importing demo data (post, pages, images, theme settings, ...) is the easiest way to setup your theme.', 'pt-pmdi' ); ?>
			<?php esc_html_e( 'It will allow you to quickly edit everything instead of creating content from scratch.', 'pt-pmdi' ); ?>
		</p>

		<hr>

		<p><?php esc_html_e( 'When you import the data, the following things might happen:', 'pt-pmdi' ); ?></p>

		
		<ul>
			<li><?php esc_html_e( 'No existing posts, pages, categories, images, custom post types or any other data will be deleted or modified.', 'pt-pmdi' ); ?></li>
			<li><?php esc_html_e( 'Posts, pages, images, widgets, menus and other theme settings will get imported.', 'pt-pmdi' ); ?></li>
			<li><?php esc_html_e( 'Please click on the Import button only once and wait, it can take a couple of minutes.', 'pt-pmdi' ); ?></li>
		</ul>

		<?php if ( ! empty( $this->import_files ) ) : ?>
			<?php if ( empty( $_GET['import-mode'] ) || 'manual' !== $_GET['import-mode'] ) : ?>
				<a href="
				<?php
				echo esc_url(
					add_query_arg(
						array(
							'page'        => $this->plugin_page_setup['menu_slug'],
							'import-mode' => 'manual',
						),
						admin_url( $this->plugin_page_setup['parent_slug'] )
					)
				);
				?>
							" class="pmdi__import-mode-switch"><?php esc_html_e( 'Switch to manual import!', 'pt-pmdi' ); ?></a>
			<?php else : ?>
				<a href="<?php echo esc_url( add_query_arg( array( 'page' => $this->plugin_page_setup['menu_slug'] ), admin_url( $this->plugin_page_setup['parent_slug'] ) ) ); ?>" class="pmdi__import-mode-switch"><?php esc_html_e( 'Switch back to theme predefined imports!', 'pt-pmdi' ); ?></a>
			<?php endif; ?>
		<?php endif; ?>

		<hr>
	</div>

	<?php
	$plugin_intro_text = ob_get_clean();

	// Display the plugin intro text (can be replaced with custom text through the filter below).
	echo wp_kses_post( apply_filters( 'pt-pmdi/plugin_intro_text', $plugin_intro_text ) );
	?>

	<?php if ( empty( $this->import_files ) ) : ?>
		<div class="notice  notice-info  is-dismissible">
			<p><?php esc_html_e( 'There are no predefined import files available in this theme. Please upload the import files manually!', 'pt-pmdi' ); ?></p>
		</div>
	<?php endif; ?>

	<?php if ( empty( $predefined_themes ) ) : ?>

		<div class="pmdi__file-upload-container">
			<h2><?php esc_html_e( 'Manual demo files upload', 'pt-pmdi' ); ?></h2>

			<div class="pmdi__file-upload">
				<h3><label for="content-file-upload"><?php esc_html_e( 'Choose a XML file for content import:', 'pt-pmdi' ); ?></label></h3>
				<input id="pmdi__content-file-upload" type="file" name="content-file-upload"  >
			</div>

			<div class="pmdi__file-upload">
				<h3><label for="widget-file-upload"><?php esc_html_e( 'Choose a WIE or JSON file for widget import:', 'pt-pmdi' ); ?></label></h3>
				<input id="pmdi__widget-file-upload" type="file" name="widget-file-upload" > 
			</div>

			<div class="pmdi__file-upload">
				<h3><label for="customizer-file-upload"><?php esc_html_e( 'Choose a DAT file for customizer import:', 'pt-pmdi' ); ?></label></h3>
				<input id="pmdi__customizer-file-upload" type="file" name="customizer-file-upload">
			</div>
		</div>

		<p class="pmdi__button-container">
			<button class="pmdi__button  button  button-hero  button-primary  js-pmdi-import-data"><?php esc_html_e( 'Import Demo Data', 'pt-pmdi' ); ?></button>
		</p>

	<?php elseif ( 1 === count( $predefined_themes ) ) : ?>

		<div class="pmdi__demo-import-notice  js-pmdi-demo-import-notice">
		<?php
		if ( is_array( $predefined_themes ) && ! empty( $predefined_themes[0]['import_notice'] ) ) {
			echo wp_kses_post( $predefined_themes[0]['import_notice'] );
		}
		?>
		</div>

		<p class="pmdi__button-container">
			<button class="pmdi__button  button  button-hero  button-primary  js-pmdi-import-data"><?php esc_html_e( 'Import Demo Data', 'pt-pmdi' ); ?></button>
		</p>

	<?php else : ?>

		<!-- PMDI grid layout -->
		<div class="pmdi__gl  js-pmdi-gl">
		<?php
			// Prepare navigation data.
			$categories = Helpers::get_all_demo_import_categories( $predefined_themes );
		?>
			<?php if ( ! empty( $categories ) ) : ?>
				<div class="pmdi__gl-header  js-pmdi-gl-header">
					<nav class="pmdi__gl-navigation">
						<ul>
							<li class="active"><a href="#all" class="pmdi__gl-navigation-link  js-pmdi-nav-link"><?php esc_html_e( 'All', 'pt-pmdi' ); ?></a></li>
							<?php foreach ( $categories as $key => $name ) : ?>
								<li><a href="#<?php echo esc_attr( $key ); ?>" class="pmdi__gl-navigation-link  js-pmdi-nav-link"><?php echo esc_html( $name ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					</nav>
					<div clas="pmdi__gl-search">
						<input type="search" class="pmdi__gl-search-input  js-pmdi-gl-search" name="pmdi-gl-search" value="" placeholder="<?php esc_html_e( 'Search demos...', 'pt-pmdi' ); ?>">
					</div>
				</div>
			<?php endif; ?>
			<div class="pmdi__gl-item-container  wp-clearfix  js-pmdi-gl-item-container">
				<?php foreach ( $predefined_themes as $index => $import_file ) : ?>
					<?php
						// Prepare import item display data.
						$img_src = esc_url( $import_file['import_preview_image_url'] ) ? esc_url( $import_file['import_preview_image_url'] ) : '';
						// Default to the theme screenshot, if a custom preview image is not defined.
					if ( empty( $img_src ) ) {
						$theme   = wp_get_theme();
						$img_src = $theme->get_screenshot();
					}

					?>
					<div class="pmdi__gl-item js-pmdi-gl-item" data-categories="<?php echo esc_attr( Helpers::get_demo_import_item_categories( $import_file ) ); ?>" data-name="<?php echo esc_attr( strtolower( $import_file['import_file_name'] ) ); ?>">
						<div class="pmdi__gl-item-image-container">
							<?php if ( ! empty( $img_src ) ) : ?>
								<img class="pmdi__gl-item-image" src="<?php echo esc_url( $img_src ); ?>">
							<?php else : ?>
								<div class="pmdi__gl-item-image  pmdi__gl-item-image--no-image"><?php esc_html_e( 'No preview image.', 'pt-pmdi' ); ?></div>
							<?php endif; ?>
						</div>
						<div class="pmdi__gl-item-footer<?php echo ! empty( $import_file['preview_url'] ) ? '  pmdi__gl-item-footer--with-preview' : ''; ?>">
							<h4 class="pmdi__gl-item-title" title="<?php echo esc_attr( $import_file['import_file_name'] ); ?>"><?php echo esc_html( $import_file['import_file_name'] ); ?></h4>
							<button class="pmdi__gl-item-button  button  button-primary  js-pmdi-gl-import-data" value="<?php echo esc_attr( $index ); ?>"><?php esc_html_e( 'Import', 'pt-pmdi' ); ?></button>
							<?php if ( ! empty( $import_file['preview_url'] ) ) : ?>
								<a class="pmdi__gl-item-button  button" href="<?php echo esc_url( $import_file['preview_url'] ); ?>" target="_blank"><?php esc_html_e( 'Preview', 'pt-pmdi' ); ?></a>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<div id="js-pmdi-modal-content"></div>

	<?php endif; ?>

	<p class="pmdi__ajax-loader  js-pmdi-ajax-loader">
		<span class="spinner"></span> <?php esc_html_e( 'Importing, please wait!', 'pt-pmdi' ); ?>
	</p>

	<div class="pmdi__response  js-pmdi-ajax-response"></div>
</div>

<?php
/**
 * Hook for adding the custom admin page footer
 */
do_action( 'pt-pmdi/plugin_page_footer' );
