=== Opinary Poll Integration ===
Tags: opinary poll
Requires at least: 4.9
Requires PHP: 5.6
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Allows to integrate Opinary.com polls directly within content of posts and pages.

This requires an account at Opinary.com (https://opinary.com/).


== Description ==

This plugin allows to directly embed Opinary.com polls into your post's/page's content via custom tags (see "Usage" below).

It requires an account at Opinary (https://opinary.com/).

= Usage =

For normal polls:
[opinary poll="some-poll-id" customer="your-customer"]

For automated polls:
[opinary automated customer="your-customer"]


== Installation ==

Copy the plugin folder into "wp-content/plugins" and activate it on the Plugins page ("/wp-admin/plugins.php").

Afterwards you can simply use the opinary tags within the content of your posts and pages, like:

For normal polls:
[opinary poll="some-poll-id" customer="your-customer"]

For automated polls:
[opinary automated customer="your-customer"]


== Changelog ==

= 0.1.2 =

* Use embed widget

= 0.1.1 =

* use "WordPress-VIP" coding standard (from https://github.com/Automattic/VIP-Coding-Standards)
* add namespaces

= 0.1.0 =

* basic implementation of opinary tags for normal and automated polls
