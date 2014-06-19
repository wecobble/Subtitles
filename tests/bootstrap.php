<?php
/**
 * Subtitles Unit Tests Bootstrap
 *
 * @package Subtitles
 *
 * @since 1.0.2
 */

/**
 * Turn on error reporting.
 *
 * @link http://www.php.net//manual/en/function.error-reporting.php
 *
 * @since 1.0.2
 */
error_reporting( E_ALL ); // Report all PHP Errors.
ini_set( 'error_reporting', E_ALL ); // Same as above.
ini_set( 'display_errors', 'on' ); // Display errors.

/**
 * PHPUnit by defaults disables Xdebug backtrace, but we'll put this
 * here to be explicit about it.
 *
 * @link http://xdebug.org/docs/all_functions#xdebug_disable
 *
 * @since 1.0.2
 */
if ( function_exists( 'xdebug_disable' ) ) {
	xdebug_disable();
} // end if xdebug_disable check

/**
 * Output a little bit of helpful information about the testing suite,
 * which is the same information that we use within the main plugin
 * file. For the version, we're just keeping it in line with the version
 * of Subtitles. The unit testing suite and the plugin are all the same thing
 * as far as versioning goes, but we won't do version bumps on Subtitles
 * for improvements to the testing suite, since this isn't something that gets
 * shipped with the public plugin on WordPress.org.
 *
 * @since 1.0.2
 */
echo 'Subtitles WordPress Plugin Unit Testing Suite' . PHP_EOL;
echo 'Author: Philip Arthur Moore' . PHP_EOL;
echo 'License: GNU General Public License v2 or later' . PHP_EOL . PHP_EOL;

/**
 * Define Subtitles plugin directory.
 * This should output (something)/(something)/subtitles/
 *
 * @since 1.0.2
 */
define( 'SUBTITLES_PLUGIN_DIR', dirname( dirname( __FILE__ ) ) . '/'  );

/**
 * Define Subtitles unit tests directory.
 * This should output (something)/(something)/subtitles/tests/
 *
 * @since 1.0.2
 */
define( 'SUBTITLES_TESTS_DIR', dirname( __FILE__ ) ) . '/';

/**
 * Activate Subtitles in WordPress so that it can be tested.
 *
 * After this, the value for $GLOBALS[ 'wp_tests_options' ] should be 'subtitles/subtitles.php'.
 *
 * @since 1.0.2
 */
$GLOBALS[ 'wp_tests_options' ] = array(
	'active_plugins' => array( basename( dirname( dirname( __FILE__ ) ) ) . '/subtitles.php' ),
);

/**
 * If the development repository for WordPress has been defined, then use it.
 * The constant for that will be WP_DEVELOP_DIR. If the constant isn't defined,
 * then assume that Subtitles has been installed in a checkout (git or svn) of
 * the WordPress development version.
 *
 * getenv( 'WP_DEVELOP_DIR' ) is a boolean.
 *
 * @since 1.0.2
 */
if ( false !== getenv( 'WP_DEVELOP_DIR' ) ) {
	// functions used before loading WordPress
	require getenv( 'WP_DEVELOP_DIR' ) . '/tests/phpunit/includes/functions.php';
	// Installs WordPress for running the tests and loads WordPress and the test libraries
	require getenv( 'WP_DEVELOP_DIR' ) . '/tests/phpunit/includes/bootstrap.php';
} else {
	// functions used before loading WordPress
	require '../../../../tests/phpunit/includes/functions.php';
	// Installs WordPress for running the tests and loads WordPress and the test libraries
	require '../../../../tests/phpunit/includes/bootstrap.php';
}