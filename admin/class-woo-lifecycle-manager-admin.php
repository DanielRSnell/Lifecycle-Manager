<?php

class Woo_Lifecycle_Manager_Admin
{

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version     = $version;
        add_filter('script_loader_tag', [$this, 'add_type_attribute'], 10, 3);
    }

    private function is_wc_order_edit_page()
    {
        if (! isset($_GET['page']) || $_GET['page'] !== 'wc-orders') {
            return false;
        }
        if (! isset($_GET['action']) || $_GET['action'] !== 'edit') {
            return false;
        }
        if (! isset($_GET['id']) || ! is_numeric($_GET['id'])) {
            return false;
        }

        return true;
    }

    public function enqueue_styles()
    {
        if ($this->is_wc_order_edit_page()) {
            wp_enqueue_style($this->plugin_name, WLM_PLUGIN_URL . 'admin/css/woo-lifecycle-manager-admin.css', [], $this->version, 'all');
        }
    }

    public function enqueue_scripts()
    {
        if ($this->is_wc_order_edit_page()) {
            wp_enqueue_script($this->plugin_name, WLM_PLUGIN_URL . 'admin/js/woo-lifecycle-manager-admin.js', ['jquery'], $this->version, true);

            wp_localize_script($this->plugin_name, 'wlm_ajax', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('wlm_lifecycle_nonce'),
            ]);
        }
    }

    public function add_type_attribute($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ($this->plugin_name !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

    public function display_lifecycle_container($order)
    {
        if (! $order instanceof WC_Order) {
            return;
        }

        $controller = new LifecycleController();
        $partial    = $controller->getPartial([
            'partial'  => 'container',
            'status'   => $order->get_status(),
            'order_id' => $order->get_id(),
        ]);

        if (! is_wp_error($partial)) {
            echo '<div id="lifecycle-container">' . $partial . '</div>';
        }
    }
}
