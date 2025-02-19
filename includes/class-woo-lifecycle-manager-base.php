<?php

if (!defined('ABSPATH')) {
    exit;
}

abstract class Woo_Lifecycle_Manager_Base {
    protected $plugin_name;
    protected $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
}
