=== WP Art Gallery ===
Contributors: micz
Donate link: http://micz.it/wordpress-plugin-art-gallery/donate/
Tags: gallery, art, jquery, javascript, photos, photo, immersive
Requires at least: 3.8.1
Tested up to: 4.7
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A full immersive gallery.
No thumbnails, no distractions. Only one image a time. Fullscreen!

== Description ==

Show your photos in a full immersive gallery.

It's possible also to define a custom CSS in your template folder to customize completely the gallery.

See the plugin in action here: http://micz.it/wordpress-plugin-art-gallery/example-gallery/


== Installation ==

1. Upload the folder `wp-art-gallery` and all its files to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Insert the shortcode [mz_artg] in the page you want to show the gallery on. More info on the shortcode options are available on the plugin settings page.

== Frequently Asked Questions ==

= Can I add the gallery to a post? =

No. The gallery can be added only on a page using the [mz_artg] shortcode.

== Screenshots ==

1. The gallery in action, see it live here: http://micz.it/wordpress-plugin-art-gallery/example-gallery/ .
2. Plugin settings page.

== Changelog ==

= 1.0.1 =
Fixed a bug opening the gallery in Firefox.

= 1.0 =
First release.

== Upgrade Notice ==

= 1.0.1 =
Fixed a bug opening the gallery in Firefox.

= 1.0 =
First release.

== Using a custom CSS file ==

You can customize the look of the gallery using a custom CSS file. The file must be named wp-art-gallery.css and copied in a wp-art-gallery folder under your theme root folder, the same where is stored the theme style.css file.
This custom file will be loaded after the standard plugin css file, so you can modify only the elements you need, the other elements will be displayed as usual. You can check the standard wp-art-gallery.css to see which css elements the gallery is composed of.

== Plugin source files ==

The source files repository is available at https://github.com/micz/wp-art-gallery/
