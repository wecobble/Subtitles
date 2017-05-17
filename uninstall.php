<?php
/**
 * Subtitles uninstall functions.
 *
 * This file is triggered when Subtitles is uninstalled. It's mostly here for demonstration
 * purposes and also in case there is a future need for it.
 *
 * Let's think a little bit about the way that Subtitles uses data. Users enter subtitles
 * into their posts, pages, and subtitles-supported custom post types. These subtitles
 * are what I would consider non-trivial post meta. They contain content that's relevant
 * to users' posts, so I do not think that automatically deleting them is a wise move.
 *
 * Plugin deletion also isn't a fair predictor that the user wants to remove all of his subtitles.
 * What if he's uninstalling the plugin in order to upload a fresh copy? What if an initial install
 * didn't go as well as planned and the user wants to wipe out Subtitles before uploading a fresh copy?
 * I'm worried that if someone uninstalls the plugin and then installs it again only to find all of their
 * post titles gone, they will be very unhappy.
 *
 * In the future I could see there being a need for people to be able to delete all of their subtitles
 * in one fell swoop, but right now I do not think that this is necessary.
 *
 * @since   1.0.0
 * @package Subtitles
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { // if uninstall hasn't been called, then bail
	exit;
}
