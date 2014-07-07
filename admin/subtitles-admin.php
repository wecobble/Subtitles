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
 * Checks for the existence of Subtitles_Admin before defining it.
 *
 * @link http://www.php.net//manual/en/function.class-exists.php
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'Subtitles_Admin' ) ) {
	/**
	 * Define Subtitles_Admin class.
	 *
	 * Within classes constants and variables are called properties.
	 * Within classes functions are called methods.
	 *
	 * @link http://php.net/manual/en/language.oop5.php
	 *
	 * @since 1.0.0
	 */
	class Subtitles_Admin extends Subtitles {
		/**
		 * Hold the singleton instance of Subtitles_Admin.
		 *
		 * @since 1.0.3
		 */
		private static $instance = null;

		public static function getinstance() {
			if ( ! self::$instance ) {
				self::$instance = new Subtitles_Admin;
			}

			return self::$instance;
		} // end method getinstance()

		protected function __clone() {}

		public function __wakeup() {
			throw new Exception( 'This Singleton cannot be unserialized.' );
		}

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
			 * Build the subtitle input field.
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
			 * @see add_action()
			 * @link http://codex.wordpress.org/Function_Reference/add_action
			 *
			 * @since 1.0.0
			 */
			add_action( 'edit_form_after_title', array( &$this, 'build_subtitle_input' ) );

			/**
			 * Validate and update the subtitle input field.
			 *
			 * @see add_action()
			 * @link http://codex.wordpress.org/Function_Reference/add_action
			 * @link http://codex.wordpress.org/Data_Validation
			 *
			 * @since 1.0.0
			 */
			add_action( 'save_post',             array( &$this, 'update_subtitle_data' ), 10, 3 );

			/**
			 * Enqueue backend scripts and styles.
			 *
			 * @see add_action()
			 * @link http://codex.wordpress.org/Function_Reference/add_action
			 *
			 * @since 1.0.0
			 */
			add_action( 'admin_enqueue_scripts', array( &$this, 'subtitle_admin_scripts' ) );
		} // end method __construct()

		/**
		 * Build the subtitle input field.
		 *
		 * @access public
		 *
		 * @since 1.0.0
		 */
		public function build_subtitle_input( $post ) {
			/**
			 * Bail if we're not on an admin screen
			 *
			 * @since 1.0.0
			 */
			if ( ! is_admin() ) {
				return;
			}

			/**
			 * Bail if the current post type doesn't support subtitles.
			 *
			 * By default both posts and pages automatically support subtitles.
			 *
			 * Support for subtitles is kicked off in Subtitles->add_subtitles_support();
			 *
			 * @since 1.0.0
			 */
			$screen = (object) get_current_screen();
			$post_type_support = post_type_supports( $screen->post_type, self::SUBTITLE_FEATURE_SUPPORT );

			if ( ! $post_type_support ) {
				return;
			}

			$post_id = (int) absint( $post->ID ); // post ID should always be a non-negative integer

			/**
			 * get_post_meta() takes the following arguments:
			 * - $post_id for the ID of the post
			 * - $key for the meta key to retrieve
			 * - $single for whether or not to return a single value
			 *   (in effect either choosing an array or a single value to be returned)
			 */
			$subtitle = (string) get_post_meta( $post_id, self::SUBTITLE_META_KEY, true );

			// nonces ("number used once") are used to protect the custom meta box forms from being misused.
			wp_nonce_field(
				basename( __FILE__ ), // $action, Action name
				self::SUBTITLE_NONCE_NAME // $name, Nonce name
			);
			?>

			<div id="subtitlediv">
				<div id="subtitlewrap">
					<label class="screen-reader-text" id="subtitle-prompt-text" for="<?php echo esc_attr( self::SUBTITLE_META_KEY ); ?>">
						<?php echo esc_html( apply_filters( 'enter_subtitle_here', __( 'Enter subtitle here', 'subtitles' ), $post ) ); ?>
					</label><!-- #subtitle-prompt-text -->
					<input type="text" name="<?php echo esc_attr( self::SUBTITLE_META_KEY ); ?>" size="30" value="<?php echo esc_attr( htmlspecialchars( $subtitle ) ); ?>" id="subtitle" autocomplete="off" />
				</div><!-- #subtitlewrap -->
			</div><!-- #subtitlediv --><?php

			do_action( 'edit_form_after_subtitle', $post );
		} // end build_subtitle_input()

		/**
		 * Validate and save custom metadata associated with Subtitles.
		 *
		 * @link http://codex.wordpress.org/Data_Validation
		 * @access public
		 *
		 * @since 1.0.0
		 */
		public function update_subtitle_data( $post_id, $post, $update ) {
			/**
			 * Check current save status of post
			 */
			$is_post_autosave = (bool) wp_is_post_autosave( $post_id ); // Autosave, found in wp-includes/revisison.php
			$is_post_revision = (bool) wp_is_post_revision( $post_id ); // Revision, found in wp-includes/revision.php

			/**
			 * Check current subtitle nonce status of post
			 */
			$is_nonce_set = (bool) isset( $_POST[ self::SUBTITLE_NONCE_NAME ] );
			/**
			 * If the nonce is set then check if it's verified.
			 * This gets rid of undefined index notices for _subtitle_data_nonce.
			 */
			if ( $is_nonce_set ) {
				$nonce = sanitize_key( $_POST[ self::SUBTITLE_NONCE_NAME ] );
				$is_verified_nonce = (bool) wp_verify_nonce( $nonce, basename( __FILE__ ) );
			}
			else {
				$is_verified_nonce = null;
			}

			/**
			 * Bail if the save status or nonce status of the post isn't correct
			 */
			if ( $is_post_autosave || $is_post_revision || ! $is_verified_nonce || ! $is_nonce_set ) {
				return;
			}

			/**
			 * Bail if the current user doesn't have permission to edit the current post type
			 */
			$post_type_object = (object) get_post_type_object( $post->post_type );
			$can_edit_post_type = (bool) current_user_can( $post_type_object->cap->edit_post, $post_id );

			if ( ! $can_edit_post_type ) {
				return;
			}

			/**
			 * Data validation and sanitization before inputting it into the database.
			 *
			 * Always remember to validate on both input and output. We're using wp_kses
			 * here, and only allowing users to input italicized and bold text in their
			 * subtitles. This may change at a later time, but I believe that in this case,
			 * less is more, and users should only be able to enter plain text, or very simple
			 * bold and italic markup for their subtitles.
			 *
			 * @link http://codex.wordpress.org/Function_Reference/wp_kses
			 * @since 1.0.0
			 */
			$subtitles_allowed_tags = array(
				'i' => array(), // italicized text
				'em' => array(), // emphasized text
				'strong' => array(), // strong text
			);
			// grab the subtitles meta key
			$subtitle_meta_key = (string) self::SUBTITLE_META_KEY;
			// If a new subtitle has been posted, then use it; otherwise assign an empty value to the subtitle
			$new_subtitle = (string) isset( $_POST[ $subtitle_meta_key ] ) ? wp_kses( $_POST[ $subtitle_meta_key ], $subtitles_allowed_tags ) : null;
			// Get the current subtitle assigned to the post
			$current_subtitle = (string) wp_kses( get_post_meta( $post_id, $subtitle_meta_key, true ), $subtitles_allowed_tags );

			/**
			 * Add meta when key is new or empty for post
			 *
			 * If the current subtitle is empty (hasn't been entered yet) then set it
			 */
			if ( $current_subtitle && '' === $current_subtitle ) {
				add_post_meta(
					$post_id,				// $post_id the post ID
					$subtitle_meta_key,		// $meta_key the metadata name
					$new_subtitle,			// $meta_value the metadata value
					false					// $unique, if this is set to true then the key cannot be re-used
				);
			}

			/**
			 * If the current meta value and the newly entered meta value are different,
			 * then update the post meta
			 */
			if ( ( $new_subtitle && $new_subtitle !== $current_subtitle ) ) {
				update_post_meta(
					$post_id,				// $post_id the post ID
					$subtitle_meta_key,		// $meta_key the metadata name
					$new_subtitle			// $meta_value the metadata value
				);
			}

			/**
			 * If the new subtitle entered is blank
			 */
			if ( '' === $new_subtitle && $current_subtitle ) {
			 	delete_post_meta(
			 		$post_id,				// $post_id the post ID
			 		$subtitle_meta_key,		// $meta_key the metadata name
			 		$new_subtitle			// $meta_value the metadata value
			 	);
		 	}
		} // end update_subtitle_data()

		/**
		 * Enqueue admin scripts and styles.
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function subtitle_admin_scripts( $hook ) {
			/**
			 * Bail on these scripts and styles if we're not on a post edit screen
			 * or an add new post screen.
			 *
			 * @since 1.0.0
			 */
			if ( 'post-new.php' != $hook && 'post.php' != $hook ) {
				return;
			}

			/**
			 * Load in main Subtitles admin stylesheet
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
			wp_enqueue_style( self::PLUGIN_SLUG . '-admin-style', plugins_url( 'assets/css/subtitles.css' , __FILE__ ), array(), self::VERSION );

			/**
			 * Load in main Subtitles scripts
			 *
			 * wp_enqueue_script accepts
			 * - $handle    Name of the script.
			 * - $src       Path to the script from the root directory of WordPress. Example: '/js/myscript.js'.
			 * - $deps      An array of registered handles this script depends on. Default empty array.
			 * - $ver       Optional. String specifying the script version number, if it has one. This parameter
			 *              is used to ensure that the correct version is sent to the client regardless of caching,
			 *              and so should be included if a version number is available and makes sense for the script.
			 * - $in_footer Optional. Whether to enqueue the script before </head> or before </body>.
			 *              Default 'false'. Accepts 'false' or 'true'.
			 *
			 * @link http://codex.wordpress.org/Function_Reference/wp_enqueue_script
			 * @since 1.0.0
			 */
			wp_enqueue_script( self::PLUGIN_SLUG . '-admin-scripts', plugins_url( 'assets/js/subtitles.js' , __FILE__ ), array( 'jquery' ), self::VERSION, true );
		} // end subtitle_admin_scripts()
	} // end class Subtitles_Admin
} // end class Subtitles_Admin check