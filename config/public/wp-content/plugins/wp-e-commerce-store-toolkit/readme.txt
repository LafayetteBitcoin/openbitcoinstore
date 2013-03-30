=== WP e-Commerce - Store Toolkit ===

Contributors: visser
Donate link: http://www.visser.com.au/#donations
Tags: e-commerce, wp-e-commerce, mod, delete store, clean store, nuke, store toolkit
Requires at least: 2.9.2
Tested up to: 3.5.1
Stable tag: 1.9.3

== Description ==

Store Toolkit - formally Nuke - includes a growing set of commonly-used WP e-Commerce administration tools aimed at web developers and store maintainers.

Features include:

* Nuke support for clearing WP e-Commerce store records; Products, Variations, Product Images, Product Files, Tags, Categories, Sales, Coupons, etc.
* Support for clearing 3rd party WP e-Commerce Plugins; Custom Fields, Related Products, Add to Wishlist, Product Enquiry, Music Player, etc.
* Nuke support for clearing WordPress records; Posts, Tags, Categories, Links, etc.
* Re-link broken WP e-Commerce Pages
* Re-link existing Sales from pre-registered Users
* Repair WordPress option 'wpsc_version'
* Manage Permalinks for WP e-Commerce Pages (e.g. Products Page, Checkout, etc.)
* All in One SEO Pack integration for WP e-Commerce
* Add a store banner for staging/testing sites
* Right Now in Store Dashboard widget
* File Downloads in Sales detail screen
* File Downloads menu item for all WP e-Commerce File Downloads
* Additional Sales detail columns (e.g. Status Indicator, Session ID, IP Address, Payment Method, etc.)
* Change Add To Cart button label
* Store Status screen
* Set Custom Session ID for Sales

If you find yourself in the situation where you need to start over with a fresh installation of WooCommerce then a 'nuke' will do the job.

For more information visit: http://www.visser.com.au/wp-ecommerce/

== Installation ==

1. Upload the folder 'wp-e-commerce-store-toolkit' to the '/wp-content/plugins/' directory
2. Activate 'WP e-Commerce Store Toolkit' through the 'Plugins' menu in WordPress

That's it!

== Usage ==

==== In WP e-Commerce 3.7 ====

1. Open Store > Store Toolkit

==== In WP e-Commerce 3.8 ====

1. Open Products > Store Toolkit

====

2. Select which WP e-Commerce details you would like to remove and click Nuke

Done!

== Support ==

If you have any problems, questions or suggestions please join the members discussion on my WP e-Commerce dedicated forum.

http://www.visser.com.au/wp-ecommerce/forums/

== Changelog ==

= 1.9.3 =
* Added: File Downloads in Sales detail screen
* Added: Dropdown support for Permalink Pages
* Added: Links to Overview screen
* Added: Cost Price export integration
* Added: Select All options to Nuke

= 1.9.2 =
* Added: Delete Sale to Manage Sales table
* Added: Resend Receipt to Manage Sales table
* Added: Dialog prompts prior to Delete Sale and Resend Receipt

= 1.9.1 =
* Changed: Options engine

= 1.9.0 =
* Added: Store Status screen
* Changed: Moved SQL queries to functions.php
* Fixed: Demo Store stuck on

= 1.8.9 =
* Added: Support for WP e-Commerce Music Player
* Added: Payment Method column to Manage Sales
* Fixed: Incomplete Sale does not appear under Pending on Right Now dashboard widget
* Fixed: Missing common function causing 500 Internal Server Error on Sale detail screen
* Changed: Plugin description on Plugins screen

= 1.8.8 =
* Added: Change Add To Cart label

= 1.8.7 =
* Added: View button to Manage Sales screen (for WP e-Commerce 3.8.9+)
* Added: Session ID column to Manage Sales screen (for WP e-Commerce 3.8.9+)
* Fixed: Nuke Varations supports WP e-Commerce 3.8.9
* Fixed: Products counter in Right Now in Store widget
* Fixed: File Downloads not rendering to table
* Added: Collect IP Address at Checkout and assign to Sale (for WP e-Commerce 3.8.9+)
* Added: IP Address column to Manage Sale (for WP e-Commerce 3.8.9+)
* Added: IP Address Sale detail on Sale Detail screen
* Added: uninstall.php
* Added: Options screen under Settings > Store Toolkit (for WP e-Commerce 3.8.9+)
* Added: Settings link to Plugins screen

= 1.8.6 =
* Added: Manage link to Plugins screen
* Added: Menu items for Nuke screen

= 1.8.5 =
* Fixed: File Downloads where Products have been deleted
* Fixed: Right now in Store hidden if WP e-Commerce is not active
* Added: Tabbed viewing on the Store Toolkit screen
* Added: Per-Category Product nuking

= 1.8.4 =
* Right Now in Store Dashboard widget
* Fixed: Integration with Offline Credit Card Processing
* Added: Cost Price to Add/Edit Products
* Added: Display prompt if All in One SEO Pack is not activated
* Added: Automatic purge of WP e-Commerce Theme transients when switching Themes
* Added: File Downloads view

= 1.8.3 =
* Added: Remove Post support
* Added: Remove Post Tags support
* Added: Remove Post Categories support
* Added: Remove Comments support

= 1.8.2 =
* Changed: Using new 'offline-payment' Post type for OCCP
* Added: Captions to Tools
* Changed: Improved relink Sales for pre-registered Users
* Added: Demo Store banner

= 1.8.1 =
* Fixed: Nuke images only removes Image filetypes attached to Products
* Fixed: Database error re-linking Pages

= 1.8 =
* Changed: Removed Tools page mention
* Fixed: Better term taxonomy count detection
* Fixed: Remove images under WP e-Commerce 3.7
* Fixed: Re-link Pages not working

= 1.7.9 =
* Changed: Moved menu item to Products

= 1.7.8 =
* Changed: Moved Product Importer Deluxe integration for All in One SEO Pack into Plugin
* Changed: Renamed Custom Fields references to Attributes
* Added: Remove Coupons
* Added: wpsc_the_product_weight()

= 1.7.7 =
* Fixed: Not deleting Custom Post Types other than Published

= 1.7.6 =
* Fixed: WP e-Commerce Plugins widget markup
* Fixed: Error removing Tags under WP e-Commerce 3.7

= 1.7.5 =
* Fixed: Count of Custom Fields
* Added: All in One SEO Pack widget for Add/Edit Products

= 1.7.4 =
* Added: Support for custom fields
* Fixed: Count Product images under WP e-Commerce 3.8

= 1.7.3 =
* Fixed: Styling issue within Plugins Dashboard widget

= 1.7.2 =
* Added: Alt. switch to wpsc_get_action()

= 1.7.1 =
* Fixed: Removing Product Enquiries under WP e-Commerce 3.7
* Fixed: progress.gif not showing

= 1.7 =
* Fixed: Issue introduced with wpsc_get_action()

= 1.6.9 =
* Changed: Performance improvements to WP e-Commerce Plugins widget
* Added: Automatic Plugin updates
* Added: Added template for Permalinks

= 1.6.8 =
* Added: Integrated Version Monitor into Plugin

= 1.6.7 =
* Added: Support for Store Menu, will relocate menu to Store if Plugin is detected
* Added: Support for changing the Pending Message appearing in Purchase Receipts and Transaction Results
* Added: Support for changing the Pending Message subject sent to customers in Purchase Receipts

= 1.6.6 =
* Added: Support for clearing out the contents of wp_wpsc_claimed_stock

= 1.6.5 =
* Fixed: Issue where all Product images were being erased
* Added: Physical file removal support for Product images in WP e-Commerce 3.8

= 1.6.4 =
* Changed: Moved ..._check_plugin_version() to functions.php
* Changed: Migrated Plugin prefix from 'vl_wpscn' to 'wpsc_st'

= 1.6.3 =
* Added: Merged Manage Permalinks functionality into Store Toolkit
* Changed: Renamed Plugin from Nuke to Store Toolkit
* Added: Support for repairing corrupt 'wpsc_version' WordPress option after unsuccessful upgrades

= 1.6.2 =
* Changed: wp_delete_post() for removal of Products in WP e-Commerce 3.8
* Fixed: Tools menu not showing Nuke option
* Changed: Renamed menu item from 'Nuke WP e-Commerce' to 'Nuke'

= 1.6.1 =
* Changed: Removal of Add to Wishlist records from WordPress database in WP e-Commerce 3.8

= 1.6 =
* Added: Support for re-linking existing Sales from pre-registered Users

= 1.5.7 =
* Fixed: Issue removing Products within WP e-Commerce 3.8.3

= 1.5.6 =
* Added: Missing wpsc_get_major_version()

= 1.5.5 =
* Added: Urgent support for WP e-Commerce 3.8+

= 1.5.4 =
* Added: Support for 'Re-link WP e-Commerce Pages' in WP e-Commerce 3.8 after corrupt database upgrade

= 1.5.3 =
* Added: Support for WP e-Commerce 3.8 official
* Added: Support for removal of Credit Card entries

= 1.5.2 =
* Added: Support for WP e-Commerce 3.8 RC2

= 1.5.1 =
* Added: Support for Windows systems
* Fixed: Issue removing files from /downloadables/

= 1.5 =
* Added: Support for WP e-Commerce 3.8

= 1.4 =
* Added: Tag support
* Changed: Displayed disabled checkbox's instead of no checkbox for empty records

= 1.3 =
* Added: Support for nuking Add to Wishlist contents
* Added: Automatic Plugin update notification

= 1.2 =
* Added: Administration page

= 1.1 =
* Changed: Migrated custom Page Template solution to a WordPress Plugin

= 1.0 =
* Added: First working release of the Plugin

== Disclaimer ==

It is not responsible for any harm or wrong doing this Plugin may cause. Users are fully responsible for their own use. This Plugin is to be used WITHOUT warranty.