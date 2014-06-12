<?php
/**
 * Custom template tags for themers.
 *
 * Subtitles automatically outputs subtitles with post titles, but if for some
 * reason a themer would like to unfilter post titles and use subtitles in other
 * locations throughout a theme, then let's allow them to do that and also
 * change the markup for the subtitles if spans aren't appropriate for the given
 * content layout for the theme on which they are working.
 *
 * @package Subtitles
 *
 * @since 1.0.0
 */

if ( ! function_exists( 'the_subtitle' ) ) {
	/**
	 * The Subtitle
	 *
	 * Display or retrieve the current post subtitle with optional content.
	 *
	 * This works exactly the same way that the_title() works in WordPress.
	 *
	 * @param string       $before Optional. Content to prepend to the subtitle.
	 * @param string       $after Optional. Content to append to the subtitle.
	 * @param bool         $echo Optional, default to true.Whether to display or return.
	 * @return null|string Null on no subtitle. String if $echo parameter is false.
	 *
	 * @since 1.0.0
	 */
	function the_subtitle( $before = '', $after = '', $echo = true ) {
		$subtitle = get_the_subtitle();

		if ( strlen( $subtitle ) == 0 )
			return;

		$subtitle = $before . $subtitle . $after;

		if ( $echo )
			echo $subtitle;
		else
			return $subtitle;
	} // end the_subtitle()
} // end the_subtitle check

if ( ! function_exists( 'get_the_subtitle' ) ) {
	/**
	 * Retrieve post subtitle.
	 *
	 * @since 1.0.0
	 */
	function get_the_subtitle( $post = 0 ) {
		$subtitle = Subtitles::get_the_subtitle( $post );
		return $subtitle;
	} // end get_the_subtitle()
} // end get_the_subtitle check