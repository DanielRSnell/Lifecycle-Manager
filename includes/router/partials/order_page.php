<?php
    /**
     * Template for displaying ACF form in lifecycle page
     *
     * Handles form display and validation for WooCommerce orders
     */

    // Security check
    if (! defined('ABSPATH')) {
        exit;
    }

    // Initialize WordPress headers and required functions
    acf_form_head();
    wp_head();
    wlm_enqueue_styles();
    wlm_enqueue_scripts();

    /**
     * Get and validate request parameters
     */
    $order_id          = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
    $group_id          = isset($_GET['group_id']) ? sanitize_text_field($_GET['group_id']) : '';
    $webhook_triggered = isset($_GET['webhook']) ? filter_var($_GET['webhook'], FILTER_VALIDATE_BOOLEAN) : false;

    // Validate required parameters
    if (! $order_id || ! $group_id) {
        wp_die('Invalid request');
    }

    // Get WooCommerce order
    $order      = wc_get_order($order_id);
    $order_link = admin_url("admin.php?page=wc-orders&action=edit&id={$order_id}");

    /**
     * Placeholder function for sending webhook
     */
    function send_webhook_notification($order_id, $group_id, $order_link)
    {
        // Get fields only if webhook is triggered
        $fields  = acf_get_fields($group_id);
        $payload = [
            'url' => $order_link,
            ...$fields, // Pass fields using keys
        ];

        // Placeholder for sending webhook
        // Example: send_request_to_webhook($payload);
    }

    // If webhook is triggered, send notification
    if ($webhook_triggered) {
        send_webhook_notification($order_id, $group_id, $order_link);
    }
?>

<div class="lifecycle-container bg-gray-100">
    <?php if ($webhook_triggered): ?>
        <div class="notification bg-green-500 text-white p-4 mb-4">
            Notification has been sent.                                        <?php $order_link?>
        </div>
    <?php endif; ?>

<?php
    if (function_exists('acf_form')) {
        acf_form([
            'post_id'             => $order_id,
            'field_groups'        => [$group_id],
            'html_submit_spinner' => '<span class="acf-spinner"></span>',
            'return'              => add_query_arg([
                'order_id' => $order_id,
                'group_id' => $group_id,
                'webhook'  => 'true',
            ], home_url('lifecycle')),
        ]);
    }
?>
</div>

<?php
    /**
     * Styles
     */
?>
<style>
body {
    overflow-y: scroll !important;
    background: oklch(0.967 0.003 264.542) !important;
}

.lifecycle-container {
    padding-bottom: 12rem;
}

.acf-field:last-child {
    padding-bottom: 4rem;
}

/* Form Styles */
#acf-form {
    height: 100%;
    width: 100%;
}

.acf-field {
    padding-bottom: 1rem;
}

.acf-form-fields {
    padding: 0 2rem;
    gap: 1rem;
    overflow-y: scroll;
}

.lifecycle-container {
    width: 100%;
    background: oklch(0.967 0.003 264.542) !important;
}

/* WordPress Admin Bar */
#wpadminbar {
    display: none;
}

html {
    margin-top: 0 !important;
}

/* Spinner Animation */
.acf-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PHBhdGggZD0iTTIwIDEwLjVjLjI1LjI1') center center no-repeat;
    background-size: 16px;
    animation: acf-spinner 1s linear infinite;
}

@keyframes acf-spinner {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>

<?php
    // Footer
wp_footer();
?>