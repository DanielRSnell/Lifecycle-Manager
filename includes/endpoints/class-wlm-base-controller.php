<?php

if (!defined('ABSPATH')) {
    exit;
}

abstract class WLM_Base_Controller {
    public function __construct() {
        add_action('rest_api_init', array($this, 'register_routes'));
    }

    abstract public function register_routes();

    protected function wc_check($key) {
        $name = str_replace('wc-', '', $key);
        $name = str_replace('-', ' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ', '', $name);
        return $name;
    }

    protected function find_matching_acf_group($wc_check) {
        $field_groups = acf_get_field_groups();
        foreach ($field_groups as $group) {
            $group_name = str_replace(' ', '', $group['title']);
            if ($group_name === $wc_check) {
                return $group;
            }
        }
        return null;
    }
}
