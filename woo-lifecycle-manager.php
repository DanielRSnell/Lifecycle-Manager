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
    exit; // Exit if accessed directly
}

// Define plugin constants
define('WLM_VERSION', '1.0.0');
define('WLM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WLM_PLUGIN_URL', plugin_dir_url(__FILE__));

// Enable error logging
ini_set('log_errors', 1);
ini_set('error_log', WLM_PLUGIN_DIR . 'debug.log');
error_reporting(E_ALL);

// Autoload Composer dependencies
if (file_exists(WLM_PLUGIN_DIR . 'vendor/autoload.php')) {
    require_once WLM_PLUGIN_DIR . 'vendor/autoload.php';
}

// Initialize Timber
if (! class_exists('Timber\Timber')) {
    add_action('admin_notices', function () {
        echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url(admin_url('plugins.php#timber')) . '">' . esc_url(admin_url('plugins.php')) . '</a></p></div>';
    });
    return;
}

Timber\Timber::init();

// Add custom Timber locations
add_filter('timber/locations', function ($paths) {
    $paths['wlm'] = [
        WLM_PLUGIN_DIR . 'partials',
    ];
    return $paths;
});

// Include the main plugin class
require_once WLM_PLUGIN_DIR . 'includes/class-woo-lifecycle-manager.php';

// Include test custom order statuses (for testing purposes only)
if (file_exists(WLM_PLUGIN_DIR . 'test/register-custom-statuses.php')) {
    require_once WLM_PLUGIN_DIR . 'test/register-custom-statuses.php';
}

// Initialize the plugin
function run_woo_lifecycle_manager()
{
    $plugin = new Woo_Lifecycle_Manager();
    $plugin->run();
}

// Activation hook
register_activation_hook(__FILE__, 'wlm_activate');

function wlm_activate()
{
    // Activation tasks (if any)
    // For example, create custom database tables, set default options, etc.
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'wlm_deactivate');

function wlm_deactivate()
{
    // Deactivation tasks (if any)
    // For example, clean up database entries, remove options, etc.
}

// Uninstall hook (must be a static method or function)
register_uninstall_hook(__FILE__, 'wlm_uninstall');

function wlm_uninstall()
{
    // Uninstall tasks
    // For example, remove all plugin data from the database
}

// Load plugin textdomain for translations
add_action('plugins_loaded', 'wlm_load_textdomain');

function wlm_load_textdomain()
{
    load_plugin_textdomain('woo-lifecycle-manager', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

// Run the plugin
run_woo_lifecycle_manager();

// Add settings link on plugin page
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wlm_settings_link');

function wlm_settings_link($links)
{
    $settings_link = '<a href="admin.php?page=wc-settings&tab=woo-lifecycle-manager">' . __('Settings', 'woo-lifecycle-manager') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}

// Add custom action for other plugins/themes to hook into
do_action('woo_lifecycle_manager_loaded');
