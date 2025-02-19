<?php

if (!defined('ABSPATH')) {
    exit;
}

class Woo_Lifecycle_Manager
{
    private $plugin_name;
    private $version;
    private $controllers = array();
    private $admin;

    public function __construct()
    {
        $this->plugin_name = 'woo-lifecycle-manager';
        $this->version     = WLM_VERSION;
        $this->load_dependencies();
        $this->init_controllers();
        $this->init_admin();
    }

    private function load_dependencies()
    {
        require_once WLM_PLUGIN_DIR . 'includes/controllers/LifecycleController.php';
        require_once WLM_PLUGIN_DIR . 'includes/endpoints/class-wlm-base-controller.php';
        require_once WLM_PLUGIN_DIR . 'includes/endpoints/class-wlm-order-statuses-controller.php';
        require_once WLM_PLUGIN_DIR . 'includes/endpoints/class-wlm-acf-field-groups-controller.php';
        require_once WLM_PLUGIN_DIR . 'includes/endpoints/class-wlm-order-status-form-controller.php';
        require_once WLM_PLUGIN_DIR . 'admin/class-woo-lifecycle-manager-admin.php';
    }

    private function init_controllers()
    {
        $this->controllers[] = new WLM_Order_Statuses_Controller();
        $this->controllers[] = new WLM_ACF_Field_Groups_Controller();
        $this->controllers[] = new WLM_Order_Status_Form_Controller();
    }

    private function init_admin()
    {
        $this->admin = new Woo_Lifecycle_Manager_Admin($this->plugin_name, $this->version);
    }

    public function run()
    {
        add_action('admin_enqueue_scripts', [$this->admin, 'enqueue_styles']);
        add_action('admin_enqueue_scripts', [$this->admin, 'enqueue_scripts']);
        add_action('woocommerce_after_order_data', [$this->admin, 'display_lifecycle_container']);
        add_action('wp_ajax_get_lifecycle_partial', [$this, 'ajax_get_lifecycle_partial']);
        add_action('wp_ajax_update_lifecycle_status', [$this, 'ajax_update_lifecycle_status']);
    }

    public function ajax_get_lifecycle_partial()
    {
        check_ajax_referer('wlm_lifecycle_nonce', 'nonce');

        if (!current_user_can('manage_woocommerce')) {
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

        if (!current_user_can('manage_woocommerce')) {
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
}
