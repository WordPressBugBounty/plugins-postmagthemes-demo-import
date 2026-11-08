=== PostmagThemes One click Demo Import for WordPress Site ===
Contributors: postmagthemes
Tags: demo importer, one click import, import, customizer, widgets, ocdi
Requires at least: 4.7
Tested up to: 7.1
Stable tag: 1.2.1
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Import a theme's demo content, widgets and customizer settings in one click. Works with PostmagThemes themes and themes from other authors.

== Description ==

**PostmagThemes Demo Import** sets up your site to look like the theme demo in a single click. Instead of creating posts, pages, menus and widgets by hand, activate the plugin, go to **Appearance > Import Demo Data**, and import. Functionally it is theme demo import plugin.

It is built for PostmagThemes themes, but it also works with **any theme** that provides standard demo files.

The main advantage of this plugin is that theme developers can register their demo import files directly in their themes. Users then set up their website by simply clicking the **Import** button on the **Import Demo Data** page as a ocdi function.


Check out [demo of our ocdi](https://youtu.be/WaSYqGqYCro)


= Features =

* **One-click import** for supported PostmagThemes themes, with a preview of each available demo
* **Works with any theme** – upload the content (.xml), widgets (.wie) and customizer (.dat) files from your theme author
* **Imports everything** – posts, pages, media, menus, widgets and customizer settings
* **Choose the post author** – assign imported posts to any user on your site
* **Safe uploads** – files are checked for the correct type before they are saved to the server
* **Theme developer friendly** – register your theme's demos with a single filter
* Translation ready

= Supported PostmagThemes themes =

Context Blog, Context Blog Pro, Blink, Ink, NewsMag and other PostmagThemes themes that support demo import.

= How to import a demo =

**With a PostmagThemes theme**

1. Activate a PostmagThemes theme that supports demo import, such as Context Blog.
2. Go to **Appearance > Import Demo Data**. You will see a preview of the theme's demo.
3. Click **Import** and wait for it to finish.

**With a theme from another author**

1. Download the three demo files from the theme author's website: content (.xml), widgets (.wie) and customizer settings (.dat).
2. Go to **Appearance > Import Demo Data**. Because the active theme is not a PostmagThemes theme, you will see three upload fields instead of a demo preview.
3. Upload each file to its matching field, then click **Import**.

= Are you a theme developer? =

Integrate PostmagThemes Demo Import into your theme and make website setup easier for your users. It takes just a few minutes.

**Step 1: Register your demo files**

Add the following code to your theme's `functions.php`. Add it at the top level of the file, not inside your own `after_setup_theme` callback, otherwise the demo may not appear.

```php

add_filter( 'pt-pmdi/import_files', function ( $import_files ) {
    $import_files[] = array(
        'import_file_name'           => esc_html__('My Theme Demo name', 'theme-text-domain'),
        'categories'                 => array(esc_html__( 'Blog', 'theme-text-domain' )),
        'import_file_url'            => esc_url( 'https://example.com/demo/content.xml' ),
        'import_widget_file_url'     => esc_url( 'https://example.com/demo/widgets.wie'),
        'import_customizer_file_url' => esc_url( 'https://example.com/demo/customizer.dat'),
        'import_preview_image_url'   => esc_url( 'https://example.com/demo/screenshot.png'),
        'preview_url'                => esc_url('https://example.com/demo/'),
        'import_notice'              => esc_html__('Optional note shown before import.', 'theme-text-domain'),
    );
    return $import_files;
} );

```

**Step 2 : Recommend the plugin to your users**

Add the following code to your theme's `functions.php` to show your users a notice with a link to install PostmagThemes Demo Import. Replace the `my_theme_` prefix and the `my-theme` text domain with your own.

```php
function your_theme_demo_import_notice() {
    // Hide if the plugin is already active or the user can't install plugins.
    if ( class_exists( 'PMDI\OneClickDemoImport' ) || ! current_user_can( 'install_plugins' ) ) {
        return;
    }

    $url = admin_url( 'plugin-install.php?s=postmagthemes-demo-import&tab=search&type=term' );
    ?>
    <div class="notice notice-info is-dismissible">
        <p>
            <?php esc_html_e( 'Want your site to look like the theme demo? Install the free PostmagThemes Demo Import plugin to import the demo content in one click.', 'theme-text-domain' ); ?>
            <a href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Get PostmagThemes Demo Import', 'theme-text-domain' ); ?></a>
        </p>
    </div>
    <?php
}
add_action( 'admin_notices', 'your_theme_demo_import_notice' );

```

**Step 3 (optional): Hide the PostmagThemes showcase**

The Import Demo Data page shows PostmagThemes' free themes and plugins. If you prefer not to show them to your users, add this to your theme's `functions.php`:

```php
add_filter( 'pt-pmdi/show_wporg_showcase', '__return_false' );


```

= Are you a theme user? =

Ask your theme developer to support this plugin. Let them know how easy it makes setting up a site with their theme's demo.
Note: Demo content is imported under the currently logged-in WordPress user. The plugin does provide an option to map author from the demo import files to existing users on your website.

= Credits =

Based on [One Click Demo Import](https://wordpress.org/plugins/one-click-demo-import/).

== Installation ==

**From your WordPress dashboard**

1. Go to **Plugins > Add New**.
2. Search for "PostmagThemes Demo Import".
3. Click **Install Now**, then **Activate**.

**Manual installation**

1. Download `postmagthemes-demo-import.zip`.
2. Upload the `postmagthemes-demo-import` folder to `/wp-content/plugins/`.
3. Activate the plugin from the **Plugins** menu in WordPress.

== Frequently Asked Questions ==

= What does this plugin do? =

It imports a theme's demo content, widgets and customizer settings so your site matches the theme demo.

= Does it only work with PostmagThemes themes? =

No. PostmagThemes themes get one-click import with a demo preview. For other themes, upload the .xml, .wie and .dat files provided by the theme author.

= Where do I find the import page? =

Go to **Appearance > Import Demo Data** in your WordPress dashboard.

= Can I assign imported posts to a specific user? =

Yes. By default, demo content is imported under the currently logged-in user. Before importing, you can choose a different existing user to be the author of the imported posts.

= How do I make my theme compatible? =

Register your demo files with the `pt-pmdi/import_files` filter in your theme's `functions.php`. See "Are you a theme developer?" in the Description for the code.

= Will importing delete my existing content? =

No. The importer adds the demo content alongside your existing content. We recommend importing on a fresh site for the best result.

== Screenshots ==

1. Demo preview for a supported PostmagThemes theme.
2. Upload fields for importing demo files from another theme.
3. Import complete.

== Changelog ==

= 1.2.1 - November 8, 2026 =
* Added: Optional review request after a successful import (dismissible, never shown again once dismissed).
* Added: Instruction added in readme for theme developer to use this plugin for their theme.

= 1.2.0 - November 6, 2026 =
* Fixed: Corrected the minimum PHP version requirement.

= 1.1.9 - October 4, 2026 =
* Added: Own agent.

= 1.1.8 - September 12, 2026 =
* Tested up to WordPress 7.1.

= 1.1.7 - July 1, 2026 =
* Fixed: Context Blog Pro demo import.
* Fixed: Menu import bug.

= 1.1.6 - December 31, 2025 =
* Fixed: Upload fields for 3 files shown incorrectly, caused by load_textdomain on the init hook.

= 1.1.5 - December 22, 2025 =
* Fixed: load_textdomain error.

= 1.1.4 - December 5, 2025 =
* Changed: show_on_front is now saved as "posts" for all Context Blog themes.
* Changed: Updated demo content for Context Blog Pro, NewsMag and Ink.
* Fixed: Manual demo import bug.

= 1.1.3 - October 5, 2024 =
* Added: NewsMag Context Blog demo.

= 1.1.2 - June 20, 2024 =
* Added: Second demo for Context Blog.

= 1.1.1 - June 13, 2024 =
* Added: Ink Context Blog demo.

= 1.1.0 - January 15, 2024 =
* Added: Context Blog Pro demo.

= 1.0.9 - June 12, 2023 =
* Added: Context Blog demo.
* Fixed: Error 500 during import.
* Tested with PHP up to 8.1.2.

= 1.0.8 - December 17, 2022 =
* Improved: Uploaded files are now validated on the server before saving, even if the browser check is bypassed.
* Improved: Sanitization and validation.
* Tested with the latest WordPress version.

= 1.0.7 - May 19, 2022 =
* Added: File type check for the 3 upload fields.
* Improved: Sanitization and validation.

= 1.0.6 - March 9, 2021 =
* Removed: Theme mode setting for Color NewsMagazine.
* Changed: Replaced demo content file.

= 1.0.5 - March 1, 2021 =
* Fixed: Non-object error in WXRImporter.php.

= 1.0.4 - March 1, 2021 =
* Added: Color NewsMagazine demo import.
* Added: Choose the author for imported posts.

= 1.0.3 - January 4, 2020 =
* Added: Isha Pro demo import.

= 1.0.2 - December 19, 2019 =
* Added: Isha demo import.

= 1.0.1 - October 21, 2019 =
* Removed: Redux dependency.

= 1.0.0 - August 27, 2019 =
* Initial release.

== Upgrade Notice ==

= 1.2.1 =
Adds a small, dismissible review request after a successful import.

= 1.2.0 =
Corrects the minimum PHP version requirement.

== Image Resources ==

All plugin images (banners, icons and screenshots) are self-created and licensed under Creative Commons Zero (CC0).