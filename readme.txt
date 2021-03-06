=== Blunt Snippets ===
Contributors: Hube2
Tags: snippets, shortcodes, embed, html, css, javascript, php
Requires at least: 3.5
Tested up to: 3.9
Stable tag: 1.1.1
Donate link: 
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows adding of any code snippets (HTML, JS, CSS, PHP, whatever) to content and widgets using shortcodes.

== Description ==

Add HTML, CSS, JavaScript & PHP to content and widgets using shortcodes

Please note that this plugin is design to work with the 
[Advanced Custom Fields](http://wordpress.org/plugins/advanced-custom-fields/) plugin. 
It is possible to use this plugin without ACF, please read the 
[Other Notes](http://wordpress.org/plugins/blunt-snippets/other_notes/) 
section for additional information.

With or without ACF this is a no frills plugin.

You can really enter anything you want as a snippet... so you should be careful. The main reason 
for making this plugin no-frills and making it uses shortcodes that must be copy/pasted is that 
you need to take specific steps to add anything. You can't just willy-nilly paste stuff into 
the content of your site.

== Installation ==

Upload/Install & Activate This Plugin.

For best experience:
Upload/Install & Activate [ACF](http://wordpress.org/plugins/advanced-custom-fields/)

== Other Notes ==

= Inserting Code Snippets with ACF =

1. Create a code snippet
2. Go to the snippet list page
3. Copy the shortcode value
4. Paste the shortcode into your content or widget where you want the snippet to be inserted.

= How to use without ACF =

1. Click "Add Snippet"
2. Add a new custom field name = "blunt_snippet_active". Set the value to 1 for active and 0 to make the snippet inactive
3. Add a new custom field name = "blunt_snippet". Enter your code snippet for the value of this field
4. Go to the snippet list page
5. Copy the shortcode value
6. Paste the shortcode into your content or widget where you want the snippet to be inserted.


== Screenshots ==

1. Location of Blunt Snippets in Menu
2. Snippet Editor w/ACF installed
3. Snippet Editor w/o ACF installed

== Frequently Asked Questions == 



== Changelog ==

= 1.1.1 = 
* Changed label from "Code Snippet Message" to "Code Snippet Instructions"
* Removed $post global from admin_columns_content(), not needed, post_id is passed by hook
* Added shortcode display on snippet edit screen (requires ACF)
* Minor code changes that do not effect operation

= 1.1.0 =
* Updated to work with ACF5

= 1.0.0 =
* Corrected code so that plugin will work without ACF installed
* Added code so that you can switch between ACF and non-ACF and everything keeps working with no data is lost.

= 0.0.1 =
* initial release

== Upgrade Notice ==