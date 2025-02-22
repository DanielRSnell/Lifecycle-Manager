<?php

if (! defined('ABSPATH')) {
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

function wlm_render_acf_form($form)
{

    $order_id       = $form['order_id'];
    $status_id      = $form['status_id'];
    $field_group_id = $form['field_group_id'];

    if (! function_exists('acf_form')) {
        return 'Advanced Custom Fields is not active.';
    }

    // Ensure the order exists
    $order = wc_get_order($order_id);
    if (! $order) {
        return 'Order not found.';
    }

    // Start output buffering
    ob_start();

    // Render ACF form
    acf_form([
        'post_id'            => $order_id,
        'new_post'           => false,
        'field_groups'       => [$field_group_id],
        'uploader'           => 'basic',
        'form'               => true,
        'return'             => '/wp-admin/admin.php?page=wc-orders&action=edit&id=' . $order_id,
        'html_submit_button' => '<input type="submit" class="acf-button button button-primary button-large" value="%s" />',
        'submit_value'       => __('Update', 'woo-lifecycle-manager'),
    ]);

    // Get the form HTML
    $form_html = ob_get_clean();

    return $form_html;
}

function wlm_acf_form_head()
{
    if (function_exists('acf_form_head')) {
        ob_start();
        acf_form_head();
        return ob_get_clean();
    }
    return '';
}

function wlm_acf_form_footer()
{
    if (function_exists('acf_form_footer')) {
        ob_start();
        acf_form_footer();
        return ob_get_clean();
    }
    return '';
}
