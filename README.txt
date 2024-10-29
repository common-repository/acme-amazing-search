=== Plugin Name ===
Contributors: acmemediakits
Donate link: http://acmemk.com/acme-amazing-search
Tags: search, WooCommerce, Taxonomy search, ajax search, google style search, custom taxonomy search, custom post type search, search by product, search by category, search by tag, search by custom text, woocommerce search, WPML search, search in sku
Requires at least: 4.6
Tested up to: 4.8.0
Stable tag: 2.0.13
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Acme Amazing Search is a simple google style ultra fast search engine that allows you to search anything inside WordPress.

== Description ==

Acme Amazing Search is a simple google style search tool.
With Version 2.0.0 we improved performances: results are displayed real time during typing.

This Plugin creates a new 'Search Tool' section in your settings menu.
Automatically detects WooCommerce, you can choose to perform your search in any post_type or terms.
Now implements a simple caching system.


= WordPress Search =
* Search in Title;
* Search in Excerpt;
* Search in Categories;
* Search in Tags;

= WooCommerce Search =
* Search in SKU
* Search in Product Title;
* Search in Product Excerpt;
* Search in Product Categories;
* Search in Product Tags;

= Custom Search =
* Extend Search to custom Post Types;
* Extend Search to custom Terms (like taxonomies or tags);


It is also compatible with WPML, and you can create your own translations via i18n standard.

Insert the _shortcode [aas] anywehere_ in your WordPress website and you will get a search input field with integrated jQuery UI autocomplete functionality. Get result as you type (at least 2 characters).


[ACME Media Kits](http://acmemk.com/acme-amazing-search "Acme Media Kits") is the home for this plugin.

== Installation ==

= From your WordPress dashboard =
1. Open Plugins Menu -> Add New;
2. Search for "Acme Amazing Search" inside the WordPress repository;
3. Activate it from the 'Plugins' menu in WordPress;

= From WordPress.org =
1. Download the zip archive;
2. Upload zip through Plugins -> Add New -> Upload Plugins;
3. Activate the plugin through the 'Plugins' menu in WordPress;

= Once Activated =
1. Open Menu ACME -> Search Tools;
2. Setup the plugin behaviour;
3. Place the shortcode [aas] anywhere in your site.

== Frequently Asked Questions ==

= Is this plugin working with WooCommerce? =

Yes, it is fully compatible with WooCommerce.

= Does it work with WPML? =

Yes, it correctly works under WPML.


== Screenshots ==

1. Control Panel of Acme Amazing Search Plugin

== Changelog ==

= 2.0.13 =
* added support to character replacement (by js)
* corrected bug in the search display

= 2.0.12 =
* corrected missing opening tag

= 2.0.11 =
* fixed internal function

= 2.0.10 =
* fixed previous svn trunk update

= 2.0.9 =
* added support to Spanish
* js action triggered to class instead of id, so multiple search form can exists in the same page
* replaced wp_guess_url() with get_home_url() to get proper home url on almost environments
* improved woocommerce compability

= 2.0.8 =
* Fixed settings link in WP Plugin Page

= 2.0.7 =
* Added new caching option with hourly WP-Cron job

= 2.0.6 =
* Fixed search in terms issue
* Fixed cache info response

= 2.0.5 =
* Fixed Caching/Encoding issue
* Fixed error on saving custom terms
* Fixed JS error

= 2.0.4 =
* Fixed encoding issues
* Stripped HTML tags form results
* added support to 4.7

= 2.0.3 =
* Fixed php session issue
* Now clicking on the input box will reset the search terms

= 2.0.2 =
* Fixed autocomplete errors
* Plugin otions title is now correct

= 2.0.1 =
* Fixed minor Bug

= 2.0.0 =
* Added custom AJAX response, for performance improving
* JS Caching results: now the search results are displayed during type with no lag
* Added options to set automatic or manual cache
* Added support for SESSION if no cache is present

= 1.1.5 =
* Added Search in SKU option
* Added a new option for applyng a specific post_type to default WP Search. (i.e. Usefull for searching in products)
* Improved performance
* Fixed Caching bugs

= 1.1.4 =
* Fixed minor bug for the "Show All" Button
* Added Menu 'ACME' for Acme plugins

= 1.1.3 =
* Fixed caching issue with WPML active

= 1.1.2 =
* Added language support for more strings

= 1.1.1 =
* Cleanup some annoying warnings

= 1.1.0 =
* Added caching system
* Improved code

= 1.0.3 =
* Added "Search in Pages" functionality


= 1.0.0 =
* Plugin release


== Upgrade Notice ==

= 1.1.5 =
* This version fixes a bug during taxonomy/terms search
* Improved Caching callbacks and performances

= Version 1.1.3 =
* Reccomended Upgrade: this version fixes a bug with WPML

= 1.0.3 =
This version adds search in pages option by default

== Arbitrary section ==
