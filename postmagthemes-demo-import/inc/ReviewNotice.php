<?php
/**
 * Ask for a WordPress.org review after a successful demo import.
 *
 * Two touch points:
 * 1. A short "enjoying it?" box appended to the import success message.
 * 2. A dismissible admin notice a few days later, shown only on the
 *    Dashboard, Plugins screen and the import page, and only to users who
 *    can import. "Maybe later" hides it for 14 days; "I already did" and
 *    "Don't ask again" hide it for good.
 *
 * @package pmdi
 */

namespace PMDI;

/**
 * Review request handler.
 */
class ReviewNotice {

	/** WordPress.org review URL. */
	const REVIEW_URL = 'https://wordpress.org/support/plugin/postmagthemes-demo-import/reviews/#new-post';

	/** Option: timestamp of the first successful import. */
	const OPTION_IMPORTED = 'pmdi_review_first_import';

	/** Option: 'done' or a timestamp to wait until. */
	const OPTION_STATE = 'pmdi_review_state';

	/** Days to wait after the first successful import before the admin notice. */
	const DELAY_DAYS = 3;

	/** Days to snooze on "Maybe later". */
	const SNOOZE_DAYS = 14;

	/**
	 * Register hooks.
	 */
	public static function init() {
		add_action( 'admin_notices', array( __CLASS__, 'maybe_show_notice' ) );
		add_action( 'wp_ajax_pmdi_review_dismiss', array( __CLASS__, 'ajax_dismiss' ) );
	}

	/**
	 * Record a successful import (only the first one counts).
	 */
	public static function record_successful_import() {
		if ( ! get_option( self::OPTION_IMPORTED ) ) {
			update_option( self::OPTION_IMPORTED, time(), false );
		}
	}

	/**
	 * Whether the user has already reviewed or opted out.
	 *
	 * @return bool
	 */
	private static function is_done() {
		return 'done' === get_option( self::OPTION_STATE );
	}

	/**
	 * HTML box appended to the import success message.
	 *
	 * @return string
	 */
	public static function success_message_html() {
		if ( self::is_done() ) {
			return '';
		}

		return sprintf(
			'<div class="notice notice-info pmdi-review-box"><p>%1$s</p><p><a href="%2$s" class="button button-primary" target="_blank" rel="noopener noreferrer">%3$s</a></p></div>',
			esc_html__( 'Did the import go well? A quick 5-star review on WordPress.org helps other users find PostmagThemes Demo Import, and it means a lot to our small team. Thank you!', 'pt-pmdi' ),
			esc_url( self::REVIEW_URL ),
			esc_html__( 'Leave a review', 'pt-pmdi' )
		);
	}

	/**
	 * Show the admin notice when it is due.
	 */
	public static function maybe_show_notice() {
		if ( ! current_user_can( 'import' ) || self::is_done() ) {
			return;
		}

		$imported = (int) get_option( self::OPTION_IMPORTED );
		if ( ! $imported || time() < $imported + self::DELAY_DAYS * DAY_IN_SECONDS ) {
			return;
		}

		$state = get_option( self::OPTION_STATE );
		if ( is_numeric( $state ) && time() < (int) $state ) {
			return;
		}

		// Only on screens where a notice is not intrusive.
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( ! $screen ) {
			return;
		}
		$allowed = array( 'dashboard', 'plugins', 'appearance_page_pt-one-click-demo-import' );
		if ( ! in_array( $screen->id, $allowed, true ) ) {
			return;
		}

		$nonce = wp_create_nonce( 'pmdi-review-dismiss' );
		?>
		<div class="notice notice-info pmdi-review-notice" data-nonce="<?php echo esc_attr( $nonce ); ?>">
			<p>
				<strong><?php esc_html_e( 'Thanks for using PostmagThemes Demo Import!', 'pt-pmdi' ); ?></strong>
				<?php esc_html_e( 'If it helped you set up your site, would you take a minute to leave a review on WordPress.org? It really helps.', 'pt-pmdi' ); ?>
			</p>
			<p>
				<a href="<?php echo esc_url( self::REVIEW_URL ); ?>" class="button button-primary pmdi-review-action" data-action="done" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Sure, leave a review', 'pt-pmdi' ); ?></a>
				<a href="#" class="button pmdi-review-action" data-action="later"><?php esc_html_e( 'Maybe later', 'pt-pmdi' ); ?></a>
				<a href="#" class="pmdi-review-action" data-action="done" style="margin-left:8px;"><?php esc_html_e( 'I already did', 'pt-pmdi' ); ?></a>
				<a href="#" class="pmdi-review-action" data-action="done" style="margin-left:8px;"><?php esc_html_e( "Don't ask again", 'pt-pmdi' ); ?></a>
			</p>
		</div>
		<script>
		( function () {
			var box = document.querySelector( '.pmdi-review-notice' );
			if ( ! box ) { return; }
			box.addEventListener( 'click', function ( e ) {
				var link = e.target.closest( '.pmdi-review-action' );
				if ( ! link ) { return; }
				if ( '#' === link.getAttribute( 'href' ) ) { e.preventDefault(); }
				var data = new FormData();
				data.append( 'action', 'pmdi_review_dismiss' );
				data.append( 'security', box.getAttribute( 'data-nonce' ) );
				data.append( 'state', link.getAttribute( 'data-action' ) );
				fetch( <?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>, { method: 'POST', credentials: 'same-origin', body: data } );
				box.style.display = 'none';
			} );
		} )();
		</script>
		<?php
	}

	/**
	 * AJAX: store the user's choice.
	 */
	public static function ajax_dismiss() {
		check_ajax_referer( 'pmdi-review-dismiss', 'security' );

		if ( ! current_user_can( 'import' ) ) {
			wp_send_json_error();
		}

		$state = isset( $_POST['state'] ) ? sanitize_key( wp_unslash( $_POST['state'] ) ) : '';

		if ( 'later' === $state ) {
			update_option( self::OPTION_STATE, time() + self::SNOOZE_DAYS * DAY_IN_SECONDS, false );
		} else {
			update_option( self::OPTION_STATE, 'done', false );
		}

		wp_send_json_success();
	}
}
