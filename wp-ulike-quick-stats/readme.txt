=== WP ULike Quick Stats ===
Contributors: 01kawa
Tags: wp-ulike, likes, dashboard, admin-bar, analytics
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.2.5
Stable tag: 1.3
License: GPL-2.0+
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Quickly check WP ULike likes with a dashboard widget and admin toolbar menu.

== Description ==
WP ULike Quick Stats enhances your WP ULike experience by adding:
- A dashboard widget showing the top 5 most-liked posts from the past 30 days.
- An admin toolbar menu displaying today's likes and top 3 posts from the past 7 days.

Perfect for bloggers who want instant feedback on user engagement and motivation to keep writing!

**Requires**: WP ULike plugin (version 4.7.9.1 or higher).

== Installation ==
1. Upload the `wp-ulike-quick-stats` folder to `/wp-content/plugins/`.
2. Activate the plugin through the WordPress admin panel.
3. Ensure WP ULike is installed and activated.
4. Check the dashboard widget and admin toolbar for like stats!

== Frequently Asked Questions ==
= Does it work without WP ULike? =
No, this plugin requires WP ULike to function.

= Is it translation-ready? =
Yes! The plugin supports translations. Japanese (`ja`) is included, and you can add more in the `languages` folder.

== Changelog ==
= 1.3 =
* Added translation support with text domain `wp-ulike-quick-stats`.
* Improved code comments for international users.

= 1.2 =
* Fixed class name check to `WpUlikeInit` for WP ULike 4.7.9.1 compatibility.

= 1.1 =
* Added `plugins_loaded` hook for stable class detection.

= 1.0 =
* Initial release.

== Screenshots ==
1. Dashboard widget showing recent likes.
2. Admin toolbar with today's likes and top posts.