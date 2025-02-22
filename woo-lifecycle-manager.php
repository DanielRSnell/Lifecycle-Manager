<?php
/**
 * Plugin Name: Woo Lifecycle Manager
 * Description: Adds a lifecycle container to the WooCommerce order page using Timber and Twig.
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: woo-lifecycle-manager
 * Domain Path: /languages
 */

if (! defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('WLM_VERSION', '1.0.0');
define('WLM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WLM_PLUGIN_URL', plugin_dir_url(__FILE__));

// Setup error logging
ini_set('log_errors', 1);
ini_set('error_log', WLM_PLUGIN_DIR . 'debug.log');
error_reporting(E_ALL);

// Load Composer autoloader
if (file_exists(WLM_PLUGIN_DIR . 'vendor/autoload.php')) {
    require_once WLM_PLUGIN_DIR . 'vendor/autoload.php';
}

// Load Timber setup
require_once WLM_PLUGIN_DIR . 'includes/timber/setup.php';
require_once WLM_PLUGIN_DIR . 'includes/timber/locations.php';
require_once WLM_PLUGIN_DIR . 'includes/timber/functions.php';

// Load main plugin class
require_once WLM_PLUGIN_DIR . 'includes/class-woo-lifecycle-manager.php';

// Load custom order statuses (for testing)
if (file_exists(WLM_PLUGIN_DIR . 'test/register-custom-statuses.php')) {
    require_once WLM_PLUGIN_DIR . 'test/register-custom-statuses.php';
}

// Initialize the plugin
function run_woo_lifecycle_manager()
{
    $plugin = new Woo_Lifecycle_Manager();
    $plugin->run();
}

// Plugin lifecycle hooks
register_activation_hook(__FILE__, 'wlm_activate');
register_deactivation_hook(__FILE__, 'wlm_deactivate');
register_uninstall_hook(__FILE__, 'wlm_uninstall');

function wlm_activate()
{
    // Activation tasks (if any)
}

function wlm_deactivate()
{
    // Deactivation tasks (if any)
}

function wlm_uninstall()
{
    // Uninstall tasks
}

// Load plugin textdomain
add_action('plugins_loaded', 'wlm_load_textdomain');
function wlm_load_textdomain()
{
    load_plugin_textdomain('woo-lifecycle-manager', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

// Add settings link on plugin page
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wlm_settings_link');
function wlm_settings_link($links)
{
    $settings_link = '<a href="admin.php?page=wc-settings&tab=woo-lifecycle-manager">' . __('Settings', 'woo-lifecycle-manager') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}

// Run the plugin
run_woo_lifecycle_manager();

// Plugin loaded action
do_action('woo_lifecycle_manager_loaded');

require WLM_PLUGIN_DIR . 'includes/router/orders.php';
