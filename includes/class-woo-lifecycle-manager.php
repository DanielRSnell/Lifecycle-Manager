<?php

    if (! defined('ABSPATH')) {
        exit; // Exit if accessed directly
    }

    class Woo_Lifecycle_Manager
    {
        private $plugin_name;
        private $version;

        public function __construct()
        {
            $this->plugin_name = 'woo-lifecycle-manager';
            $this->version     = WLM_VERSION;
            $this->load_dependencies();
            $this->define_admin_hooks();
        }

        private function load_dependencies()
        {
            require_once WLM_PLUGIN_DIR . 'includes/controllers/LifecycleController.php';
        }

        private function define_admin_hooks()
        {
            add_action('admin_enqueue_scripts', [$this, 'enqueue_styles']);
            add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
            add_action('wp_ajax_get_lifecycle_partial', [$this, 'ajax_get_lifecycle_partial']);
            add_action('wp_ajax_update_lifecycle_status', [$this, 'ajax_update_lifecycle_status']);
            add_action('woocommerce_admin_order_data_after_order_details', [$this, 'display_lifecycle_container']);
        }

        public function enqueue_styles()
        {
            if (is_admin() && isset($_GET['page']) && $_GET['page'] === 'wc-orders') {
                wp_enqueue_style($this->plugin_name, WLM_PLUGIN_URL . 'admin/css/woo-lifecycle-manager-admin.css', [], $this->version, 'all');
            }
        }

        public function enqueue_scripts()
        {
            if (is_admin() && isset($_GET['page']) && $_GET['page'] === 'wc-orders') {
                wp_enqueue_script($this->plugin_name, WLM_PLUGIN_URL . 'admin/js/woo-lifecycle-manager-admin.js', ['jquery'], $this->version, false);
                wp_localize_script($this->plugin_name, 'wlm_ajax', [
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'nonce'    => wp_create_nonce('wlm_lifecycle_nonce'),
                ]);
            }
        }

        public function ajax_get_lifecycle_partial()
        {
            check_ajax_referer('wlm_lifecycle_nonce', 'nonce');

            if (! current_user_can('manage_woocommerce')) {
                wp_send_json_error('Insufficient permissions');
                return;
            }

            $controller = new LifecycleController();
            $response   = $controller->getPartial($_POST);

            if (is_wp_error($response)) {
                wp_send_json_error($response->get_error_message());
            } else {
                wp_send_json_success($response);
            }
        }

        public function ajax_update_lifecycle_status()
        {
            check_ajax_referer('wlm_lifecycle_nonce', 'nonce');

            if (! current_user_can('manage_woocommerce')) {
                wp_send_json_error('Insufficient permissions');
                return;
            }

            $controller = new LifecycleController();
            $response   = $controller->updateLifecycle($_POST);

            if (is_wp_error($response)) {
                wp_send_json_error($response->get_error_message());
            } else {
                wp_send_json_success($response);
            }
        }

        public function display_lifecycle_container($order)
        {
            $controller = new LifecycleController();
            $partial    = $controller->getPartial([
                'partial'  => 'container',
                'status'   => $order->get_status(),
                'order_id' => $order->get_id(),
            ]);

            if (! is_wp_error($partial)) {
                echo $partial;
            }
        }

        public function run()
        {
            $this->define_admin_hooks();
        }
}
