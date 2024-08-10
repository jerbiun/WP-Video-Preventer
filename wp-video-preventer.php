<?php
/**
 * WP Video Preventer
 *
 * @package       WPVIDEOPRE
 * @author        Traini.tn
 * @license       gplv2
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   WP Video Preventer
 * Plugin URI:    Traini.tn
 * Description:   Embed any video to your website and prevent user to do any action only he can watch and only on your website
 * Version:       1.0.0
 * Author:        Traini.tn
 * Author URI:    Traini.tn
 * Text Domain:   wp-video-preventer
 * Domain Path:   /languages
 * License:       GPLv2
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with WP Video Preventer. If not, see <https://www.gnu.org/licenses/gpl-2.0.html/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This file contains the main information about the plugin.
 * It is used to register all components necessary to run the plugin.
 * 
 * The comment above contains all information about the plugin 
 * that are used by WordPress to differenciate the plugin and register it properly.
 * It also contains further PHPDocs parameter for a better documentation
 * 
 * The function WPVIDEOPRE() is the main function that you will be able to 
 * use throughout your plugin to extend the logic. Further information
 * about that is available within the sub classes.
 * 
 * HELPER COMMENT END
 */

// Plugin name
define( 'WPVIDEOPRE_NAME',			'WP Video Preventer' );

// Plugin version
define( 'WPVIDEOPRE_VERSION',		'1.0.0' );

// Plugin Root File
define( 'WPVIDEOPRE_PLUGIN_FILE',	__FILE__ );

// Plugin base
define( 'WPVIDEOPRE_PLUGIN_BASE',	plugin_basename( WPVIDEOPRE_PLUGIN_FILE ) );

// Plugin Folder Path
define( 'WPVIDEOPRE_PLUGIN_DIR',	plugin_dir_path( WPVIDEOPRE_PLUGIN_FILE ) );

// Plugin Folder URL
define( 'WPVIDEOPRE_PLUGIN_URL',	plugin_dir_url( WPVIDEOPRE_PLUGIN_FILE ) );

/**
 * Load the main class for the core functionality
 */
require_once WPVIDEOPRE_PLUGIN_DIR . 'core/class-wp-video-preventer.php';

/**
 * The main function to load the only instance
 * of our master class.
 *
 * @author  Traini.tn
 * @since   1.0.0
 * @return  object|Wp_Video_Preventer
 */
function WPVIDEOPRE() {
	return Wp_Video_Preventer::instance();
}

register_activation_hook(__FILE__, 'wpvideopre_activate');

function wpvideopre_activate() {
    global $wpdb;

    // Define table name with WordPress prefix
    $table_name = $wpdb->prefix . 'wpvideopre_videos';

    // SQL to create table
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255)   NULL,
        url varchar(255)  NOT NULL,
		url_id varchar(255)   NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    // Include the WordPress upgrade file to use dbDelta function
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    // Create the table
    dbDelta($sql);

 
}

WPVIDEOPRE();
