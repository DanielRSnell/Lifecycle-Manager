<?php

if (!defined('ABSPATH')) {
    exit;
}

class WLM_ACF_Field_Groups_Controller extends WLM_Base_Controller {
    public function register_routes() {
        register_rest_route('lifecycle/v1', '/acf-field-groups', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_acf_field_groups'),
            'permission_callback' => function() {
                return current_user_can('view_woocommerce_reports');
            },
        ));
    }

    public function get_acf_field_groups() {
        if (!function_exists('acf_get_field_groups')) {
            return new WP_REST_Response(array('error' => 'ACF is not active'), 400);
        }

        $field_groups = acf_get_field_groups();

        $formatted_groups = array_map(function($group) {
            return array(
                'name' => $group['title'],
                'key' => $group['key']
            );
        }, $field_groups);

        return new WP_REST_Response($formatted_groups, 200);
    }
}
