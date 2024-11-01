<?php
/**
 *
 * @link              https://www.vsourz.com/
 * @since             1.0.0
 * @package           wp-responsive-media-gallery
 *
 * @wordpress-plugin
 * Plugin Name:       WP Responsive Media Gallery
 * Plugin URI:        www.vsourz.com
 * Description:       Create a wonderful image gallery in couple of clicks with responsive media gallery plugin. A finest way to Promote your image and video online with different groups. 
 * Version:           1.1.1
 * Author:            Vsourz Digital
 * Author URI:        https://www.vsourz.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-responsive-media-gallery
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-responsive-media-gallery-activator.php
 */
function activate_responsive_media_gallery() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-responsive-media-gallery-activator.php';
	Responsive_Media_Gallery_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-responsive-media-gallery-deactivator.php
 */
function deactivate_responsive_media_gallery() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-responsive-media-gallery-deactivator.php';
	Responsive_Media_Gallery_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_responsive_media_gallery' );
register_deactivation_hook( __FILE__, 'deactivate_responsive_media_gallery' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-responsive-media-gallery.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_responsive_media_gallery() {

	$plugin = new Responsive_Media_Gallery();
	$plugin->run();

}
run_responsive_media_gallery();
