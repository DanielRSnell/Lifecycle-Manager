<?php
class Woo_Lifecycle_Manager_Admin {
    public function enqueue_scripts() {
        if (is_admin() && isset($_GET['page']) && $_GET['page'] === 'wc-orders') {
            wp_enqueue_script('woo-lifecycle-manager', WLM_PLUGIN_URL . 'admin/js/woo-lifecycle-manager-admin.js', array('jquery'), WLM_VERSION, true);
            wp_enqueue_style('woo-lifecycle-manager', WLM_PLUGIN_URL . 'admin/css/woo-lifecycle-manager-admin.css', array(), WLM_VERSION);
        }
    }
}
