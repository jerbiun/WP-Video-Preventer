<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This is the main class that is responsible for registering
 * the core functions, including the files and setting up all features. 
 * 
 * To add a new class, here's what you need to do: 
 * 1. Add your new class within the following folder: core/includes/classes
 * 2. Create a new variable you want to assign the class to (as e.g. public $helpers)
 * 3. Assign the class within the instance() function ( as e.g. self::$instance->helpers = new Wp_Video_Preventer_Helpers();)
 * 4. Register the class you added to core/includes/classes within the includes() function
 * 
 * HELPER COMMENT END
 */

if ( ! class_exists( 'Wp_Video_Preventer' ) ) :

	/**
	 * Main Wp_Video_Preventer Class.
	 *
	 * @package		WPVIDEOPRE
	 * @subpackage	Classes/Wp_Video_Preventer
	 * @since		1.0.0
	 * @author		Traini.tn
	 */
	final class Wp_Video_Preventer {

		/**
		 * The real instance
		 *
		 * @access	private
		 * @since	1.0.0
		 * @var		object|Wp_Video_Preventer
		 */
		private static $instance;

		/**
		 * WPVIDEOPRE helpers object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Wp_Video_Preventer_Helpers
		 */
		public $helpers;

		/**
		 * WPVIDEOPRE settings object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Wp_Video_Preventer_Settings
		 */
		public $settings;

		/**
		 * Throw error on object clone.
		 *
		 * Cloning instances of the class is forbidden.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to clone this class.', 'wp-video-preventer' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to unserialize this class.', 'wp-video-preventer' ), '1.0.0' );
		}

		/**
		 * Main Wp_Video_Preventer Instance.
		 *
		 * Insures that only one instance of Wp_Video_Preventer exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @access		public
		 * @since		1.0.0
		 * @static
		 * @return		object|Wp_Video_Preventer	The one true Wp_Video_Preventer
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Wp_Video_Preventer ) ) {
				self::$instance					= new Wp_Video_Preventer;
				self::$instance->base_hooks();
				self::$instance->includes();
				self::$instance->helpers		= new Wp_Video_Preventer_Helpers();
				self::$instance->settings		= new Wp_Video_Preventer_Settings();

				//Fire the plugin logic
				new Wp_Video_Preventer_Run();

				/**
				 * Fire a custom action to allow dependencies
				 * after the successful plugin setup
				 */
				do_action( 'WPVIDEOPRE/plugin_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Include required files.
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function includes() {
			require_once WPVIDEOPRE_PLUGIN_DIR . 'core/includes/classes/class-wp-video-preventer-helpers.php';
			require_once WPVIDEOPRE_PLUGIN_DIR . 'core/includes/classes/class-wp-video-preventer-settings.php';

			require_once WPVIDEOPRE_PLUGIN_DIR . 'core/includes/classes/class-wp-video-preventer-run.php';
		}

		/**
		 * Add base hooks for the core functionality
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function base_hooks() {
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'wp-video-preventer', FALSE, dirname( plugin_basename( WPVIDEOPRE_PLUGIN_FILE ) ) . '/languages/' );
		}

	}

endif; // End if class_exists check.