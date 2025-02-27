<?php
/**
 * Template for displaying ACF form in lifecycle admin page
 *
 * Handles form display and validation for WooCommerce orders
 */

// Security check
if (!defined('ABSPATH')) {
    exit;
}

// Initialize required functions
acf_form_head();
wlm_enqueue_styles();
wlm_enqueue_scripts();

/**
 * Get and validate request parameters
 */
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$group_id = isset($_GET['group_id']) ? sanitize_text_field($_GET['group_id']) : '';
$webhook_triggered = isset($_GET['webhook']) ? filter_var($_GET['webhook'], FILTER_VALIDATE_BOOLEAN) : false;

// Validate order ID is provided
if (!$order_id) {
    echo '<div class="wrap"><h1>Order Lifecycle</h1>';
    echo '<div class="notice notice-error"><p>Invalid request: Order ID is required</p></div>';
    echo '</div>';
    return;
}

// Get WooCommerce order
$order = wc_get_order($order_id);

// Validate order exists
if (!$order) {
    echo '<div class="wrap"><h1>Order Lifecycle</h1>';
    echo '<div class="notice notice-error"><p>Order not found</p></div>';
    echo '</div>';
    return;
}

$order_link = admin_url("admin.php?page=wc-orders&action=edit&id={$order_id}");

/**
 * Send webhook notification with order data
 */
function send_webhook_notification($order_id, $group_id, $order_link)
{
    // Get fields only if webhook is triggered
    $fields = acf_get_fields($group_id);
    $payload = [
        'url' => $order_link,
        ...$fields, // Pass fields using keys
    ];

    // Placeholder for sending webhook
    // Example: send_request_to_webhook($payload);
}

// If webhook is triggered, send notification
if ($webhook_triggered && $group_id) {
    send_webhook_notification($order_id, $group_id, $order_link);
}
?>

<div class="wrap">
<?php if ($webhook_triggered): ?>
            <div class="notice notice-success">
                <p>Notification has been sent.</p>
            </div>
        <?php endif;?>
        <div class="lifecycle-container">
        <div class="lifecycle-header">
            <h class="lc-header"1>Order Lifecycle</h>
            <p>Managing order #<?php echo $order_id; ?> - <a href="<?php echo $order_link; ?>">View Order</a></p>
        </div>

        <div class="form-wrapper">
            <?php if ($group_id): ?>
                <!-- Tab Navigation when both forms are available -->
                <div class="tabs-navigation">
                    <button class="tab-button active" data-tab="status">Order Status Fields</button>
                    <button class="tab-button" data-tab="global">Global Fields</button>
                </div>

                <?php else: ?>
                <!-- Only show global fields if no group_id -->
                <div id="lifecycle-global-fields" data-order-id="<?php echo $order_id; ?>" data-group-id="<?php echo $group_id; ?>">
                    <?php

acf_form([
    'id' => 'lifecycle-global-fields',
    'post_id' => $order_id,
    'field_groups' => ['group_67be0734bf22e'],
]);

?>
                </div>
            <?php endif;?>
        </div>
    </div>
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
    padding: 2rem;
    width: 100%;
}



/* Form Wrapper */
.form-wrapper {
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    margin-bottom: 12rem;
    max-width: 88rem;
    width: 100%;
    margin: 0 auto;
}

.acf-field:last-child {
    padding-bottom: 4rem;
}

  html {
                margin-top: 0 !important;
                padding-top: 0!important;
            }
            .notice {
                display: none !important;
            }
            body.full-screen #wpadminbar {
                display: none !important;
            }
            body.full-screen #adminmenuback,
            body.full-screen #adminmenuwrap {
                display: none !important;
            }
            body.full-screen #wpcontent,
            body.full-screen #wpfooter {
                margin-left: 0 !important;
            }
            #wpfooter {
                display: none!important;
                }
            #wpcontent,
            #wpbody,
            #wpbody-content {
                height: 100% !important;
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

/* Tab Styles */
.tabs-navigation {
    display: flex;
    gap: 1rem;
    padding: 1rem 2rem;
    background: white;
    border-bottom: 1px solid #e5e7eb;
}

.tab-button {
    padding: 0.5rem 1rem;
    border: none;
    background: none;
    cursor: pointer;
    font-weight: 500;
    color: #6b7280;
    border-bottom: 2px solid transparent;
}

.tab-button.active {
    color: #000;
    border-bottom: 2px solid #000;
}

.tab-pane {
    display: none;
    padding: 1rem;
}

.tab-pane.active {
    display: block;
}

/* Notification */
.notification {
    border-radius: 6px;
    margin-bottom: 1rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab-button');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));

            // Add active class to clicked tab
            tab.classList.add('active');
            document.getElementById(`${tab.dataset.tab}-tab`).classList.add('active');
        });
    });
});
</script>

<?php
// Footer
wp_footer();
?>