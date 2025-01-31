<?php
/**
 * Plugin Name: Woo Lifecycle Manager
 * Description: Adds a lifecycle container to the WooCommerce order page using Timber and Twig.
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

// Autoload Composer dependencies
if (file_exists(WLM_PLUGIN_DIR . 'vendor/autoload.php')) {
    require_once WLM_PLUGIN_DIR . 'vendor/autoload.php';
}

// Initialize Timber
if (class_exists('Timber\Timber')) {
    Timber\Timber::init();
} else {
    add_action('admin_notices', function() {
        echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url(admin_url('plugins.php#timber')) . '">' . esc_url(admin_url('plugins.php')) . '</a></p></div>';
    });
    return;
}

// Include the main plugin class
require_once WLM_PLUGIN_DIR . 'includes/class-woo-lifecycle-manager.php';

// Initialize the plugin
function run_woo_lifecycle_manager() {
    $plugin = new Woo_Lifecycle_Manager();
    $plugin->run();
}
run_woo_lifecycle_manager();
