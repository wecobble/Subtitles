# Subtitles WordPress Plugin

![Travis CI Build Status](https://travis-ci.org/philiparthurmoore/Subtitles.svg?branch=master)

![Subtitles in action](https://i.cloudup.com/YoFzxUCM2S.png)

Add subtitles into your WordPress posts, pages, custom post types, and themes. No coding required. Simply activate _Subtitles_ and you're ready to go.

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

### _Subtitles_ Doesn't Work! ###

There are two primary issues that may cause users to think that _Subtitles_ doesn't work: 1) no subtitles show on the site or 2) weird HTML begins to appear around titles on a site. I will address both of those here.

#### Subtitles Don't Show Up On My Site!

Subtitles relies on two things to work properly: 1) `the_title` being present in your theme and 2) the [WordPress Loop](http://codex.wordpress.org/The_Loop). This plugin works by automatically filtering all appropriate post titles so that you are not put in the position of needing to open your theme files manually and using the [custom template tags](https://github.com/philiparthurmoore/Subtitles#using-template-tags) that are available in this plugin.

Some themes use titles outside of the standard WordPress Loop, which means that _Subtitles_ won't touch those. If you would like to use subtitles in a non-standard area of your site, outside of the Loop, then you can either change the views that are [supported by the plugin](https://github.com/philiparthurmoore/Subtitles#modifying-supported-subtitles-views) or manually use the template tags that are available to you in this plugin.

The reason this approach has been taken is because if titles outside of the Loop were touched so liberally, you would end up seeing subtitles in places on your site that you wouldn't want them, like in sidebars, navigation menus, and admin screens.

#### There's Weird HTML Showing Up On My Site!

I can almost guarantee that the reason this is happening is because your theme developer is using either `the_title` or `get_the_title` in places where they should not be used. This is a theme bug, not a plugin bug. When titles are used as attributes, the appropriate template tag to use is `the_title_attribute`, never `the_title`.

Please see [these long threads](https://github.com/philiparthurmoore/Subtitles/issues?q=the_title_attribute) as examples of what happens when themes conflict with _Subtitles_.

---

### SEO ###

Will _Subtitles_ ruin your SEO? That's a fair question. The answer is no. I've made a note of exactly why `<spans>` are the default wrappers for subtitles in the [inline developer docs](https://github.com/philiparthurmoore/Subtitles/blob/master/subtitles.php) for the plugin, which I'll reiterate here:

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

### Front-End Performance ###

_Subtitles_ makes one additional server request on the front-end of your website. This is to load sensible CSS that will ensure your subtitle is always scaled properly alongside your website title.

```css
/**
 * Be explicit about this styling only applying to spans,
 * since that's the default markup that's returned by
 * Subtitles. If a developer overrides the default subtitles
 * markup with another element or class, we don't want to stomp
 * on that.
 *
 * @since 1.0.0
 */
span.entry-subtitle {
	display: block; /* Put subtitles on their own line by default. */
	font-size: 0.53333333333333em; /* Sensible scaling. It's assumed that post titles will be wrapped in heading tags. */
}
/**
 * If subtitles are shown in comment areas, we'll hide them by default.
 *
 * @since 1.0.5
 */
#comments .comments-title span.entry-subtitle {
	display: none;
	font-size: 1em;
}
```

I can certainly see a case for potentially inlining these styles, but I need to first make sure that it makes sense to do so at the expense of documentation and extensibility for potential future enhancements to the plugin.

For now, if you'd like to remove this additional CSS call, then simply add a similar function to the following in your plugin or theme's primary file:

```php
function ditch_subtitle_styling() {
	wp_dequeue_style( 'subtitles-style' );
}
add_action( 'wp_enqueue_scripts', 'ditch_subtitle_styling' );
```

After doing this, nothing should be loaded on the front end of your site and you'll need to style subtitles using your own CSS.

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
/**
 * Disable Subtitles in archive views.
 *
 * @uses  function is_archive
 * @uses  function in_the_loop
 */
function subtitles_mod_supported_views() {
	// Ditch subtitles in archives.
	if ( is_archive() ) {
		return false;
	}

	// Default in The Loop behavior from Subtitles.
	if ( in_the_loop() ) {
		return true;
	}
} // end function subtitles_mod_supported_views
add_filter( 'subtitle_view_supported', 'subtitles_mod_supported_views' );
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

All versions of _Subtitles_ can be found on the [Releases](https://github.com/philiparthurmoore/Subtitles/releases) page.

### [v1.0.7](https://github.com/philiparthurmoore/Subtitles/releases/tag/v1.0.7) (August 17th, 2014)

- Bug Fix: Better backend tabbing from the title to the subtitle input field (see [issue](https://github.com/philiparthurmoore/Subtitles/issues/23)).
- Extra: Add default support for Jetpack Portfolios (see [issue](https://github.com/philiparthurmoore/Subtitles/issues/26)).

### [v1.0.6](https://github.com/philiparthurmoore/Subtitles/releases/tag/v1.0.6) (August 4th, 2014)

- Bug Fix: Better visual styling in the back end to keep up with WordPress 4.0

### [v1.0.5](https://github.com/philiparthurmoore/Subtitles/releases/tag/v1.0.5) (July 7th, 2014)

- Bug Fix: If subtitles are shown in comment areas, we'll hide them by default.
- Bug Fix: Better security for nonce checking after update to the WordPress VIP Coding Standards. See [this discussion](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/190) for more information.
- Extra: Wrap primary entry title parts in spans that theme authors can take advantage of for more fine-grained styling when a post has a subtitle.
- Extra: French (fr_FR) language packs added (see [issue](https://github.com/philiparthurmoore/Subtitles/pull/18)).

### [v1.0.4](https://github.com/philiparthurmoore/Subtitles/releases/tag/v1.0.4) (June 20th, 2014)

- Bug Fix: Make sure that other plugins that try to mess with titles do not cause _Subtitles_ to throw PHP warnings due to the second optional `$id` parameter not being sent to the primary `the_subtitles` method used throughout sites (see [issue](https://github.com/philiparthurmoore/Subtitles/issues/16)).

### [v1.0.3](https://github.com/philiparthurmoore/Subtitles/releases/tag/v1.0.3) (June 19th, 2014)

- Bug Fix: Ensure that _Subtitles_ works in PHP 5.2.4 environments (see [issue](https://github.com/philiparthurmoore/Subtitles/issues/8)).

### [v1.0.2](https://github.com/philiparthurmoore/Subtitles/releases/tag/v1.0.2) (June 18th, 2014)

- Bug Fix: Check if `$post` is set before proceeding with any title filtering for subtitles (see [issue](https://github.com/philiparthurmoore/Subtitles/issues/12)).
- Bug Fix: Add a single space between titles and subtitles so that they look sensible when being output as a title attribute (see [commit](https://github.com/philiparthurmoore/Subtitles/commit/5b54263fcf82de6db9e7e0875a0a99974758a81f)).
- Extra: Catalan (ca) language packs added (see [issue](https://github.com/philiparthurmoore/Subtitles/pull/11)).
- Extra: Korean (ko_KR) language packs added (see [issue](https://github.com/philiparthurmoore/Subtitles/pull/10)).
- Extra: Spanish (es_ES) language packs added (see [issue](https://github.com/philiparthurmoore/Subtitles/pull/11)).
- Extra: Begin preparing plugin for better automated testing via [Travis CI](https://travis-ci.org/), [phpunit](https://github.com/sebastianbergmann/phpunit/), [WordPress Coding Standards](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards), and [CodeSniffer](http://pear.php.net/package/PHP_CodeSniffer/)

### [v1.0.1](https://github.com/philiparthurmoore/Subtitles/releases/tag/v1.0.1) (June 14th, 2014)

- Bug Fix: Make sure that the plugin automatically works with `single_post_title` (see [issue](https://github.com/philiparthurmoore/Subtitles/issues/2)).
- Bug Fix: Ensure that special characters in post titles do not erroneously cause subtitles to be skipped during title filtering and checks (see [issue](https://github.com/philiparthurmoore/Subtitles/issues/3)).
- Bug Fix: Remove unnecessary ID checks against nav menus (see [issue](https://github.com/philiparthurmoore/Subtitles/issues/4)).
- Bug Fix: Resolve title output issues when [WordPress SEO by Yoast](https://wordpress.org/plugins/wordpress-seo/) breadcrumbs are used inside of [The Loop](http://codex.wordpress.org/The_Loop) (see [issue](https://github.com/philiparthurmoore/Subtitles/issues/5)).
- Extra: Vietnamese (vi_VN) language packs added.
- Extra: German (de_DE) language packs added.
- Extra: Finnish (fi) language packs added.
- Extra: Italian (it_IT) language packs added.
- Extra: Japanese (ja) language packs added.

### [v1.0.0](https://github.com/philiparthurmoore/Subtitles/releases/tag/v1.0.0) (June 12th, 2014)
- Initial Release ([Launch Announcement](https://philiparthurmoore.com/subtitles))

## Screenshots

Two primary screenshots have been shown in this README.md file, one of the post screen and one of an example of what subtitles will look like on the front end of your website. The [assets folder](https://github.com/philiparthurmoore/Subtitles/tree/master/assets) in this GitHub repository will be used to populate screenshots on the WordPress.org plugin site, and will not be included in the official plugin download from WordPress.org.

## Translations

See the [languages](https://github.com/philiparthurmoore/Subtitles/tree/master/languages) folder for more information on using _Subtitles_ in your language. These are considered "Extras" and will usually be released when a version bump has happened to _Subtitles_, for example during a bug fix or enhancement round of updates.

## Versioning

I've done my best to adhere to [Semantic Versioning](http://semver.org) for _Subtitles_.

Given a version number MAJOR.MINOR.PATCH, increment the:

1. MAJOR version when you make incompatible API changes,
2. MINOR version when you add functionality in a backwards-compatible manner, and
3. PATCH version when you make backwards-compatible bug fixes.

Most of the updates for this plugin will be in the form of bug fixes and minor enhancements.

## Build Status

Most commits and pull requests will undergo automatic build testing via [Travis CI](http://travis-ci.org/). The build result for the most recent non-skipped commit for master is at the top of this README.
