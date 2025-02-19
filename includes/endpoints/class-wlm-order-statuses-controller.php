<?php

if (!defined('ABSPATH')) {
    exit;
}

class WLM_Order_Statuses_Controller extends WLM_Base_Controller {
    public function register_routes() {
        register_rest_route('lifecycle/v1', '/order-statuses', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_order_statuses'),
            'permission_callback' => '__return_true',
        ));
    }

    public function get_order_statuses() {
        $wc_order_statuses = wc_get_order_statuses();
        
        $formatted_statuses = array();
        foreach ($wc_order_statuses as $key => $value) {
            $status = array(
                'id' => $key,
                'name' => $value,
                'fields' => array(
                    'status' => false,
                    'key' => null,
                    'name' => null
                )
            );

            if (function_exists('acf_get_field_groups')) {
                $wc_check = $this->wc_check($key);
                $matching_group = $this->find_matching_acf_group($wc_check);
                if ($matching_group) {
                    $status['fields'] = array(
                        'status' => true,
                        'key' => $matching_group['key'],
                        'name' => $matching_group['title']
                    );
                }
            }

            $formatted_statuses[] = $status;
        }

        usort($formatted_statuses, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return new WP_REST_Response($formatted_statuses, 200);
    }
}
