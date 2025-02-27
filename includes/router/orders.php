<?php
/**
 * Handles routing for lifecycle pages and asset management
 */

/**
 * Register the admin menu page for Lifecycle
 */
function wlm_register_admin_page()
{
    add_menu_page(
        'Order Lifecycle',
        'Lifecycle',
        'manage_options',
        'wlm-lifecycle',
        'wlm_render_lifecycle_page',
        'dashicons-update',
        56
    );
}
add_action('admin_menu', 'wlm_register_admin_page');

/**
 * Render the lifecycle admin page
 */
function wlm_render_lifecycle_page()
{
    // Include the order page template
    include plugin_dir_path(__FILE__) . '/partials/order_page.php';
}

/**
 * Handle the legacy frontend lifecycle template routing (can be removed later)
 */
function handle_lifecycle_template()
{
    // Check if we're on the lifecycle endpoint
    if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/lifecycle') !== false) {
        // Redirect to admin page with the same parameters
        $redirect_url = add_query_arg(
            $_GET,
            admin_url('admin.php?page=wlm-lifecycle')
        );
        wp_redirect($redirect_url);
        exit;
    }
}
add_action('template_redirect', 'handle_lifecycle_template', 999);

/**
 * Register and enqueue plugin styles
 */
function wlm_enqueue_styles()
{
    wp_register_style(
        'woo-lifecycle-manager',
        WLM_PLUGIN_URL . 'admin/css/woo-lifecycle-manager-admin.css',
        [],
        WLM_VERSION,
        'all'
    );

    do_action('wlm_enqueue_styles');
    wp_enqueue_style('woo-lifecycle-manager');
}

/**
 * Register and enqueue plugin scripts
 */
function wlm_enqueue_scripts()
{
    wp_register_script(
        'woo-lifecycle-manager',
        WLM_PLUGIN_URL . 'admin/js/woo-lifecycle-manager-admin.js',
        ['jquery'],
        WLM_VERSION,
        true
    );

    wp_localize_script(
        'woo-lifecycle-manager',
        'wlmData',
        [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('wlm_lifecycle_nonce'),
        ]
    );

    do_action('wlm_enqueue_scripts');
    wp_enqueue_script('woo-lifecycle-manager');
}
