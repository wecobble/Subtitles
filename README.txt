=== Subtitles ===
Contributors: philiparthurmoore
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2XTWCMPJ3NCYG
Tags: subtitle, subtitles, title, titles
Requires at least: 3.9
Tested up to: 3.9.1
Stable tag: 1.0.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add subtitles into your WordPress posts, pages, custom post types, and themes. No coding required. Simply activate Subtitles and you're ready to go.

== Description ==

Add subtitles into your WordPress posts, pages, custom post types, and themes. No coding required. Simply activate *Subtitles* and you're ready to go.

Right now WordPress currently presents no easy way for web publishers to add subtitles into their posts, pages, and other custom post types. This leaves users and developers in a bit of a quandary, trying to figure out how best to present subtitles in a beautiful and sensible way. Post excerpts are a very poor choice for subtitles and the only available option outside of custom fields, but custom fields aren't entirely self-explanatory or user-friendly. This simple, straightforward plugin aims to solve this issue.

Simply download *Subtitles*, activate it, and begin adding subtitles into your posts and pages today.

For more advanced usage of the plugin, please see the Frequently Asked Questions.

If you like *Subtitles*, thank me with [coffee](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2XTWCMPJ3NCYG). If you find it buggy, [tell me on GitHub](https://github.com/philiparthurmoore/Subtitles). And if you have a cool example of how you're using *Subtitles* on your website, let me know on [Twitter](https://twitter.com/philip_arthur).

== Installation ==

By default the *Subtitles* plugin just works. All you should need to do in order to begin using it is activate the plugin and begin adding subtitles into your posts, pages, and *Subtitles*-enabled custom post types.

There are no custom template tags to add into your theme and, outside of advanced use, there is nothing you need to do to your theme in order to begin using this plugin.

What follows are instructions on how to install the plugin and get it working.

= Using The WordPress Dashboard (Recommended) =

1. Navigate to *Plugins → Add New* from within the WordPress Dashboard.
2. Search for `subtitles`.
3. Click **Install Now** on *Subtitles* by Philip Arthur Moore.
4. Activate the plugin.

= Uploading in WordPress Dashboard =

1. Navigate to *Plugins → Add New* from within the WordPress Dashboard.
2. Click on the **Upload** link underneath the *Install Plugins* page title.
3. Click the **Browse...** button and choose `subtitles.zip` in its download location on your computer.
4. Click the **Install Now** button.
5. Activate the plugin.

= Using FTP (Not Recommended) =

1. Download `subtitles.zip`.
2. Extract the `subtitles` directory to your computer.
3. Upload the `subtitles` directory to your `/wp-content/plugins/` directory.
4. Navigate to *Plugins → Installed Plugins* and activate the plugin.

== Frequently Asked Questions ==

There are two types of questions that are anticipated: user questions and developer questions. I'll address the user questions first.

= Users: How to use *Subtitles* =

*Subtitles* lets you easily add subtitles into your WordPress posts, pages, and *Subtitles*-supported custom post types.

After plugin activation, you should see an input field labeled **Enter subtitle here** immediately under your **Enter title here** input field.

After adding a subtitle into your post, simply hit publish and then view your post. There's nothing else to do.

= Users: What happens to my subtitles if I uninstall this plugin? =

Nothing. They'll still be retained in your database, so if you ever decide to use *Subtitles* again, you'll be able to activate the plugin and have your subtitles show up again.

= Developers: Adding Support into Custom Post Types =

If you'd like to add *Subtitles* support into a custom post type, use `add_post_type_support` in a function hooked to `init`, for example:

`
function theme_slug_add_subtitles_support() {
	add_post_type_support( 'custom-post-type-slug', 'subtitles' );
}
add_action( 'init', 'theme_slug_add_subtitles_support' );
`

This should also work on core-supported post types, like `attachment`.

= Developers: Removing Support from Posts and Pages =

If you'd like to remove *Subtitles* support from posts or pages, use `remove_post_type_support` in a function hooked to `init`, for example:

`
function remove_subtitles_support() {
	remove_post_type_support( 'post', 'subtitles' );
	remove_post_type_support( 'page', 'subtitles' );
}
add_action( 'init', 'remove_subtitles_support' );
`
= Developers: Modifying Subtitles Markup =

HTML does not have a dedicated mechanism for marking up subheadings, alternative titles, or taglines. There are [suggested alternatives](http://www.w3.org/html/wg/drafts/html/master/common-idioms.html#sub-head) from the World Wide Web Consortium (W3C); among them are spans, which work well for what we're trying to do with titles in WordPress.

If for some reason you'd like to change the markup, hook a custom output function to `subtitle_markup`, for example:

`
function subtitle_markup_mods( $markup ) {
	$markup[ 'before' ] = '<span class="custom-subtitle-class">';
	$markup[ 'after' ] = '</span>';

	return $markup;
}
add_filter( 'subtitle_markup', 'subtitle_markup_mods' );
`
= Developers: Modifying Supported Views =

By default, subtitles appear on most views throughout a site. This includes single post views, single page views, archive views, and search results pages.

If you'd like to change this behavior, you can do so by taking advantage of `subtitle_view_supported`. For example, if you'd like to hide subtitles on all archive pages, the following code would work:

`
function subtitle_views( $view ) {
	if ( is_archive() ) {
		return false;
	}

	return $view;
}
add_filter( 'subtitle_view_supported', 'subtitle_views' );
`
= Developers: Filtering All Subtitle Output =

If you'd like to change the output of all subtitles throughout your site, use a function hooked to `the_subtitle`, for example:

`
function better_subtitle( $title ) {
	return $title . 'Hello World';
}
add_filter( 'the_subtitle', 'better_subtitle' );
`
= Developers: Using Template Tags =

I very much hope that you do not need to use these template tags, because all of the above methods for handling subtitles should be enough. That said, in the event that you do need to use either `the_subtitle()` or `get_the_subtitle()`, they exist in the plugin and will give you a little bit more flexibility over your theme.

They work in the same way that `the_title()` and `get_the_title()` work, for example:

`
the_subtitle( '<p class="entry-subtitle">', '</p>' );
`

Here's how using `get_the_subtitle` would look:

`
echo get_the_subtitle( 35 );
`

An ID isn't necessary for `get_the_subtitle`, but will work for retrieving subtitles from posts that aren't currently being viewed.

== Screenshots ==

1. The input prompt for subtitles.

== Changelog ==

= 1.0.0 =
* Initial Release

== Upgrade Notice ==

= 1.0.0 =
* Initial Release