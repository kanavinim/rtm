=== Blur Text ===
Author URI: http://www.linsoftware.com
Plugin URI: http://www.linsoftware.com/blur-text/
Contributors: LinSoftware
Donate link: http://www.linsoftware.com/support-free-plugin-development/
Tags: blur text, hide text, blur, hover
Requires at least: 3.9
Tested up to: 4.4
Stable tag: 1.0.0
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

This plugin uses the CSS3 feature "text-shadow" to create the blur and a transparent color font.  This plugin only works with the following browsers:
Firefox 3.5+
Chrome 4+
Safari 4+
Opera 9.6+

This plugin allows you to choose what should be done on unsupported browsers.  There are currently 3 choices:
1) blackout - This makes the background color the same color as the text  (default)
2) none - This doesn't change the text at all.
3) hide - The text will not be shown on unsupported browsers.

The fallback is specified like this:

[blur fallback=blackout color=red]This text will have a solid red background on unsupported browsers.[/blur]

[blur toggle=click fallback=hide]If you are using IE or another unsupported browser, you will not see this text.[/blur]


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
 specific context, I may be albe to program a custom plugin for you.  Please contact me at http://www.linsoftware.com/contact/


== Screenshots ==

1. An example of blurred text.
2. An example of blackout text on unsupported browsers.

== Changelog ==

= 1.0.0: September 2015 =

* First official release!

== Upgrade Notice ==

= 1.0.0 =

* First official release!

