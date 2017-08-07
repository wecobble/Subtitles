<?php

/**
 * Subtitles_Quick_Edit_Support enables quick editing of subtitles on
 * the post edit screens. It uses the same 'save_post' hook as
 * Subtitles_Admin.
 *
 * Quick edit support can be turned off by using the
 * subtitles_quick_edit_support hook. Default is true (enabled);
 */
class Subtitles_Quick_Edit_Support {

	/**
	 * Subscribes to the quick edit custom box to render the markup.
	 */
	public function register() {
		if ( apply_filters( 'subtitles_quick_edit_support', true ) ) {
			add_action(
				'quick_edit_custom_box', array( $this, 'render' ), 100
			);
		}
	}

	/** Helpers */

	/**
	 * Renders the subtitle form fields markup and nonce.
	 *
	 * NOTE: The value of the subtitle field is intentionally left at '-' instead of
	 * populating it with the stored value post meta. The wp-admin/js/inline-edit-post.js in
	 * Core hardcodes the list of fields used in the quick edit form.
	 *
	 * There is no easy way to override this without modifying Core.
	 *
	 * The special '-' value will be ignored by the save hook. All other
	 * values are valid and are saved to post meta as earlier.
	 */
	function render() {
		$subtitle_field = $this->get_field_name();

		wp_nonce_field(
			$this->get_nonce_action(),
			$this->get_nonce_name(),
			true,
			true
		);

		?>
		<fieldset class="inline-edit-col-right">
			<div class="inline-edit-col">
				<label>
					<span class="title"><?php _e( 'Subtitle' ); ?></span>
					<span class="input-text-wrap">
					<input type="text" name="<?php _e( $subtitle_field ); ?>" class="ptitle"
						value="-" />
					</span>
				</label>
			</div>
		</fieldset>
		<?php
	}

	/**
	 * The same nonce action from subtitles admin
	 */
	function get_nonce_action() {
		return basename( __DIR__ . '/class-subtitles-admin.php' );
	}

	/**
	 * The same nonce name from subtitles admin
	 */
	function get_nonce_name() {
		return Subtitles_Admin::SUBTITLE_NONCE_NAME;
	}

	/**
	 * The same field name from subtitles admin
	 */
	function get_field_name() {
		return Subtitles_Admin::SUBTITLE_META_KEY;
	}

}
