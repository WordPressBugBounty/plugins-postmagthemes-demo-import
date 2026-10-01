<?php
/**
 * Class for declaring the content importer used in the PostmagThemes Demo Import plugin
 *
 * @package pmdi
 */

namespace PMDI;

class Importer {
	/**
	 * The importer class object used for importing content.
	 *
	 * @var object
	 */
	private $importer;

	/**
	 * Time in milliseconds, marking the beginning of the import.
	 *
	 * @var float
	 */
	private $microtime;

	/**
	 * The instance of the PMDI\Logger class.
	 *
	 * @var object
	 */
	public $logger;

	/**
	 * The instance of the PostmagThemes Demo Import class.
	 *
	 * @var object
	 */
	private $pmdi;

	/**
	 * Number of items in the content file and the item currently being processed.
	 * Used for the import progress bar.
	 *
	 * @var int
	 */
	private $total_items = 0;
	private $item_index  = 0;
	private $last_percent = -1;

	/**
	 * Constructor method.
	 *
	 * @param array  $importer_options Importer options.
	 * @param object $logger           Logger object used in the importer.
	 */
	public function __construct( $importer_options = array(), $logger = null ) {
		// Include files that are needed for WordPress Importer v2.
		$this->include_required_files();

		// Set the WordPress Importer v2 as the importer used in this plugin.
		// More: https://github.com/humanmade/WordPress-Importer.
		$this->importer = new WXRImporter( $importer_options );

		// Set logger to the importer.
		$this->logger = $logger;
		if ( ! empty( $this->logger ) ) {
			$this->set_logger( $this->logger );
		}

		// Get the PMDI (main plugin class) instance.
		$this->pmdi = OneClickDemoImport::get_instance();
	}


	/**
	 * Include required files.
	 */
	private function include_required_files() {
		if ( ! class_exists( '\WP_Importer' ) ) {
			require ABSPATH . '/wp-admin/includes/class-wp-importer.php';
		}
	}


	/**
	 * Imports content from a WordPress export file.
	 *
	 * @param string $data_file path to xml file, file with WordPress export data.
	 */
	public function import( $data_file ) {
		$this->importer->import( $data_file );
	}


	/**
	 * Set the logger used in the import
	 *
	 * @param object $logger logger instance.
	 */
	public function set_logger( $logger ) {
		$this->importer->set_logger( $logger );
	}


	/**
	 * Get all protected variables from the WXR_Importer needed for continuing the import.
	 */
	public function get_importer_data() {
		return $this->importer->get_importer_data();
	}


	/**
	 * Sets all protected variables from the WXR_Importer needed for continuing the import.
	 *
	 * @param array $data with set variables.
	 */
	public function set_importer_data( $data ) {
		$this->importer->set_importer_data( $data );
	}


	/**
	 * Import content from an WP XML file.
	 *
	 * @param string $import_file_path Path to the import file.
	 */
	public function import_content( $import_file_path ) {
		$this->microtime = microtime( true );

		// Increase PHP max execution time. Just in case, even though the AJAX calls are only 25 sec long.
		set_time_limit( apply_filters( 'pt-pmdi/set_time_limit_for_demo_data_import', 300 ) );

		// Disable import of authors.
		add_filter( 'wxr_importer.pre_process.user', '__return_false' );

		// Track the progress of the content import (runs before the new AJAX check below).
		$this->total_items  = $this->count_content_items( $import_file_path );
		$this->item_index   = 0;
		$this->last_percent = -1;
		add_filter( 'wxr_importer.pre_process.post', array( $this, 'track_content_progress' ), 5 );

		// Check, if we need to send another AJAX request and set the importing author to the current user.
		add_filter( 'wxr_importer.pre_process.post', array( $this, 'new_ajax_request_maybe' ) );

		// Disables generation of multiple image sizes (thumbnails) in the content import step.
		if ( ! apply_filters( 'pt-pmdi/regenerate_thumbnails_in_content_import', true ) ) {
			add_filter( 'intermediate_image_sizes_advanced', '__return_null' );
		}

		// Import content.
		if ( ! empty( $import_file_path ) ) {
			ob_start();
				$this->import( $import_file_path );
			$message = ob_get_clean();
		}

		// Return any error messages for the front page output (errors, critical, alert and emergency level messages only).
		return $this->logger->error_output;
	}


	/**
	 * Count the posts, pages, menu items and images in the content file.
	 *
	 * @param string $import_file_path Path to the import file.
	 * @return int
	 */
	private function count_content_items( $import_file_path ) {
		if ( empty( $import_file_path ) || ! is_readable( $import_file_path ) ) {
			return 0;
		}

		$count  = 0;
		$handle = fopen( $import_file_path, 'r' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fopen

		if ( $handle ) {
			while ( ! feof( $handle ) ) {
				$count += substr_count( (string) fgets( $handle ), '<item>' );
			}
			fclose( $handle ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fclose
		}

		return $count;
	}

	/**
	 * Update the progress bar while the content items are imported.
	 * Content import covers 5% - 90% of the whole import.
	 * Every new AJAX call parses the file from the start, so the item index is the position in the file.
	 *
	 * @param array $data Current post data.
	 * @return array
	 */
	public function track_content_progress( $data ) {
		$this->item_index++;

		if ( $this->total_items > 0 ) {
			$percent = 5 + (int) floor( 85 * min( $this->item_index, $this->total_items ) / $this->total_items );

			if ( $percent !== $this->last_percent ) {
				$this->last_percent = $percent;
				Helpers::set_import_progress( $percent, 'content' );
			}
		}

		return $data;
	}

	/**
	 * Check if we need to create a new AJAX request, so that server does not timeout.
	 *
	 * @param array $data current post data.
	 * @return array
	 */
	public function new_ajax_request_maybe( $data ) {
		$time = microtime( true ) - $this->microtime;

		// We should make a new ajax call, if the time is right.
		if ( $time > apply_filters( 'pt-pmdi/time_for_one_ajax_call', 25 ) ) {
			$response = array(
				'status'  => 'newAJAX',
				'message' => 'Time for new AJAX request!: ' . $time,
			);

			// Add any output to the log file and clear the buffers.
			$message = ob_get_clean();

			// Add any error messages to the frontend_error_messages variable in PMDI main class.
			if ( ! empty( $message ) ) {
				$this->pmdi->append_to_frontend_error_messages( $message );
			}

			// Add message to log file.
			$log_added = Helpers::append_to_file(
				__( 'New AJAX call!', 'pt-pmdi' ) . PHP_EOL . $message,
				$this->pmdi->get_log_file_path(),
				''
			);

			// Set the current importer stat, so it can be continued on the next AJAX call.
			$this->set_current_importer_data();

			// Flush the deferred term and comment counts before this AJAX call ends.
			// Otherwise they are lost and terms imported in this call (e.g. nav menus)
			// keep a count of 0, which makes WordPress treat those menus as empty.
			wp_defer_term_counting( false );
			wp_defer_comment_counting( false );

			// Send the request for a new AJAX call.
			wp_send_json( $response );
		}

		// Set importing author to the current user.
		// Fixes the [WARNING] Could not find the author for ... log warning messages.

		if ( isset( $_SESSION['imprter_user_id'] ) ) {
			$importUserID = get_userdata( absint( $_SESSION['imprter_user_id'] ) );
			$userLoginIm  = isset( $importUserID->user_login ) ? $importUserID->user_login : '';
			$userIdIm     = isset( $importUserID->ID ) ? $importUserID->ID : '';
		} else {
			$current_user_obj = wp_get_current_user();
			$userLoginIm      = $current_user_obj->user_login;
			$userIdIm         = $current_user_obj->ID;
		}
		$data['post_author'] = $userLoginIm;
		return $data;
	}


	/**
	 * Set current state of the content importer, so we can continue the import with new AJAX request.
	 */
	private function set_current_importer_data() {
		$data = array_merge( $this->pmdi->get_current_importer_data(), $this->get_importer_data() );

		Helpers::set_pmdi_import_data_transient( $data );
	}
}
