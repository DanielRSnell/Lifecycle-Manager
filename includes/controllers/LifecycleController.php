<?php

use Timber\Timber;

class LifecycleController
{
    private $handlers = [];

    public function __construct()
    {
        $this->loadHandlers();
    }

    private function loadHandlers()
    {
        $handlerFiles = glob(WLM_PLUGIN_DIR . 'includes/handlers/*.php');
        foreach ($handlerFiles as $file) {
            require_once $file;
            $className = 'WLM_' . basename($file, '.php') . '_Handler';
            if (class_exists($className)) {
                $this->handlers[] = new $className();
            }
        }
    }

    public function getPartial($data)
    {
        foreach ($this->handlers as $handler) {
            if (method_exists($handler, 'canHandle') && $handler->canHandle($data)) {
                return $handler->handle($data);
            }
        }

        // Fallback to default handling
        return $this->defaultGetPartial($data);
    }

    private function defaultGetPartial($data)
    {
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

            // Add form data to context with proper ACF field group matching
            $context['form'] = [
                'order_id' => $order_id,
                'status_id' => $context['order_status'],
                'field_group_id' => $this->findMatchingFieldGroup($context['order_status'])
            ];

            $template = $this->getTemplateForPartial($partial, $context['order_status']);

            if (!file_exists(WLM_PLUGIN_DIR . 'partials/' . $template)) {
                $template = 'fallback.twig';
            }

            $result = Timber::compile('@wlm/' . $template, $context);
            return $result;
        } catch (Exception $e) {
            return new WP_Error('lifecycle_error', $e->getMessage());
        }
    }

    private function getTemplateForPartial($partial, $status)
    {
        $status = str_replace('wc-', '', $status);
        return "{$partial}-{$status}.twig";
    }

    private function findMatchingFieldGroup($status)
    {
        if (!function_exists('acf_get_field_groups')) {
            return null;
        }

        // Convert status to the expected ACF group name format
        $status = str_replace('wc-', '', $status);
        $status = str_replace('-', ' ', $status);
        $status = ucwords($status);
        $status = str_replace(' ', '', $status);

        // Get all ACF field groups
        $field_groups = acf_get_field_groups();
        
        // Find matching group
        foreach ($field_groups as $group) {
            $group_name = str_replace(' ', '', $group['title']);
            if ($group_name === $status) {
                return $group['key'];
            }
        }

        return null;
    }

    public function updateLifecycle($data)
    {
        foreach ($this->handlers as $handler) {
            if (method_exists($handler, 'canHandleUpdate') && $handler->canHandleUpdate($data)) {
                return $handler->handleUpdate($data);
            }
        }

        // Fallback to default handling
        return $this->defaultUpdateLifecycle($data);
    }

    private function defaultUpdateLifecycle($data)
    {
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

            $order->update_status($status);

            $data['partial'] = 'container';
            return $this->getPartial($data);
        } catch (Exception $e) {
            return new WP_Error('lifecycle_error', $e->getMessage());
        }
    }
}
