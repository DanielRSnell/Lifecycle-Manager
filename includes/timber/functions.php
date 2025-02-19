<?php

if (!defined('ABSPATH')) {
    exit;
}

add_filter('timber/twig/functions', function ($functions) {
    $functions['wlm_acf_form'] = [
        'callable' => 'wlm_render_acf_form',
    ];
    $functions['acf_form_head'] = [
        'callable' => 'wlm_acf_form_head',
    ];
    $functions['acf_form_footer'] = [
        'callable' => 'wlm_acf_form_footer',
    ];
    return $functions;
});

function wlm_render_acf_form($order_id, $status_id, $field_group_id) {
    if (!function_exists('acf_form')) {
        return 'Advanced Custom Fields is not active.';
    }

    // Ensure the order exists
    $order = wc_get_order($order_id);
    if (!$order) {
        return 'Order not found.';
    }

    // Start output buffering
    ob_start();

    // Render ACF form
    acf_form(array(
        'post_id' => $order_id,
        'field_groups' => array($field_group_id),
        'form' => true,
        'return' => add_query_arg('updated', 'true', get_permalink()),
        'submit_value' => __('Update', 'woo-lifecycle-manager'),
    ));

    // Get the form HTML
    $form_html = ob_get_clean();

    return $form_html;
}

function wlm_acf_form_head() {
    if (function_exists('acf_form_head')) {
        ob_start();
        acf_form_head();
        return ob_get_clean();
    }
    return '';
}

function wlm_acf_form_footer() {
    if (function_exists('acf_form_footer')) {
        ob_start();
        acf_form_footer();
        return ob_get_clean();
    }
    return '';
}
