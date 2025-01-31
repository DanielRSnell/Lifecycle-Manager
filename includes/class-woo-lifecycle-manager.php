<?php
class Woo_Lifecycle_Manager {
    public function __construct() {
        $this->load_dependencies();
        $this->define_admin_hooks();
    }

    private function load_dependencies() {
        require_once WLM_PLUGIN_DIR . 'admin/class-woo-lifecycle-manager-admin.php';
    }

    private function define_admin_hooks() {
        $plugin_admin = new Woo_Lifecycle_Manager_Admin();
        add_action('admin_enqueue_scripts', array($plugin_admin, 'enqueue_scripts'));
    }

    public function run() {
        // Future plugin logic can be added here
    }
}
