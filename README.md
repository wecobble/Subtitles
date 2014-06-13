# Subtitles WordPress Plugin

![Subtitles in action](https://i.cloudup.com/YoFzxUCM2S.png)

Add subtitles into your WordPress posts, pages, custom post types, and themes. No coding required. Simply activate _Subtitles_ and you're ready to go.

```css
Contributors: philiparthurmoore
Tags: subtitle, subtitles, title, titles
Requires at least: 3.9
Tested up to: 3.9.1
Stable tag: 1.0.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
```

Right now WordPress currently presents no easy way for web publishers to add subtitles into their posts, pages, and other custom post types. This leaves users and developers in a bit of a quandary, trying to figure out how best to present subtitles in a beautiful and sensible way. Post [excerpts](http://codex.wordpress.org/Function_Reference/the_excerpt) are a very poor choice for subtitles and the only available option outside of [custom fields](http://codex.wordpress.org/Custom_Fields), but custom fields aren't entirely self-explanatory or user-friendly. This simple, straightforward plugin aims to solve this issue.

Simply download _Subtitles_, activate it, and begin adding subtitles into your posts and pages today. For more advanced usage of the plugin, please see the [Frequently Asked Questions](https://github.com/philiparthurmoore/Subtitles/blob/master/README.md#frequently-asked-questions).

If you like _Subtitles_, [thank me with coffee](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2XTWCMPJ3NCYG) :coffee:. If you find it buggy, [tell me on GitHub](https://github.com/philiparthurmoore/Subtitles/issues) :beetle:. And if you have a cool example of how you're using _Subtitles_ on your website, let me know on [Twitter](https://twitter.com/philip_arthur) :bird:.

## Installation

By default the _Subtitles_ plugin just works. All you should need to do in order to begin using it is activate the plugin and begin adding subtitles into your posts, pages, and _Subtitles_-enabled custom post types.

There are no custom template tags to add into your theme and, outside of advanced use, there is nothing you need to do to your theme in order to begin using this plugin.

What follows are instructions on how to install the plugin and get it working.

### Using The WordPress Dashboard (Recommended) ###

1. Navigate to *Plugins → Add New* from within the WordPress Dashboard.
2. Search for `subtitles`.
3. Click **Install Now** on *Subtitles* by Philip Arthur Moore.
4. Activate the plugin.

### Uploading in WordPress Dashboard ###

1. Navigate to *Plugins → Add New* from within the WordPress Dashboard.
2. Click on the **Upload** link underneath the *Install Plugins* page title.
3. Click the **Browse...** button and choose `subtitles.zip` in its download location on your computer.
4. Click the **Install Now** button.
5. Activate the plugin.

### Using FTP (Not Recommended) ###

1. Download `subtitles.zip`.
2. Extract the `subtitles` directory to your computer.
3. Upload the `subtitles` directory to your `/wp-content/plugins/` directory.
4. Navigate to *Plugins → Installed Plugins* and activate the plugin.

---

## Frequently Asked Questions ##

There are two types of questions that are anticipated: user questions and developer questions. I'll address the user questions first, and then dive into more detailed information about customizing _Subtitles_.

### How to Use _Subtitles_ ###

_Subtitles_ lets you easily add subtitles into your WordPress posts, pages, custom post types, and themes.

![New post waiting on a title and subtitle](https://i.cloudup.com/HhC9q0j5bH.png)

After plugin activation, you should see an input field labeled **Enter subtitle here** immediately under your **Enter title here** input field. After adding a subtitle into your post, simply hit publish and then view your post. There's nothing else to do.

---

### Uninstalling _Subtitles_ ###

When you uninstall _Subtitles_, nothing will happen to your subtitles post meta. They'll still be retained in your database, so if you ever decide to use _Subtitles_ again, you'll be able to activate the plugin and have your subtitles show up. In a future release, there may be the option to clean subtitles out of your database, but it didn't make the cut for the initial release, and auto-deleting the data on uninstallation would have been a bad move, as subtitles are non-trivial post meta.

---

### SEO ###

Will _Subtitles_ ruin your SEO? That's a fair question. The answer is no. I've made a note of exactly why `<spans>` are the default wrappers for subtitles in the inline developer docs for the plugin, which I'll reiterate here:

```php
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
```

If you're worried about SEO and the markup of _Subtitles_, then [roll your own markup](https://github.com/philiparthurmoore/Subtitles#modifying-subtitles-markup).

---

### Adding _Subtitles_ Support into Custom Post Types ###

If you'd like to add _Subtitles_ support into a custom post type, use `add_post_type_support` in a function hooked to `init`, for example:

```php
function theme_slug_add_subtitles_support() {
	add_post_type_support( 'custom-post-type-slug', 'subtitles' );
}
add_action( 'init', 'theme_slug_add_subtitles_support' );
```

This should also work on core-supported post types, like `attachment`.

---

### Removing Default Support from Posts and Pages ###

If you'd like to remove _Subtitles_ support from posts or pages, use `remove_post_type_support` in a function hooked to `init`, for example:

```php
function remove_subtitles_support() {
	remove_post_type_support( 'post', 'subtitles' );
	remove_post_type_support( 'page', 'subtitles' );
}
add_action( 'init', 'remove_subtitles_support' );
```

This will work on any post type that may have had _Subtitles_ support added into it elsewhere.

---

### Modifying _Subtitles_ Markup ###

HTML does not have a dedicated mechanism for marking up subheadings, alternative titles, or taglines. There are [suggested alternatives](http://www.w3.org/html/wg/drafts/html/master/common-idioms.html#sub-head) from the World Wide Web Consortium (W3C); among them are spans, which work well for what we're trying to do with titles in WordPress.

If for some reason you'd like to change the markup, hook a custom output function to `subtitle_markup`, for example:

```php
function subtitle_markup_mods( $markup ) {
	$markup[ 'before' ] = '<span class="custom-subtitle-class">';
	$markup[ 'after' ] = '</span>';

	return $markup;
}
add_filter( 'subtitle_markup', 'subtitle_markup_mods' );
```

I do not suggest using headings tags for subtitles.

---

### Modifying Supported _Subtitles_ Views ###

By default, subtitles appear on most views throughout a site. This includes single post views, single page views, archive views, and search results pages.

If you'd like to change this behavior, you can do so by taking advantage of `subtitle_view_supported`. For example, if you'd like to hide subtitles on all archive pages, the following code would work:

```php
function subtitle_views( $view ) {
	if ( is_archive() ) {
		return false;
	}

	return $view;
}
add_filter( 'subtitle_view_supported', 'subtitle_views' );
```

---

### Filtering All Subtitle Output ###

If you'd like to change the output of all subtitles throughout your site, use a function hooked to `the_subtitle`, for example:

```php
function better_subtitle( $title ) {
	return $title . 'Hello World';
}
add_filter( 'the_subtitle', 'better_subtitle' );
```

This will filter both the title and subtitle output after _Subtitles_ has done all of its magic.

---

### Using Template Tags ###

I very much hope that you do not need to use these template tags, because all of the above methods for handling subtitles should be enough. That said, in the event that you do need to use either `the_subtitle()` or `get_the_subtitle()`, they exist in the plugin and will give you a little bit more flexibility over your theme.

They work in the same way that `the_title()` and `get_the_title()` work, for example:

```php
if ( function_exists( 'the_subtitle' ) ) {
	the_subtitle( '<p class="entry-subtitle">', '</p>' );
}
```

Here's how using `get_the_subtitle` would look:

```php
if ( function_exists( 'get_the_subtitle' ) ) {
	echo get_the_subtitle( 35 );
}
```

An ID isn't necessary for `get_the_subtitle`, but will work for retrieving subtitles from posts that aren't currently being viewed.

## Changelog

### 1.0.0 (June 12th, 2014)
- Initial Release ([Launch Announcement](https://philiparthurmoore.com/subtitles))

## Screenshots

Two primary screenshots have been shown in this README.md file, one of the post screen and one of an example of what subtitles will look like on the front end of your website. The [assets folder](https://github.com/philiparthurmoore/Subtitles/tree/master/assets) in this GitHub repository will be used to populate screenshots on the WordPress.org plugin site, and will not be included in the official plugin download from WordPress.org.

## Translations

See the [languages](https://github.com/philiparthurmoore/Subtitles/tree/master/languages) folder for more information on using _Subtitles_ in your language.