<?php
/**
 * Plugin Name: Subtitles
 *  Plugin URI: http://wordpress.org/plugins/subtitles/
 * Description: Easily add subtitles into your WordPress posts, pages, custom post types, and themes.
 *      Author: <a href="https://philip.blog/">Philip Arthur Moore</a>, <a href="https://wecobble.com">We Cobble</a>
 *     Version: 3.0.0
 * Text Domain: subtitles
 * Domain Path: /languages/
 *     License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Subtitles WordPress Plugin, Copyright 2014-2017 We Cobble <mail@wecobble.com>,
 * distributed under the terms of the GNU General Public License v2 or later.
 *
 * Right now WordPress currently presents no easy way for web publishers to add
 * subtitles into their posts, pages, and other custom post types. This leaves theme makers
 * in a bit of a quandary, trying to figure out how best to present subtitles in a beautiful
 * and sensible way. Post excerpts are a very poor choice for subtitles and the only available
 * option outside of custom fields, but custom fields aren't entirely self-explanatory or
 * user-friendly. This simple, straightforward plugin aims to solve this issue.
 *
 * A note to plugin developers and theme makers: you may be wondering why I've taken
 * the time to document code that to seasoned vets may seem obvious, for example explaining
 * that functions inside of classes are called methods. The reason I've done this is primarily
 * because there was a time when looking at the following code would have made absolutely no
 * sense to me. At that time, I would have loved for there to have been robust inline documentation
 * explaining to me what was happening. Consider the following documentation a gift to newbies and
 * also a gift to my future self, who will undoubtedly look back on some of what I've written here
 * with confusion and chagrin.
 *
 * A few design decisions have been made during the implementation of this plugin, which I'd
 * like to outline below.
 *
 * 1. This plugin should "just work". I do not want users to have to both install the plugin and
 *    then also have to edit their theme files in order to show subtitles on their websites. That's
 *    too painful for what should be a quick and pleasant experience from activation to plugin use.
 * 2. Along these lines, the ideal situation for users is that they should be able to download Subtitles,
 *    activate it, use it, and switch between themes (and preview themes before use) without worrying
 *    about all of their Subtitles data getting lost. If a simple theme-focused plugin like this one
 *    instructs users to use custom template tags like the_subtitle() in their theme template files,
 *    then what will happen when they switch away from their active theme in use? They'll either
 *    think that their data has been lost or they will have to go back into their new theme and
 *    add in the custom template tags all over again. This isn't very user-friendly.
 * 3. Along these lines, there's a problem with custom template tags in that the moment a user disables
 *    Subtitles, his website will crash if he has not put in function_exists checks into his template
 *    files, which isn't pretty. If a user tries Subtitles, doesn't like it, and removes it altogether,
 *    there shouldn't be remnants of the plugin left in his theme's template files. I'd like for it to
 *    be as if the plugin never existed in the first place if a user decides that it's not a good fit.
 *    It would also be very cool if in the future core adopts its own the_subtitle() template tag, which
 *    I'd like not to potentially stomp on.
 * 4. Visually, I have made a major assumption that subtitles belong immediately after titles. The very
 *    definition of a subtitle is that it is a subordinate title of a published work that often gives
 *    explanatory details about the immediately preceeding title. It's for this reason that I've chosen
 *    to filter the output of the_title() with the expectation that post titles will be wrapped in
 *    primary heading (h1) tags. So post titles will be H1, while their subtitles will be spans.
 *    Multiple H1 tags in the HTML5 age are okay.
 * 5. The reason that <spans> are being used is because HTML does not have a dedicated mechanism for
 *    marking up subheadings, alternative titles, or taglines. There are suggested alternatives from
 *    the World Wide Web Consortium (W3C); among them are spans, which work well for what we're trying
 *    to do with titles in WordPress. See the linked documentation for more information.
 *    @link http://www.w3.org/html/wg/drafts/html/master/common-idioms.html#sub-head
 * 6. By default subtitles are available to posts, pages, and Jetpack portfolio projects. If you find that you'd also like to use
 *    them with your custom post types, then simply add post type support for subtitles, for example:
 *    `add_post_type_support( $post_type, 'subtitles' )`. Remember to do this within a function that's
 *    hooked to `init`. See the Codex for more information:
 *    @link http://codex.wordpress.org/Function_Reference/add_post_type_support
 *    @link http://jetpack.com/
 *
 * One of the drawbacks of this approach, which I think is minor enough to proceed with the design of the plugin,
 * is that the $before and $after values in the_title() are unable to be filtered. What this means is that for
 * themes that have markup wrapped inside of a the_title() call the subtitle markup will either be nested
 * inside of the theme-provided markup or break out of the markup, depending on what's wrapped around the_title().
 *
 * The default markup for subtitles in this plugin is spans, so this isn't a problem, but if for some reason
 * you would like to use subtitles for another purpose, then I suggest removing the subtitle filter on the_title()
 * and using one of the helper template tags that have been shipped with the plugin.
 *
 * Bug reports and contributions in the form of patches are both welcomed and very much appreciated.
 * @link https://github.com/wecobble/Subtitles
 *
 * For WordPress PHP documentation standards, see the following link:
 * @link http://make.wordpress.org/core/handbook/inline-documentation-standards/php-documentation-standards/
 *
 * @package   Subtitles
 * @author    We Cobble <mail@wecobble.com>
 * @license   URI: http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2 or later
 * @link      http://wordpress.org/plugins/subtitles/
 * @copyright 2014-2017 We Cobble
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Do not load this file directly.
 *
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Load in front-end functionality.
 *
 * @link http://www.php.net//manual/en/function.require.php
 * @see  plugin_dir_path()
 * @link http://codex.wordpress.org/Function_Reference/plugin_dir_path
 *
 * @since 1.0.0
 */
require plugin_dir_path( __FILE__ ) . 'public/class-subtitles.php';

/**
 * Instantiate Subtitles on `plugins_loaded`.
 *
 * @see  add_action()
 * @link http://codex.wordpress.org/Function_Reference/add_action
 * @link http://codex.wordpress.org/Plugin_API/Action_Reference/plugins_loaded
 *
 * @since 1.0.0
 */
add_action(
	'plugins_loaded', // The hook on which Subtitles is fired.
	array(
		// The primary front-end class.
		'Subtitles',
		// Instantiation method within the class.
		'getinstance',
	)
); // end add_action()

/**
 * Load in front-end functions that can be used by themers.
 *
 * The ultimate goal is that themers will not need to use these functions,
 * as Subtitles should just work out of the box, but for themers who want to unfilter
 * subtitles from the_title() and have more control over what happens with
 * subtitles within their themes, these helper functions exist to make that a viable option.
 *
 * @link http://www.php.net//manual/en/function.require.php
 * @see  plugin_dir_path()
 * @link http://codex.wordpress.org/Function_Reference/plugin_dir_path
 *
 * @since 1.0.0
 */
require plugin_dir_path( __FILE__ ) . 'public/includes/template-tags.php';

/**
 * Load in Dashboard functionality and kick off the primary admin class on `plugins_loaded`.
 *
 * The plugin doesn't really depend on any Ajax functionality,
 * so we'll make sure that the admin class isn't triggered when
 * DOING_AJAX is defined. We'll also make sure that the admin class
 * only fires off when we're actually in the admin area of the site.
 *
 * @see  add_action()
 * @see  is_admin()
 * @see  plugin_dir_path()
 * @link http://codex.wordpress.org/Function_Reference/is_admin
 * @link http://www.php.net//manual/en/function.require.php
 * @link http://codex.wordpress.org/Function_Reference/plugin_dir_path
 * @link http://codex.wordpress.org/Function_Reference/add_action
 * @link http://codex.wordpress.org/Plugin_API/Action_Reference/plugins_loaded
 *
 * @since 1.0.0
 */

if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
	require plugin_dir_path( __FILE__ ) . 'admin/class-subtitles-admin.php';

	add_action(
		'plugins_loaded', // The hook on which Subtitles_Admin is fired.
		array(
			// The primary admin class for Subtitles.
			'Subtitles_Admin',
			// Instantiation method within the class.
			'getinstance',
		)
	); // end add_action()
} // End if().
