<?php

if (!defined('ABSPATH')) {
    exit;
}

class WLM_Order_Status_Form_Controller extends WLM_Base_Controller {
    public function register_routes() {
        register_rest_route('lifecycle/v1', '/order-status/form', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_order_status_form'),
            'permission_callback' => function() {
                return current_user_can('view_woocommerce_reports');
            },
            'args' => array(
                'order_id' => array(
                    'required' => true,
                    'validate_callback' => function($param, $request, $key) {
                        return is_numeric($param);
                    }
                ),
                'status_id' => array(
                    'required' => true,
                    'validate_callback' => function($param, $request, $key) {
                        return is_string($param);
                    }
                ),
                'field_group_id' => array(
                    'required' => true,
                    'validate_callback' => function($param, $request, $key) {
                        return is_string($param);
                    }
                ),
            ),
        ));
    }

    public function get_order_status_form($request) {
        $order_id = $request->get_param('order_id');
        $status_id = $request->get_param('status_id');
        $field_group_id = $request->get_param('field_group_id');

        // Check if ACF is active
        if (!function_exists('acf_form')) {
            return new WP_REST_Response(array('error' => 'ACF is not active'), 400);
        }

        // Check if the order exists
        $order = wc_get_order($order_id);
        if (!$order) {
            return new WP_REST_Response(array('error' => 'Order not found'), 404);
        }

        // Start output buffering
        ob_start();

        // Generate ACF form
        acf_form(array(
            'post_id' => $order_id,
            'field_groups' => array($field_group_id),
            'form' => false,
            'return' => add_query_arg('updated', 'true', get_permalink()),
            'submit_value' => __('Update', 'woo-lifecycle-manager'),
        ));

        // Get the form HTML
        $form_html = ob_get_clean();

        // Return the form HTML
        return new WP_REST_Response(array('form' => $form_html), 200);
    }
}
