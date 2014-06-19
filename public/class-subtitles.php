<?php
/**
 * @package Subtitles
 */

/**
 * Do not load this file directly
 *
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Checks for the existence of Subtitles before defining it.
 *
 * @link http://www.php.net//manual/en/function.class-exists.php
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'Subtitles' ) ) {
	/**
	 * Define primary Subtitles class.
	 *
	 * Within classes constants and variables are called properties.
	 * Within classes functions are called methods.
	 *
	 * @link http://php.net/manual/en/language.oop5.php
	 *
	 * @since 1.0.0
	 */
	class Subtitles {
		/**
		 * Constant used for plugin versioning and enqueues.
		 *
		 * Constants differ from normal variables in that you don't use the $ symbol
		 * to declare or use them. The value must be a constant expression, not (for example)
		 * a variable, a property, a result of a mathematical operation, or a function call.
		 *
		 * Semantic versioning is used in this plugin.
		 *
		 * @link http://semver.org/
		 *
		 * @since 1.0.0
		 */
		const VERSION = '1.0.0';

		/**
		 * Constant used when referencing the plugin in load text domain calls and other
		 * functionality that relies on the slug of the plugin being present.
		 *
		 * @since 1.0.0
		 */
		const PLUGIN_SLUG = 'subtitles';

		/**
		 * Constant used in subtitle support checks for custom post types.
		 *
		 * @link http://codex.wordpress.org/Post_Types
		 *
		 * @since 1.0.0
		 */
		const SUBTITLE_FEATURE_SUPPORT = 'subtitles';

		/**
		 * Constant used in nonce checks when saving custom subtitles data.
		 *
		 * @link http://codex.wordpress.org/WordPress_Nonces
		 *
		 * @since 1.0.0
		 */
		const SUBTITLE_NONCE_NAME = '_subtitle_data_nonce';

		/**
		 * Constant used for subtitle meta key.
		 *
		 * Note the underscore before the custom meta for subtitles. This ensures that
		 * the meta key doesn't appear in the custom fields section on the new post
		 * and page screens and is only used within our classes. This is considered
		 * protected meta.
		 *
		 * @link http://codex.wordpress.org/Custom_Fields
		 *
		 * @since 1.0.0
		 */
		const SUBTITLE_META_KEY = '_subtitle';

		/**
		 * If no instance of Subtitles has been made then let's make it.
		 *
		 * Declaring class properties or methods as static makes them accessible
		 * without needing an instantiation of the class Subtitles.
		 *
		 * For example, this is how you would access a static property:
		 * Subtitles::$instance
		 *
		 * And this is how you would access a static method:
		 * Subtitles::getinstance()
		 *
		 * @link http://www.php.net/manual/en/language.oop5.late-static-bindings.php
		 * @var object $instance
		 * @access private
		 * @static
		 *
		 * @since 1.0.0
		 */
		private static $instance = null;

		/**
		 * Kick off the class if it hasn't been instantiated.
		 *
		 * There is a lot of information about the Singleton design pattern
		 * and singleton implementations for WordPress and plugins. I am not (at all!)
		 * an expert on this and may very well be instantiating Subtitles in the
		 * wrong way. If there's a better way to do this, or if it's not necessary
		 * to use this design pattern with the plugin, please let me know. The reason
		 * I've done it this way is because plugins should never be instantiated twice
		 * in WordPress. In general, I assume that this won't happen under normal circumstances,
		 * but using this design pattern ensures that if someone tries to instantiate
		 * Subtitles twice, then it won't be possible to do so.
		 *
		 * For more reading, see the following links.
		 *
		 * @link http://en.wikipedia.org/wiki/Singleton_pattern
		 * @link http://hardcorewp.com/2013/using-singleton-classes-for-wordpress-plugins/
		 * @link http://eamann.com/tech/the-case-for-singletons/
		 * @link http://www.toppa.com/2013/the-case-against-singletons-in-wordpress/
		 * @link http://eamann.com/tech/making-singletons-safe-in-php/
		 *
		 * @staticvar Singleton $instance The Singleton instance of this class.
		 * @return Singleton The Singleton instance of this class.
		 * @access public
		 * @static
		 *
		 * @since 1.0.0
		 */
		public static function getinstance() {
			if ( ! self::$instance ) {
				self::$instance = new Subtitles;
			}

			return self::$instance;
		} // end method getinstance()

		/**
		 * Declare constructor methods for the class Subtitles.
		 *
		 * Classes which have a constructor method call this method on each newly-created object,
		 * so it is suitable for any initialization that the object may need before it is used.
		 *
		 * Access is set to protected to prevent outside access to the method for anything other than
		 * the class or a child class. Otherwise `new` could be used to kick off this constructor if
		 * it were public.
		 *
		 * @access protected
		 *
		 * @since 1.0.0
		 */
		protected function __construct() {
			/**
			 * Add default support for subtitles on posts and pages.
			 *
			 * To find out the number and name of arguments for any action in WordPress,
			 * search Core for the matching do_action call. For example, searching for
			 * "do_action( 'save_post'" reveals that two arguments, $post_id and $post are used.
			 *
			 * add_action accepts:
			 *
			 * - $tag The name of the action to which the $function_to_add is hooked.
			 * - callback function The name of the function you wish to be called.
			 * - int $priority optional. Used to specify the order in which the functions
			 *   associated with a particular action are executed (default: 10).
			 *   Lower numbers correspond with earlier execution, and functions with the
			 *   same priority are executed in the order in which they were added to the action.
			 * - int $accepted_args optional. The number of arguments the function accept (default: 1).
			 *
			 * Support for post types needs to be fired on `init`.
			 *
			 * @see add_action()
			 * @link http://codex.wordpress.org/Function_Reference/add_action
			 *
			 * @since 1.0.0
			 */
			add_action( 'init',               array( &$this, 'add_subtitles_support' ) );

			/**
			 * Make Subtitles available for translation.
			 *
			 * Translations can be added into the /languages/ directory.
			 * @link http://codex.wordpress.org/Translating_WordPress
			 *
			 * @since 1.0.0
			 */
			add_action( 'init',               array( &$this, 'load_subtitles_textdomain' ) );

			/**
			 * Enqueue front-end scripts and styles.
			 *
			 * @see add_action()
			 * @link http://codex.wordpress.org/Function_Reference/add_action
			 *
			 * @since 1.0.0
			 */
			add_action( 'wp_enqueue_scripts', array( &$this, 'subtitle_scripts' ) );

			/**
			 * Filter post titles to display subtitles properly.
			 *
			 * add_filter accepts:
			 *
			 * - $tag             The name of the filter to hook the $function_to_add callback to.
			 * - $function_to_add The callback to be run when the filter is applied.
			 * - $priority        (optional) The order in which the functions associated with a
			 *                    particular action are executed. Lower numbers correspond with
			 *                    earlier execution, and functions with the same priority are
			 *                    executed in the order in which they were added to the action.
			 *                    Default: 10.
			 * $accepted_args     (optional) The number of arguments the function accepts.
			 *                    Default: 1.
			 *
			 * @see add_filter()
			 * @link http://codex.wordpress.org/Function_Reference/add_filter
			 *
			 * @since 1.0.0
			 */
			if ( ! is_admin() ) { // Don't touch anything inside of the WordPress Dashboard, yet.
				add_filter( 'the_title',           array( &$this, 'the_subtitle' ), 10, 2 );

				/**
				 * Let's also filter the dedicated function for single post titles
				 *
				 * @link https://github.com/philiparthurmoore/Subtitles/issues/2
				 *
				 * @since 1.0.1
				 */
				add_filter( 'single_post_title',   array( &$this, 'the_subtitle' ), 10, 2 );

				/**
				 * Make sure that Subtitles plays nice with WordPress SEO plugin by Yoast
				 *
				 * @link https://wordpress.org/plugins/wordpress-seo/
				 * @link https://github.com/philiparthurmoore/Subtitles/issues/5
				 *
				 * @since 1.0.1
				 */
				add_filter( 'wp_seo_get_bc_title', array( &$this, 'plugin_compat_wordpress_seo' ) );
			}
		} // end method __construct()

		/**
		 * Make sure that Subtitles plays nice with WordPress SEO plugin by Yoast.
		 *
		 * The plugin features breadcrumb functionality, which users can add into their themes
		 * by using functionality that's specific to the plugin. Because it's so popular and
		 * I'm not sure where or how people will insert their breadcrumbs into their template
		 * files, it's best avoid messing with the plugin altogether. We'll filter the output
		 * and make sure that subtitles isn't included in any of the breadcrumb titles.
		 *
		 * @link https://wordpress.org/plugins/wordpress-seo/
		 * @link https://github.com/philiparthurmoore/Subtitles/issues/5
		 * @link http://us1.php.net//manual/en/function.strlen.php
		 * @see get_the_subtitle()
		 *
		 * @since 1.0.1
		 */
		public function plugin_compat_wordpress_seo( $title ) {
			/**
			 * This issue only arrises when breadcrumbs are placed inside of The Loop,
			 * so we'll first check to see if we're in The Loop, and if not, just bail
			 * on this altogether.
			 *
			 * @link http://codex.wordpress.org/Function_Reference/in_the_loop
			 *
			 * @since 1.0.1
			 */
			$in_the_loop = (bool) in_the_loop();
			if ( ! $in_the_loop ) {
				return $title;
			}

			/**
			 * Grab the post subtitle.
			 *
			 * Example: (string) "Subtitle"
			 *
			 * @see get_the_subtitle()
			 *
			 * @since 1.0.1
			 */
			$post_subtitle = get_the_subtitle();

			/**
			 * Grab the length of the post subtitle.
			 *
			 * We're also using a negative value of the legnth of the subtitle.
			 *
			 * Example: (int) 8
			 *
			 * @link http://us1.php.net//manual/en/function.strlen.php
			 *
			 * @since 1.0.1
			 */
			$post_subtitle_length = (int) strlen( $post_subtitle );
			$post_subtitle_length_neg = -1 * $post_subtitle_length;

			/**
			 * Grab the already filtered post title.
			 *
			 * Example: (string) "Post TitleSubtitle"
			 */
			$post_title = $title;

			/**
			 * Grab the length of the filtered post title.
			 *
			 * Example: (int) 18
			 */
			$post_title_length = (int) strlen( $post_title );

			/**
			 * Check for a few specific cases:
			 *
			 * 1. Does the subtitle of the current post equal the title of any of its ancestors?
			 * 2. Is the post title that's being checked empty?
			 *
			 * If so, then bail out on this.
			 *
			 * Example: If the subtitle of the page is "Features" and one of the parent breadcrumbs also
			 * has the name "Features", then we don't want to mess that up, so we'll make sure that we bail out
			 * early on this function.
			 *
			 * @since 1.0.1
			 */
			if ( ( $post_title == $post_subtitle ) || ( '' == $post_title ) ) {
				return $title;
			}

			/**
			 * Remove the subtitle from the "title" string so that it shows up properly.
			 *
			 * Example: If the post title that we've brought into the function is called "Post TitleSubtitle",
			 * then all we really want is the the title to say "Post Title", so we'll use the length of the subtitle
			 * to cut that many characters off of the end of the post title, and hope to end up with "Post Title".
			 *
			 * @see apply_filters()
			 * @link http://us2.php.net//manual/en/function.substr.php
			 *
			 * @since 1.0.1
			 */
			$post_title = substr( $post_title, 0, $post_subtitle_length_neg );
			$post_title = apply_filters( 'compat_wordpress_seo', $post_title );

			/**
			 * Make sure that the new title and its subtitle is the right string that
			 * we want to manipulate, by comparing the post title + subtitle against
			 * the original title that we brought into this function.
			 *
			 * Example: The original title brought in was "Post TitleSubtitle", so we'll
			 * make sure that "Post Title" + "Subtitle" is actually "Post TitleSubtitle".
			 *
			 * @since 1.0.1
			 */
			$reconstructed_title = $post_title . $post_subtitle;

			if ( $reconstructed_title == $title ) {
				return $post_title;
			}

			// else just return the title that was brought into the function
			return $title;
		} // end plugin_compat_wordpress_seo()

		/**
		 * Add default support for subtitles on posts and pages.
		 *
		 * @since 1.0.0
		 */
		public function add_subtitles_support() {
			/**
			 * Automatically enable subtitles support on posts.
			 *
			 * This can be overriden within themes.
			 *
			 * @see add_post_type_support()
			 * @link http://codex.wordpress.org/Function_Reference/add_post_type_support
			 * @see SUBTITLE_FEATURE_SUPPORT
			 *
			 * @since 1.0.0
			 */
			add_post_type_support( 'post', self::SUBTITLE_FEATURE_SUPPORT );

			/**
			 * Automatically enable subtitles support on pages.
			 *
			 * This can be overriden within themes.
			 *
			 * @see add_post_type_support()
			 * @link http://codex.wordpress.org/Function_Reference/add_post_type_support
			 * @see SUBTITLE_FEATURE_SUPPORT
			 *
			 * @since 1.0.0
			 */
			add_post_type_support( 'page', self::SUBTITLE_FEATURE_SUPPORT );
		} // end add_subtitles_support()

		/**
		 * Declare __clone as private to prevent cloning an instance of the class via `clone`.
		 *
		 * @link http://www.php.net/manual/en/language.oop5.cloning.php
		 * @access protected
		 *
		 * @since 1.0.0
		 */
		protected function __clone() {

		} // end method __clone()

		/**
		 * Prevent unserializing.
		 *
		 * @link http://php.net/function.unserialize
		 * @access public
		 *
		 * @since 1.0.0
		 */
		public function __wakeup() {
			// i18n won't work on this exception so there's no need to add it into the language pack
			throw new Exception( 'This Singleton cannot be unserialized.' );
		} // end method __wakeup()

		/**
		 * Make Subtitles available for translation.
		 *
		 * @link https://ulrich.pogson.ch/load-theme-plugin-translations
		 * @link http://ottopress.com/2013/language-packs-101-prepwork/
		 *
		 * @since 1.0.0
		 */
		public function load_subtitles_textdomain() {
			$domain = self::PLUGIN_SLUG;
			$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

			/**
			 * Load a .mo file into the text domain $domain.
			 *
			 * If the text domain already exists, the translations will be merged. If both
			 * sets have the same string, the translation from the original value will be taken.
			 *
			 * On success, the .mo file will be placed in the $l10n global by $domain
			 * and will be a MO object.
			 *
			 * @param string $domain Text domain. Unique identifier for retrieving translated strings.
			 * @param string $mofile Path to the .mo file.
			 * @return bool  True on success, false on failure.
			 *
			 * @since 1.0.0
			 */
			load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );

			/**
			 * Load a plugin's translated strings.
			 *
			 * If the path is not given then it will be the root of the plugin directory.
			 *
			 * The .mo file should be named based on the text domain with a dash, and then the locale exactly.
			 *
			 * @param string $domain          Unique identifier for retrieving translated strings
			 * @param string $deprecated      Use the $plugin_rel_path parameter instead.
			 * @param string $plugin_rel_path Optional. Relative path to WP_PLUGIN_DIR where the .mo file resides.
			 *
			 * @since 1.0.0
			 */
			load_plugin_textdomain( $domain, false, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );
		}

		/**
		 * Enqueue front-end scripts and styles.
		 *
		 * @access public
		 *
		 * @since 1.0.0
		 */
		public function subtitle_scripts() {
			/**
			 * Load in main Subtitles stylesheet
			 *
			 * wp_enqueue_style accepts
			 * - $handle Name of the stylesheet.
			 * - $src    Path to the stylesheet from the root directory of WordPress. Example: '/css/mystyle.css'.
			 * - $deps   An array of registered style handles this stylesheet depends on. Default empty array.
			 * - $ver    String specifying the stylesheet version number, if it has one. This parameter is used
			 *           to ensure that the correct version is sent to the client regardless of caching, and so
			 *           should be included if a version number is available and makes sense for the stylesheet.
			 * - $media  Optional. The media for which this stylesheet has been defined.
			 *           Default 'all'. Accepts 'all', 'aural', 'braille', 'handheld', 'projection', 'print',
			 *           'screen', 'tty', or 'tv'.
			 *
			 * @link http://codex.wordpress.org/Function_Reference/wp_enqueue_style
			 * @since 1.0.0
			 */
			wp_enqueue_style( self::PLUGIN_SLUG . '-style', plugins_url( 'assets/css/subtitles.css' , __FILE__ ), array(), self::VERSION );
		} // end subtitle_scripts()

		/**
		 * Output the subtitle
		 *
		 * @since 1.0.0
		 */
		public function the_subtitle( $title, $id ) {
			/**
			 * Which globals will we need?
			 *
			 * @since 1.0.0
			 */
			global $post;

			/**
			 * Check if $post is set. There's a chance that this can
			 * be NULL on search results pages with zero results.
			 *
			 * @link https://github.com/philiparthurmoore/Subtitles/issues/12
			 *
			 * @since 1.0.2
			 */
			if ( ! isset( $post ) ) {
				return $title;
			}

			/**
			 * Make sure we're not touching any of the titles in the Dashboard
			 * This filtering should only happen on the front end of the site.
			 *
			 * @since 1.0.0
			 */
			if ( is_admin() ) {
				return $title;
			}

			/**
			 * Bail early if no subtitle has been set for the post.
			 *
			 * @since 1.0.0
			 */
			$post_id = (int) absint( $post->ID ); // post ID should always be a non-negative integer
			$subtitle = (string) html_entity_decode( wp_unslash( esc_html( get_post_meta( $post_id, self::SUBTITLE_META_KEY, true ) ) ), ENT_QUOTES );

			if ( '' == $subtitle ) {
				return $title;
			}

			/**
			 * Bail if the title being filtered isn't the actual primary post title.
			 *
			 * This can happen when something is used within the loop that outputs titles,
			 * like navigation between single posts on a blog.
			 *
			 * $id should not be an object, and if it is then the single_post_title() filter is likely
			 * being invoked, so we'll need a check against that.
			 *
			 * Here's a more detailed explanation:
			 *
			 * 1. the_title() can be filtered with apply_filters( 'the_title', $title, $id ).
			 *    This means that the second argument needs to be an integer, which will be the ID of a post.
			 * 2. single_post_title() can be filtered with apply_filters( 'single_post_title', $_post->post_title, $_post ).
			 *    This means that the second argument needs to be the entire post object.
			 *
			 * So if we're checking $id and it's an object, then there's a good chance that single_post_title()
			 * is being used and we need to reassign $id to be ID of the object WP_Post.
			 *
			 * @see the_title()
			 * @see single_post_title()
			 * @see get_the_ID()
			 * @see is_object()
			 *
			 * @since 1.0.0
			 */
			if ( isset( $id ) && is_object( $id ) ) { // single_post_title() is being used.
				$id = $id->ID; // grab the ID from the post object.
			}
			if ( isset( $post->post_title ) && $id != get_the_ID() ) {
				return $title;
			}

			/**
			 * Make sure we're in The Loop. If a theme maker wants to create
			 * a custom loop via WP_Query, then he can filter subtitle_view_supported.
			 *
			 * @see in_the_loop()
			 * @link http://codex.wordpress.org/Function_Reference/in_the_loop
			 *
			 * @since 1.0.0
			 */
			$subtitle_view_supported = true;

			$in_the_loop = (bool) in_the_loop();
			if ( ! $in_the_loop ) {
				$subtitle_view_supported = false;
			}

			/**
			 * Allow subtitle views to be filtered by theme developers.
			 *
			 * @see apply_filters()
			 * @link http://codex.wordpress.org/Function_Reference/apply_filters
			 *
			 * @since 1.0.0
			 */
			$subtitle_view_supported = (bool) apply_filters( 'subtitle_view_supported', $subtitle_view_supported );

			/**
			 * If no subtitle support is active for the given view
			 * then simply return the post title.
			 *
			 * @since 1.0.0
			 */
			if ( ! $subtitle_view_supported ) {
				return $title;
			}

			/**
			 * Bail if the current post type doesn't support Subtitles.
			 *
			 * The good news here is that if Subtitles have been entered in for a user,
			 * but support for a specific post type has been removed, then the subtitles
			 * won't be displayed on the front-end of the site but will still be retained
			 * in the database. This is useful for child themes and such who want to override
			 * the look and feel of a theme that features subtitles.
			 *
			 * @since 1.0.0
			 */
			if ( ! post_type_supports( $post->post_type, self::SUBTITLE_FEATURE_SUPPORT ) ) {
				return $title;
			}

			/**
			 * Let theme authors modify the subtitle markup, in case spans aren't appropriate
			 * for what they are trying to do with their themes.
			 *
			 * The reason that spans are being used is because HTML does not have a dedicated
			 * mechanism for marking up subheadings, alternative titles, or taglines. There
			 * are suggested alternatives from the World Wide Web Consortium (W3C); among them
			 * are spans, which work well for what we're trying to do with titles in WordPress.
			 * See the linked documentation for more information.
			 *
			 * @link http://www.w3.org/html/wg/drafts/html/master/common-idioms.html#sub-head
			 *
			 * @since 1.0.0
			 */
			$subtitle_markup = apply_filters(
				'subtitle_markup',
				array(
					'before' => '<span class="entry-subtitle">',
					'after'  => '</span>',
				)
			);

			$subtitle = $subtitle_markup[ 'before' ] . $subtitle . $subtitle_markup[ 'after' ];

			/**
			 * Put together the final title and subtitle set
			 *
			 * @since 1.0.0
			 */
			$title = $title . ' ' . $subtitle;

			/**
			 * Filter the post subtitle, if necessary.
			 *
			 * @param string $title The post title.
			 * @param int    $id    The post ID.
			 *
			 * @since 1.0.0
			 */
			return apply_filters( 'the_subtitle', $title );
		}

		/**
		 * Get the Subtitle.
		 *
		 * This is a helper method used to grab the subtitle for any given post,
		 * which theme authors can use with the template tag get_the_subtitle().
		 *
		 * @since 1.0.0
		 */
		public static function get_the_subtitle( $post = 0 ) {
			/**
			 * Bail early if no subtitle has been set for the post.
			 *
			 * @since 1.0.0
			 */
			$post = get_post( $post ); // this will be returned as an object or NULL
			$post_id = ( isset( $post ) ) ? $post->ID : 0; // post ID should always be a non-negative integer
			$subtitle = (string) html_entity_decode( wp_unslash( esc_html( get_post_meta( $post_id, self::SUBTITLE_META_KEY, true ) ) ), ENT_QUOTES );

			if ( '' == $subtitle ) {
				return $subtitle;
			}

			/**
			 * Filter the post subtitle.
			 *
			 * @since 1.0.0
			 *
			 * @param string $subtitle The post subtitle.
			 * @param int    $id    The post ID.
			 */
			return apply_filters( 'the_subtitle', $subtitle );
		} // end get_the_subtitle()
	} // end class Subtitles
} // end class Subtitles check
