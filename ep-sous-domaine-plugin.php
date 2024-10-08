<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://takamoastudio.com/
 * @since             1.0.0
 * @package           Ep-sous-domaine-plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Etapes Print Sous Domaine
 * Plugin URI:        x
 * Description:       Etapes Print Extension : Sous Domaine
 * Version:           1.0.0
 * Author:            Takamoa Studio
 * Author URI:        https://takamoastudio.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       Ep-sous-domaine-plugin
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ep_sous_domaine', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ep_sous_domaine-activator.php
 */
function activate_ep_sous_domaine() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ep_sous_domaine-activator.php';
	Ep_sous_domaine_Activator::activate();

}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ep_sous_domaine-deactivator.php
 */
// function deactivate_ep_sous_domaine() {

// 	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ep_sous_domaine-deactivator.php';
// 	Ep_sous_domaine_Deactivator::deactivate();

// }

register_activation_hook( __FILE__, 'activate_ep_sous_domaine' );
// register_deactivation_hook( __FILE__, 'deactivate_ep_sous_domaine' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ep_sous_domaine.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
	$plugin = new Ep_sous_domaine();
	$plugin->run();

/**
 * Check if the parent plugin is active, and display an error notice if it's not.
 */
function ep_sous_domaine_check_parent_plugin() {
	if ( ! is_plugin_active( 'etapes-print/etapes-print.php' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_notices', 'ep_sous_domaine_missing_parent_plugin_notice' );
	}
}

/**
 * Display an error notice if the parent plugin is not active.
 */
function ep_sous_domaine_missing_parent_plugin_notice() {
	?>
	<div class="notice notice-error" style="background: #8c7c66;
    color: white;">
		<p><?php _e( 'L\'extension Etapes Print est requise pour activer l\'extension Etapes Print sous domaine.', 'ep-sous-domaine-plugin' ); ?></p>
	</div>
	<?php
}
add_action( 'admin_init', 'ep_sous_domaine_check_parent_plugin' );