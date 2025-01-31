<?php
class Woo_Lifecycle_Manager
{
    public function __construct()
    {
        $this->load_dependencies();
        $this->define_hooks();
    }

    private function load_dependencies()
    {
        require_once WLM_PLUGIN_DIR . 'includes/controllers/LifecycleController.php';
    }

    private function define_hooks()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_ajax_get_lifecycle_partial', [$this, 'ajax_get_lifecycle_partial']);
        add_action('wp_ajax_update_lifecycle_status', [$this, 'ajax_update_lifecycle_status']);
    }

    public function enqueue_scripts()
    {
        if (is_admin() && isset($_GET['page']) && $_GET['page'] === 'wc-orders') {
            wp_enqueue_script('woo-lifecycle-manager', WLM_PLUGIN_URL . 'admin/js/woo-lifecycle-manager-admin.js', ['jquery'], WLM_VERSION, true);
            wp_localize_script('woo-lifecycle-manager', 'wlm_ajax', [
                'nonce' => wp_create_nonce('wlm_lifecycle_nonce'),
            ]);
        }
    }

    public function ajax_get_lifecycle_partial()
    {
        check_ajax_referer('wlm_lifecycle_nonce', 'nonce');

        $controller = new LifecycleController();
        $response   = $controller->getPartial($_POST);

        wp_send_json_success($response);
    }

    public function ajax_update_lifecycle_status()
    {
        check_ajax_referer('wlm_lifecycle_nonce', 'nonce');

        $controller = new LifecycleController();
        $response   = $controller->updateLifecycle($_POST);

        wp_send_json_success($response);
    }

    public function run()
    {
        // Future plugin logic can be added here
    }
}
