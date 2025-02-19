<?php

class Woo_Lifecycle_Manager_Admin extends Woo_Lifecycle_Manager_Base {
    public function enqueue_scripts() {
        if (is_admin() && isset($_GET['page']) && $_GET['page'] === 'wc-orders') {
            wp_enqueue_script($this->plugin_name, WLM_PLUGIN_URL . 'admin/js/woo-lifecycle-manager-admin.js', ['jquery'], $this->version, true);
            wp_localize_script($this->plugin_name, 'wlm_ajax', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('wlm_lifecycle_nonce'),
            ]);
            wp_localize_script($this->plugin_name, 'wlm_admin', [
                'plugin_url' => WLM_PLUGIN_URL,
            ]);
        }
    }

    // ... rest of the class
}
