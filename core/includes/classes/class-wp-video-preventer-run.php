<?php

// Exit if accessed directly.
if (! defined('ABSPATH')) exit;

/**
 * HELPER COMMENT START
 * 
 * This class is used to bring your plugin to life. 
 * All the other registered classed bring features which are
 * controlled and managed by this class.
 * 
 * Within the add_hooks() function, you can register all of 
 * your WordPress related actions and filters as followed:
 * 
 * add_action( 'my_action_hook_to_call', array( $this, 'the_action_hook_callback', 10, 1 ) );
 * or
 * add_filter( 'my_filter_hook_to_call', array( $this, 'the_filter_hook_callback', 10, 1 ) );
 * or
 * add_shortcode( 'my_shortcode_tag', array( $this, 'the_shortcode_callback', 10 ) );
 * 
 * Once added, you can create the callback function, within this class, as followed: 
 * 
 * public function the_action_hook_callback( $some_variable ){}
 * or
 * public function the_filter_hook_callback( $some_variable ){}
 * or
 * public function the_shortcode_callback( $attributes = array(), $content = '' ){}
 * 
 * 
 * HELPER COMMENT END
 */

/**
 * Class Wp_Video_Preventer_Run
 *
 * Thats where we bring the plugin to life
 *
 * @package		WPVIDEOPRE
 * @subpackage	Classes/Wp_Video_Preventer_Run
 * @author		Traini.tn
 * @since		1.0.0
 */
class Wp_Video_Preventer_Run
{

	/**
	 * Our Wp_Video_Preventer_Run constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct()
	{
		$this->add_hooks();
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOKS
	 * ###
	 * ######################
	 */

	/**
	 * Registers all WordPress and plugin related hooks
	 *
	 * @access	private
	 * @since	1.0.0
	 * @return	void
	 */
	private function add_hooks()
	{

		add_action('plugin_action_links_' . WPVIDEOPRE_PLUGIN_BASE, array($this, 'add_plugin_action_link'), 20);
		add_action('admin_enqueue_scripts', array($this, 'enqueue_backend_scripts_and_styles'), 20);
		// Add this code to your theme's functions.php file or a custom plugin

		add_action('admin_menu', array($this, 'wpvideopre__menu'));

		add_action('wp_ajax_wpvideopre_get_videos', [$this, 'wpvideopre_get_videos']);
		add_action('wp_ajax_nopriv_wpvideopre_get_videos', [$this, 'wpvideopre_get_videos']);

		add_action('wp_ajax_wpvideopre_add_videos', [$this, 'wpvideopre_add_videos']);
		add_action('wp_ajax_nopriv_wpvideopre_add_videos', [$this, 'wpvideopre_add_videos']);
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOK CALLBACKS
	 * ###
	 * ######################
	 */

	/**
	 * Adds action links to the plugin list table
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @param	array	$links An array of plugin action links.
	 *
	 * @return	array	An array of plugin action links.
	 */
	public function add_plugin_action_link($links)
	{

		$links['our_shop'] = sprintf('<a href="%s" title="Custom Link" style="font-weight:700;">%s</a>', 'https://test.test', __('Custom Link', 'wp-video-preventer'));

		return $links;
	}

	/**
	 * Enqueue the backend related scripts and styles for this plugin.
	 * All of the added scripts andstyles will be available on every page within the backend.
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	public function enqueue_backend_scripts_and_styles()
	{
		wp_enqueue_style('wpvideopre-backend-styles', WPVIDEOPRE_PLUGIN_URL . 'core/includes/assets/css/backend-styles.css', array(), WPVIDEOPRE_VERSION, 'all');
		wp_enqueue_script('wpvideopre-backend-scripts', WPVIDEOPRE_PLUGIN_URL . 'core/includes/assets/js/backend-scripts.js', array(), WPVIDEOPRE_VERSION, false);
		wp_localize_script('wpvideopre-backend-scripts', 'wpvideopre', array(
			'plugin_name'   	=> __(WPVIDEOPRE_NAME, 'wp-video-preventer'),
		));
	}


	public  function wpvideopre__menu()
	{
		// Add a top-level menu page
		add_menu_page(
			'Video Preventer',        // Page title
			'Video Preventer',           // Menu title
			'manage_options',        // Capability
			'wpvideopre',        // Menu slug
			array($this, 'wpvideopre_page'),   // Callback function to display the page content
			'dashicons-admin-generic', // Icon URL (dashicons or custom URL)
			6                       // Position (optional)
		);
	}

	public function wpvideopre_page()
	{
		include WPVIDEOPRE_PLUGIN_DIR . 'core/includes/view/main.php';
	}


	public function wpvideopre_get_videos()
	{

		global $wpdb;

		// Define your table name with the $wpdb->prefix to ensure it respects the WordPress prefix
		$table_name = $wpdb->prefix . 'wpvideopre_videos';
		
		// Perform the query to get all rows from the table
		$results = $wpdb->get_results("SELECT * FROM $table_name");
		
		 
		
		wp_send_json([
			'data' => $results,
			'request' => $_REQUEST
		]);
	}

	public function wpvideopre_add_videos()
	{

		//save url video with id of the video

		global $wpdb;

		// Define your table name with the $wpdb->prefix to ensure it respects the WordPress prefix
		$table_name = $wpdb->prefix . 'wpvideopre_videos';

		$video = $_REQUEST['video'];
		// Data to insert
		$data = array(
		//	'name' => $video['name'],
			'url' => $video['url'],
		);

	 

		// Insert data into the table
		$wpdb->insert(
			$table_name, // Table name
			$data,       // Data to insert
		);

		// Check for errors (optional)
		if ($wpdb->last_error) {
			$resp = 'Database error: ' . $wpdb->last_error;
		} else {
			$resp = 'Data inserted successfully!';
		}
		wp_send_json([
			'resp' => $resp,
			'request' => $_REQUEST
		]);
	}
}
