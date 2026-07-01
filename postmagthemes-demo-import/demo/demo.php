<?php
/**
 * Demo configuration
 */
$activate_theme = wp_get_theme();
$themeName      = $activate_theme->get( 'Name' );

if($pmdi_plugin->createSlug($themeName) == 'context-blog-pro' ){

	add_filter( 'pt-pmdi/import_files', function( $import_files ) {
		if ( ! empty( $import_files ) ) {
			return $import_files;
		}

		return array(
			array(
				'import_file_name'             => esc_html__( 'Import Context Blog Pro', 'pt-pmdi' ),
				'categories'                   => array( esc_html__('Category A','pt-pmdi') ),
				'import_file_url' 	           => esc_url( 'https://www.postmagthemes.com/download/ContextblogPro/content8.xml'),
				'import_widget_file_url'	     => esc_url( 'https://www.postmagthemes.com/download/ContextblogPro/widgets.wie'),
				'import_customizer_file_url'	 => esc_url( 'https://www.postmagthemes.com/download/ContextblogPro/customizer3.dat'),
				  'import_notice'                => esc_html__( 'You have activated Context Blog Pro theme from postmagthemes hence its demo content will be set', 'pt-pmdi' ),
				  'preview_url'                  => esc_url('https://contextblog.postmagthemes.com/contextblogpro/'),
				'import_preview_image_url'     => esc_url( 'https://www.postmagthemes.com/download/ContextblogPro/screenshot.png' ),
			),
			array(
				'import_file_name'             => esc_html__( 'Import Context Blog Newsmag Pro', 'pt-pmdi' ),
				'categories'                   => array( esc_html__('Category B','pt-pmdi') ),
				'import_file_url'              => esc_url( 'https://www.postmagthemes.com/download/newsmagpro/content4.xml'),
				'import_widget_file_url'         => esc_url( 'https://www.postmagthemes.com/download/newsmagpro/widgets.wie'),
				'import_customizer_file_url'     => esc_url( 'https://www.postmagthemes.com/download/newsmagpro/customizer4.dat'),
				'import_notice'                => esc_html__( 'You have activated Context Blog Pro theme from postmagthemes hence its demo content will be set', 'pt-pmdi' ),
				'preview_url'                  => esc_url('https://contextblog.postmagthemes.com/contextblogpro/newsmagpro'),
				'import_preview_image_url'     => esc_url( 'https://www.postmagthemes.com/download/newsmagpro/screenshot.png' ),
			),
			array(
				'import_file_name'             => esc_html__('Coming soon','pt-pmdi' ),
				'import_preview_image_url'     => esc_url( 'https://www.postmagthemes.com/download/colornewsmagazine/commingsoon_demo.jpg' ),
				'import_notice'                => esc_html__( 'Coming soon, please do not import this. ', 'pt-pmdi' ),
				),
		);
	}, 10 );
	
	// some theme has problem getting menu location hence below add_Action is added specally if there are 2 menu locale_get_region
	// even some has 2 menu lcation below code is not required as in the case of context blog theme. but required now.
	// some has single menu location e.g color newsmagazine theme below code is still required.
	// most of theme do not require below code as they have single menu location e.g best news, isha etc.
	// below code should be repalaced in another theme as well later.

	add_action( 'pt-pmdi/after_import', function( $selected_import = array() ) use ( $pmdi_plugin ) {

		$theme_slug = $pmdi_plugin->createSlug( wp_get_theme()->get( 'Name' ) );
		if ( $theme_slug !== 'context-blog-pro' ) {
			return;
		}
	
		$primary_menu   = wp_get_nav_menu_object( 'primary' );
		$sidepanel_menu = wp_get_nav_menu_object( 'sidemenu' );
	
		if ( ! $primary_menu && ! $sidepanel_menu ) {
			return;
		}
	
		$locations = (array) get_theme_mod( 'nav_menu_locations', array() );
	
		foreach ( array( $primary_menu, $sidepanel_menu ) as $menu ) {
			if ( ! $menu ) {
				continue;
			}
	
			// Fix draft/pending items left behind by the importer.
			$items = wp_get_nav_menu_items( $menu->term_id, array( 'post_status' => 'any' ) );
			if ( $items ) {
				foreach ( $items as $item ) {
					if ( $item->post_status !== 'publish' ) {
						wp_update_post( array(
							'ID'          => $item->ID,
							'post_status' => 'publish',
						) );
					}
				}
			}
	
			// Fix stale term count so the theme actually renders the menu.
			// This is the real fix for items being invisible until a manual
			// save in Appearance > Menus.
			wp_update_term_count_now( array( $menu->term_id ), 'nav_menu' );
		}
	
		if ( $primary_menu ) {
			$locations['primary'] = (int) $primary_menu->term_id;
		}
		if ( $sidepanel_menu ) {
			$locations['sidepanel'] = (int) $sidepanel_menu->term_id;
		}
	
		set_theme_mod( 'nav_menu_locations', $locations );
	
	}, 10, 1 );
}

if($pmdi_plugin->createSlug($themeName) == 'newsmag-context-blog' ){

	add_filter( 'pt-pmdi/import_files', function( $import_files ) {
		if ( ! empty( $import_files ) ) {
			return $import_files;
		}

		return array(
			array(
				'import_file_name'             => esc_html__( 'Import Newsmag Context Blog', 'pt-pmdi' ),
				'categories'                   => array( esc_html__('Category A','pt-pmdi') ),
				'import_file_url' 	           => esc_url( 'https://www.postmagthemes.com/download/newsmagcontextblog/content3.xml'),
				'import_widget_file_url'	     => esc_url( 'https://www.postmagthemes.com/download/newsmagcontextblog/widgets1.wie'),
				'import_customizer_file_url'	 => esc_url( 'https://www.postmagthemes.com/download/newsmagcontextblog/customizer3.dat'),
				  'import_notice'                => esc_html__( 'You have activated Newsmag Context Blog theme from postmagthemes hence its demo content will be set', 'pt-pmdi' ),
				  'preview_url'                  => esc_url('https://contextblog.postmagthemes.com/newsmagcontextblog/'),
				'import_preview_image_url'     => esc_url( 'https://www.postmagthemes.com/download/newsmagcontextblog/screenshot.png' ),
			),
			array(
				'import_file_name'             => esc_html__('Coming soon','pt-pmdi' ),
				'import_preview_image_url'     => esc_url( 'https://www.postmagthemes.com/download/colornewsmagazine/commingsoon_demo.jpg' ),
				'import_notice'                => esc_html__( 'Coming soon, please do not import this. ', 'pt-pmdi' ),
			),
		);
	}, 10 );
	add_action( 'pt-pmdi/after_import', function( $selected_import = array() ) use ( $pmdi_plugin ) {

		$theme_slug = $pmdi_plugin->createSlug( wp_get_theme()->get( 'Name' ) );
		if ( $theme_slug !== 'newsmag-context-blog' ) {
			return;
		}

		// Find menus by name (adjust names to match imported menu names).
		$primary_menu   = wp_get_nav_menu_object( 'primary' );
		$sidepanel_menu = wp_get_nav_menu_object( 'Sidemenu' );

		// If menus aren’t found, do nothing (prevents nav-menus warning).
		if ( ! $primary_menu && ! $sidepanel_menu ) {
			return;
		}

		$locations = (array) get_theme_mod( 'nav_menu_locations', array() );

		if ( $primary_menu ) {
			$locations['primary'] = (int) $primary_menu->term_id;
		}
		if ( $sidepanel_menu ) {
			$locations['sidepanel'] = (int) $sidepanel_menu->term_id;
		}

		set_theme_mod( 'nav_menu_locations', $locations );
	}, 10, 1 );
}

if($pmdi_plugin->createSlug($themeName) == 'best-news' ){

	add_filter( 'pt-pmdi/import_files', function( $import_files ) {
		if ( ! empty( $import_files ) ) {
			return $import_files;
		}

		return array(
			array(
				'import_file_name'           => esc_html__( 'Import Best News Demo', 'pt-pmdi' ),
				'categories'                 => array( esc_html__( 'Category A', 'pt-pmdi' ) ),
				'import_file_url'            => esc_url( 'https://www.postmagthemes.com/download/bestnews/contents.xml' ),
				'import_widget_file_url'     => esc_url( 'https://www.postmagthemes.com/download/bestnews/widgets.wie' ),
				'import_customizer_file_url' => esc_url( 'https://www.postmagthemes.com/download/bestnews/customizer.dat' ),
				'import_notice'              => esc_html__( 'You have activated Best News theme from postmagthemes hence now its demo content will be set', 'pt-pmdi' ),
				'preview_url'                => esc_url( 'https://www.postmagthemes.com/demobestnews/' ),
				'import_preview_image_url'   => esc_url( 'https://www.postmagthemes.com/download/bestnews/screenshot.png' ),
			),
			array(
				'import_file_name'         => esc_html__('Coming soon','pt-pmdi' ),
				'import_preview_image_url' => esc_url( 'https://www.postmagthemes.com/download/bestnews/commingsoon_demo.jpg' ),
				'import_notice'            => esc_html__( 'Coming soon, please do not import this. ', 'pt-pmdi' ),
			),
		);
	}, 10 );
}


if($pmdi_plugin->createSlug($themeName) == 'pro-isha' ){

	add_filter( 'pt-pmdi/import_files', function( $import_files ) {
		if ( ! empty( $import_files ) ) {
			return $import_files;
		}

		return array(
			array(
				'import_file_name'           => esc_html__( 'Import Pro Isha Demo', 'pt-pmdi' ),
				'categories'                 => array( esc_html__( 'Category A', 'pt-pmdi' ) ),
				'import_file_url'            => esc_url( 'https://www.postmagthemes.com/download/proisha/proisha.WordPress.2020-01-04.xml' ),
				'import_widget_file_url'     => esc_url( 'https://www.postmagthemes.com/download/proisha/www.postmagthemes.com-demoproisha-widgets_2.wie' ),
				'import_customizer_file_url' => esc_url( 'https://www.postmagthemes.com/download/proisha/pro-isha-export.dat' ),
				'import_notice'              => esc_html__( 'You have activated Pro Isha theme from postmagthemes hence now its demo content will be set', 'pt-pmdi' ),
				'preview_url'                => esc_url( 'https://www.postmagthemes.com/demoproisha/' ),
				'import_preview_image_url'   => esc_url( 'https://www.postmagthemes.com/download/proisha/screenshot.png' ),
			),
			array(
				'import_file_name'         => esc_html__( 'Coming soon', 'pt-pmdi' ),
				'import_preview_image_url' => esc_url( 'https://www.postmagthemes.com/download/proisha/commingsoon_demo.jpg' ),
				'import_notice'            => esc_html__( 'Coming soon, please do not import this. ', 'pt-pmdi' ),
			),
		);
	}, 10 );
}

if($pmdi_plugin->createSlug($themeName) == 'isha' ){

	add_filter( 'pt-pmdi/import_files', function( $import_files ) {
		if ( ! empty( $import_files ) ) {
			return $import_files;
		}

		return array(
			array(
				'import_file_name'           => esc_html__( 'Import Isha Demo', 'pt-pmdi' ),
				'categories'                 => array( esc_html__( 'Category A', 'pt-pmdi' ) ),
				'import_file_url'            => esc_url( 'https://www.postmagthemes.com/download/isha/ishaportfolio.post.xml' ),
				'import_widget_file_url'     => esc_url( 'https://www.postmagthemes.com/download/isha/demoisha-widgets.wie' ),
				'import_customizer_file_url' => esc_url( 'https://www.postmagthemes.com/download/isha/isha-export-custo.dat' ),
				'import_notice'              => esc_html__( 'You have activated Isha theme from postmagthemes hence now its demo content will be set', 'pt-pmdi' ),
				'preview_url'                => esc_url( 'https://www.postmagthemes.com/demoisha/' ),
				'import_preview_image_url'   => esc_url( 'https://www.postmagthemes.com/download/isha/screenshot.png' ),
			),
			array(
				'import_file_name'         => esc_html__('Coming soon','pt-pmdi' ),
				'import_preview_image_url' => esc_url( 'https://www.postmagthemes.com/download/isha/commingsoon_demo.jpg' ),
				'import_notice'            => esc_html__( 'Coming soon, please do not import this. ', 'pt-pmdi' ),
			),
		);
	}, 10 );
}

if($pmdi_plugin->createSlug($themeName) == 'ink-context-blog' ){

	add_filter( 'pt-pmdi/import_files', function( $import_files ) {
		if ( ! empty( $import_files ) ) {
			return $import_files;
		}

		return array(
			array(
				'import_file_name'             => esc_html__( 'Import Ink Context Blog', 'pt-pmdi' ),
				'categories'                   => array( esc_html__('Category A','pt-pmdi') ),
				'import_file_url' 	           => esc_url( 'https://www.postmagthemes.com/download/inkcontextblog/content2.xml'),
				'import_widget_file_url'	     => esc_url( 'https://www.postmagthemes.com/download/inkcontextblog/widgets2.wie'),
				'import_customizer_file_url'	 => esc_url( 'https://www.postmagthemes.com/download/inkcontextblog/customizer2.dat'),
				  'import_notice'                => esc_html__( 'You have activated Ink Context Blog theme from postmagthemes hence its demo content will be set', 'pt-pmdi' ),
				  'preview_url'                  => esc_url('https://contextblog.postmagthemes.com/inkcontextblog/'),
				'import_preview_image_url'     => esc_url( 'https://www.postmagthemes.com/download/inkcontextblog/screenshot.png' ),
			),
			array(
				'import_file_name'             => esc_html__('Coming soon','pt-pmdi' ),
				'import_preview_image_url'     => esc_url( 'https://www.postmagthemes.com/download/colornewsmagazine/commingsoon_demo.jpg' ),
				'import_notice'                => esc_html__( 'Coming soon, please do not import this. ', 'pt-pmdi' ),
			),
		);
	}, 10 );
}

if($pmdi_plugin->createSlug($themeName) == 'color-newsmagazine' ){

	add_filter( 'pt-pmdi/import_files', function( $import_files ) {
		if ( ! empty( $import_files ) ) {
			return $import_files;
		}

		return array(
			array(
				'import_file_name'           => esc_html__( 'Import Color NewsMagazine', 'pt-pmdi' ),
				'categories'                 => array( esc_html__( 'Category A', 'pt-pmdi' ) ),
				'import_file_url'            => esc_url( 'https://www.postmagthemes.com/download/colornewsmagazine/contents1.xml' ),
				'import_widget_file_url'     => esc_url( 'https://www.postmagthemes.com/download/colornewsmagazine/widgets1.wie' ),
				'import_customizer_file_url' => esc_url( 'https://www.postmagthemes.com/download/colornewsmagazine/customizer1.dat' ),
				'import_notice'              => esc_html__( 'You have activated Color NewsMagazine theme from postmagthemes hence its demo content will be set', 'pt-pmdi' ),
				'preview_url'                => esc_url( 'https://www.postmagthemes.com/democolornewsmagazine/' ),
				'import_preview_image_url'   => esc_url( 'https://www.postmagthemes.com/download/colornewsmagazine/screenshot.png' ),
			),
			array(
				'import_file_name'         => esc_html__( 'Coming soon', 'pt-pmdi' ),
				'import_preview_image_url' => esc_url( 'https://www.postmagthemes.com/download/colornewsmagazine/commingsoon_demo.jpg' ),
				'import_notice'            => esc_html__( 'Coming soon, please do not import this. ', 'pt-pmdi' ),
			),
		);
	}, 10 );

	add_action( 'pt-pmdi/after_import', function( $selected_import = array() ) use ( $pmdi_plugin ) {

		$theme_slug = $pmdi_plugin->createSlug( wp_get_theme()->get( 'Name' ) );
		if ( $theme_slug !== 'color-newsmagazine' ) {
			return;
		}

		// Find menus by name (adjust names to match imported menu names).
		$primary_menu   = wp_get_nav_menu_object( 'primary' );
		$sidepanel_menu = wp_get_nav_menu_object( 'Sidemenu' );

		// If menus aren’t found, do nothing (prevents nav-menus warning).
		if ( ! $primary_menu && ! $sidepanel_menu ) {
			return;
		}

		$locations = (array) get_theme_mod( 'nav_menu_locations', array() );

		if ( $primary_menu ) {
			$locations['primary'] = (int) $primary_menu->term_id;
		}
		if ( $sidepanel_menu ) {
			$locations['sidepanel'] = (int) $sidepanel_menu->term_id;
		}

		set_theme_mod( 'nav_menu_locations', $locations );
	}, 10, 1 );
	
}

if($pmdi_plugin->createSlug($themeName) == 'context-blog' ){

	add_filter( 'pt-pmdi/import_files', function( $import_files ) {
		if ( ! empty( $import_files ) ) {
			return $import_files;
		}
	
		return array(
			array(
				'import_file_name'           => esc_html__( 'Import Context Blog A', 'pt-pmdi' ),
				'categories'                 => array( esc_html__( 'Category A', 'pt-pmdi' ) ),
				'import_file_url'            => esc_url( 'https://www.postmagthemes.com/download/contextblog/contents1.xml' ),
				'import_widget_file_url'     => esc_url( 'https://www.postmagthemes.com/download/contextblog/widgets1.wie' ),
				'import_customizer_file_url' => esc_url( 'https://www.postmagthemes.com/download/contextblog/customizer1.dat' ),
				'import_notice'              => esc_html__( 'You have activated Context Blog theme from postmagthemes hence its demo content will be set', 'pt-pmdi' ),
				'preview_url'                => esc_url( 'https://contextblog.postmagthemes.com' ),
				'import_preview_image_url'   => esc_url( 'https://www.postmagthemes.com/download/contextblog/screenshot.png' ),
			),
			array(
				'import_file_name'           => esc_html__( 'Import Context Blog B', 'pt-pmdi' ),
				'categories'                 => array( esc_html__( 'Category B', 'pt-pmdi' ) ),
				'import_file_url'            => esc_url( 'https://www.postmagthemes.com/download/contextblog/contents2.xml' ),
				'import_widget_file_url'     => esc_url( 'https://www.postmagthemes.com/download/contextblog/widgets2.wie' ),
				'import_customizer_file_url' => esc_url( 'https://www.postmagthemes.com/download/contextblog/customizer2.dat' ),
				'import_notice'              => esc_html__( 'You have activated Context Blog theme from postmagthemes hence its demo content will be set', 'pt-pmdi' ),
				'preview_url'                => esc_url( 'https://www.postmagthemes.com/wp-content/uploads/2024/06/contextblogdemo3.jpg' ),
				'import_preview_image_url'   => esc_url( 'https://www.postmagthemes.com/download/contextblog/screenshot2.jpg' ),
			),
			array(
				'import_file_name'         => esc_html__( 'Coming soon', 'pt-pmdi' ),
				'import_preview_image_url' => esc_url( 'https://www.postmagthemes.com/download/colornewsmagazine/commingsoon_demo.jpg' ),
				'import_notice'            => esc_html__( 'Coming soon, please do not import this.', 'pt-pmdi' ),
			),
		);
	}, 10 );
}
