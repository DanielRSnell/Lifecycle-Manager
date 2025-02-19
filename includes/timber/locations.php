<?php

if (!defined('ABSPATH')) {
    exit;
}

add_filter('timber/locations', function ($paths) {
    $paths['wlm'] = [
        WLM_PLUGIN_DIR . 'partials',
    ];
    return $paths;
});
