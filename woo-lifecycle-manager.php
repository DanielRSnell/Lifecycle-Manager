<?php
/**
 * Plugin Name: Woo Lifecycle Manager
 * Description: Adds a lifecycle container to the WooCommerce order page.
 * Version: 1.0
 * Author: Your Name
 * Text Domain: woo-lifecycle-manager
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('WLM_VERSION', '1.0.0');
define('WLM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WLM_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include the main plugin class
require_once WLM_PLUGIN_DIR . 'includes/class-woo-lifecycle-manager.php';

// Initialize the plugin
function run_woo_lifecycle_manager() {
    $plugin = new Woo_Lifecycle_Manager();
    $plugin->run();
}
run_woo_lifecycle_manager();
