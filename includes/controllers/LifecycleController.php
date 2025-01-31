<?php

use Timber\Timber;

class LifecycleController
{
    public function getPartial($data)
    {
        error_log('LifecycleController::getPartial called');
        error_log('Data: ' . print_r($data, true));

        try {
            $partial = isset($data['partial']) ? sanitize_text_field($data['partial']) : '';
            $order_id = isset($data['order_id']) ? intval($data['order_id']) : 0;
            $status = isset($data['status']) ? sanitize_text_field($data['status']) : '';

            if (!$order_id) {
                throw new Exception('Invalid order ID');
            }

            $order = wc_get_order($order_id);

            if (!$order) {
                throw new Exception('Order not found');
            }

            $context = Timber::context();
            $context['order_status'] = $status ?: $order->get_status();
            $context['order_id'] = $order_id;
            $context['order'] = $order;

            $template = $this->getTemplateForPartial($partial, $context['order_status']);
            error_log('Template path: ' . $template);

            if (!file_exists(WLM_PLUGIN_DIR . 'partials/' . $template)) {
                error_log('Template not found, using fallback');
                $template = 'fallback.twig';
            }

            $result = Timber::compile('@wlm/' . $template, $context);
            error_log('Template compiled successfully');
            return $result;
        } catch (Exception $e) {
            error_log('LifecycleController Error: ' . $e->getMessage());
            return new WP_Error('lifecycle_error', $e->getMessage());
        }
    }

    private function getTemplateForPartial($partial, $status)
    {
        $status = str_replace('wc-', '', $status);
        return "{$partial}-{$status}.twig";
    }

    public function updateLifecycle($data)
    {
        error_log('LifecycleController::updateLifecycle called');
        error_log('Data: ' . print_r($data, true));

        try {
            $order_id = isset($data['order_id']) ? intval($data['order_id']) : 0;
            $status = isset($data['status']) ? sanitize_text_field($data['status']) : '';

            if (!$order_id) {
                throw new Exception('Invalid order ID');
            }

            $order = wc_get_order($order_id);
            if (!$order) {
                throw new Exception('Order not found');
            }

            // Update the order status
            $order->update_status($status);
            error_log('Order status updated to: ' . $status);

            // Return the new partial
            $data['partial'] = 'container';
            return $this->getPartial($data);
        } catch (Exception $e) {
            error_log('LifecycleController Error: ' . $e->getMessage());
            return new WP_Error('lifecycle_error', $e->getMessage());
        }
    }
}
