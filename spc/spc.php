<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/zapotocnylubos
 * @since             1.0.0
 * @package           Spc
 *
 * @wordpress-plugin
 * Plugin Name:       Submission post creator
 * Plugin URI:        https://github.com/zapotocnylubos/submission-post-creator
 * Description:       Plugin responsible for managing competition with HTML Forms plugin
 * Version:           1.0.0
 * Author:            Lubos Zapotocny
 * Author URI:        https://github.com/zapotocnylubos
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       spc
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
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-spc-activator.php
 */
function activate_spc() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-spc-activator.php';
	Spc_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-spc-deactivator.php
 */
function deactivate_spc() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-spc-deactivator.php';
	Spc_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_spc' );
register_deactivation_hook( __FILE__, 'deactivate_spc' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-spc.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_spc() {

	$plugin = new Spc();
	$plugin->run();

}
run_spc();

