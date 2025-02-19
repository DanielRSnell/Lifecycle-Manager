<?php

if (!defined('ABSPATH')) {
    exit;
}

class Woo_Lifecycle_Manager_Loader extends Woo_Lifecycle_Manager_Base {
    private $controllers = array();

    public function load_dependencies() {
        require_once WLM_PLUGIN_DIR . 'includes/controllers/LifecycleController.php';
        require_once WLM_PLUGIN_DIR . 'includes/endpoints/class-wlm-base-controller.php';
        require_once WLM_PLUGIN_DIR . 'includes/endpoints/class-wlm-order-statuses-controller.php';
        require_once WLM_PLUGIN_DIR . 'includes/endpoints/class-wlm-acf-field-groups-controller.php';
        require_once WLM_PLUGIN_DIR . 'includes/endpoints/class-wlm-order-status-form-controller.php';
    }

    public function init_controllers() {
        $this->controllers[] = new WLM_Order_Statuses_Controller();
        $this->controllers[] = new WLM_ACF_Field_Groups_Controller();
        $this->controllers[] = new WLM_Order_Status_Form_Controller();
    }
}
