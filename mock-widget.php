<?php
/**
 * ACF Form Widget for WooCommerce Order Admin Page
 * 
 * This file registers a widget section to the WooCommerce order admin page
 * and renders an ACF form directly within that section.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register and initialize the ACF form widget
 */
function wlm_init_acf_order_widget() {
    // Make sure ACF is active
    if (!function_exists('acf_form')) {
        return;
    }
    
    // Add the meta box to the WooCommerce order page
    add_meta_box(
        'wlm_acf_order_widget',
        'Lifecycle Fields',
        'wlm_render_acf_order_widget',
        'shop_order',
        'normal',
        'high'
    );
    
    // Register the ACF form head in admin
    add_action('admin_head', 'wlm_acf_widget_form_head');
}
add_action('add_meta_boxes', 'wlm_init_acf_order_widget');

/**
 * Add ACF form head to admin
 */
function wlm_acf_widget_form_head() {
    // Only load on order edit page
    $screen = get_current_screen();
    if ($screen && $screen->id === 'shop_order') {
        acf_form_head();
    }
}

/**
 * Render the ACF form widget content
 * 
 * @param WP_Post $post The post object
 */
function wlm_render_acf_order_widget($post) {
    // Get the order ID
    $order_id = $post->ID;
    
    // Get the order
    $order = wc_get_order($order_id);
    if (!$order) {
        echo '<p>Order not found.</p>';
        return;
    }
    
    // Get the order status
    $order_status = $order->get_status();
    
    // Output some CSS to style the form
    ?>
    <style>
        .acf-form-submit {
            padding: 15px 0;
        }
        
        .acf-form .acf-field {
            border-top: 1px solid #eee;
        }
        
        .acf-form .acf-fields > .acf-field:first-child {
            border-top: none;
        }
        
        .acf-form .acf-field-group .acf-fields {
            border: none;
        }
        
        /* Fix potential conflicts with WooCommerce styles */
        .acf-form input[type="text"], 
        .acf-form input[type="number"],
        .acf-form textarea,
        .acf-form select {
            width: 100%;
            max-width: 100%;
        }
        
        /* Status indicator */
        .wlm-status-indicator {
            display: inline-block;
            margin-bottom: 15px;
            padding: 5px 10px;
            background-color: #f0f8ff;
            border-left: 4px solid #2271b1;
            font-weight: 500;
        }
    </style>
    
    <div class="wlm-status-indicator">
        Order Status: <?php echo ucfirst($order_status); ?>
    </div>
    
    <?php
    // Render the ACF form
    acf_form([
        'id' => 'lifecycle-global-fields',
        'post_id' => $order_id,
        'field_groups' => ['group_67be0734bf22e'], // Specific field group ID
        'updated_message' => 'Lifecycle fields updated successfully.',
        'html_submit_button' => '<input type="submit" class="button button-primary button-large" value="%s" />',
        'submit_value' => 'Update Lifecycle Fields',
        'html_after_fields' => '<input type="hidden" name="lifecycle_form_submitted" value="1" />',
    ]);
    
    // Add a script to handle form submission without page reload (optional)
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Optional: Add AJAX form submission if needed
        // This is just a placeholder for potential AJAX implementation
        
        // Update status indicator when fields change
        $('.acf-field input, .acf-field textarea, .acf-field select').on('change', function() {
            $('.wlm-status-indicator').css('border-left-color', '#ffba00');
        });
    });
    </script>
    <?php
}

/**
 * Handle the form submission and save the data
 * This is optional as ACF handles saving by default
 */
function wlm_handle_acf_order_widget_submission($post_id) {
    // Check if our form was submitted
    if (!isset($_POST['lifecycle_form_submitted']) || $_POST['lifecycle_form_submitted'] !== '1') {
        return;
    }
    
    // ACF will handle the saving of fields automatically
    // This function is a hook for any additional processing
    
    // Example: Add a note to the order
    $order = wc_get_order($post_id);
    if ($order) {
        $order->add_order_note('Lifecycle fields were updated.', false, false);
    }
}
add_action('acf/save_post', 'wlm_handle_acf_order_widget_submission');
