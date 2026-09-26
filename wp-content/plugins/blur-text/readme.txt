=== Blur Text ===
Author URI: https://www.linsoftware.com
Plugin URI: https://www.linsoftware.com/blur-text/
Contributors: Linnea Huxford
Tags: blur text, hide text, blur, hover
Requires at least: 3.9
Tested up to: 6.7.1
Stable tag: 2.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Blur Text with a shortcode.  Unblur with a click or hover.  Specify a blur color.

== Description ==

Use the shortcode [blur][/blur] to blur text.

For example:
[blur]This text will be blurred[/blur]

Optionally, you can set the blur to be removed when the user clicks or hovers on it.

Here are the shortcode examples for that:

[blur toggle=click]This text will be blurred until it's clicked on[/blur]

[blur toggle=hover]This text will be blurred until it's hovered over[/blur]

Be default, the blurred text color will be black.  You can specify a different color like this:

[blur color=orange]This text will be orange, even when blurred.[/blur]

[blur color=#00FF00]This text will be green, even when blurred.[/blur]

== Installation ==

1. Install the plugin in wp-content\plugins
2. Activate the Plugin
3. Use one of these shortcodes around your text:
[blur][/blur]
[blur toggle=click][/blur]
[blur toggle=hover][/blur]

== Frequently Asked Questions ==

= Can I have the blurred text revealed only to members, only after a form is submitted, or in some other context? =

I may write a premium plugin that has these features.  However, I need suggestions as to exactly what type of features I
 should add.  If you would like me to customize this plugin for you so that it shows the blurred text only in a
 specific context, I may be able to program a custom plugin for you.  Please contact me at http://www.linsoftware.com/contact/

== Screenshots ==

1. An example of blurred text.
2. An example of blackout text on unsupported browsers.

== Changelog ==

= 2.0.0: December 2024 =
* Security Release
* Compatibility with WordPress 6.7.1
* Remove support for IE.
* Remove fallback for unsupported browsers because all browsers now support text-shadow.
* Remove mailing list subscription prompt.

= 1.0.0: September 2015 =

* First official release!

== Upgrade Notice ==

= 1.0.0 =

* First official release!

