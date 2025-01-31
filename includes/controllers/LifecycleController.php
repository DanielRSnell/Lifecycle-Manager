<?php

use Timber\Timber;

class LifecycleController
{
    public function getPartial($data)
    {
        $partial  = isset($data['partial']) ? sanitize_text_field($data['partial']) : '';
        $order_id = isset($data['order_id']) ? intval($data['order_id']) : 0;
        $status   = isset($data['status']) ? sanitize_text_field($data['status']) : '';

        $order = wc_get_order($order_id);

        if (! $order) {
            return 'Invalid order ID';
        }

        $context                 = Timber::context();
        $context['order_status'] = $status ?: $order->get_status();
        $context['order_id']     = $order_id;
        $context['order']        = $order;

        $template = $this->getTemplateForPartial($partial, $context['order_status']);

        if (Timber::template_exists($template)) {
            return Timber::compile($template, $context);
        } else {
            return Timber::compile('fallback.twig', $context);
        }
    }

    private function getTemplateForPartial($partial, $status)
    {
        $status = str_replace('wc-', '', $status);
        return "partials/{$partial}-{$status}.twig";
    }

    public function updateLifecycle($data)
    {
        $order_id = isset($data['order_id']) ? intval($data['order_id']) : 0;
        $status   = isset($data['status']) ? sanitize_text_field($data['status']) : '';

        $order = wc_get_order($order_id);
        if (! $order) {
            return 'Invalid order ID';
        }

        // Update the order status
        $order->update_status($status);

                                        // Return the new partial
        $data['partial'] = 'container'; // Assuming we want to return the main container after update
        return $this->getPartial($data);
    }
}
