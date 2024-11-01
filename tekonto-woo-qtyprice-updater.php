<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://tekonto.com
 * @since             1.0.0
 * @package           Tekonto_Woo_Qtyprice_Updater
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce Product Qty and Price Updater
 * Plugin URI:        http://tekonto.com
 * Description:       Woocommerce Qty and Price Updater does one thing and does it well and fast. It updates your woocommerce store's product quantity and price in a breeze.
 * Version:           1.0.0
 * Author:            chasehe
 * Author URI:        http://tekonto.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tekonto-woo-qtyprice-updater
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tekonto-woo-qtyprice-updater-activator.php
 */
function activate_tekonto_woo_qtyprice_updater() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tekonto-woo-qtyprice-updater-activator.php';
	Tekonto_Woo_Qtyprice_Updater_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-tekonto-woo-qtyprice-updater-deactivator.php
 */
function deactivate_tekonto_woo_qtyprice_updater() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tekonto-woo-qtyprice-updater-deactivator.php';
	Tekonto_Woo_Qtyprice_Updater_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_tekonto_woo_qtyprice_updater' );
register_deactivation_hook( __FILE__, 'deactivate_tekonto_woo_qtyprice_updater' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-tekonto-woo-qtyprice-updater.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tekonto_woo_qtyprice_updater() {

	$plugin = new Tekonto_Woo_Qtyprice_Updater();
	$plugin->run();

}
run_tekonto_woo_qtyprice_updater();
