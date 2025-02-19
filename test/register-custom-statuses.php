<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function wlm_register_custom_order_statuses() {
    $json_file = WLM_PLUGIN_DIR . 'test/custom-order-status.json';
    
    if (file_exists($json_file)) {
        $json_content = file_get_contents($json_file);
        $custom_statuses = json_decode($json_content, true);

        if (is_array($custom_statuses) && isset($custom_statuses['custom_statuses'])) {
            foreach ($custom_statuses['custom_statuses'] as $status) {
                register_post_status($status['id'], array(
                    'label' => $status['name'],
                    'public' => true,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => true,
                    'show_in_admin_status_list' => true,
                    'label_count' => _n_noop($status['name'] . ' <span class="count">(%s)</span>', $status['name'] . ' <span class="count">(%s)</span>')
                ));
            }
        }
    }
}

function wlm_add_custom_statuses_to_order_statuses($order_statuses) {
    $json_file = WLM_PLUGIN_DIR . 'test/custom-order-status.json';
    
    if (file_exists($json_file)) {
        $json_content = file_get_contents($json_file);
        $custom_statuses = json_decode($json_content, true);

        if (is_array($custom_statuses) && isset($custom_statuses['custom_statuses'])) {
            foreach ($custom_statuses['custom_statuses'] as $status) {
                $order_statuses[$status['id']] = $status['name'];
            }
        }
    }

    return $order_statuses;
}

add_action('init', 'wlm_register_custom_order_statuses');
add_filter('wc_order_statuses', 'wlm_add_custom_statuses_to_order_statuses');
