<?php
function handle_lifecycle_template()
{

    // Check if we're on the lifecycle endpoint
    if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/lifecycle') !== false) {
        // Ensure we have required parameters
        if (! isset($_GET['order_id']) || ! isset($_GET['group_id'])) {
            wp_redirect(home_url());
            exit;
        }

        // Check if user is logged in and not customer or subscriber
        if (! is_user_logged_in() || current_user_can('customer') || current_user_can('subscriber')) {
            // If they are redirect to 404
            wp_redirect('/404');
            exit;
        }

        // Load the template
        include plugin_dir_path(__FILE__) . '/partials/order_page.php';
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
