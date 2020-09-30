<?php

/**
 * Plugin Name: Sitepilot
 * Plugin URI: https://sitepilot.io
 * Description: A plugin for managing and developing WordPress websites.
 * Version: 2.0.0-dev
 * Author: Sitepilot
 * Author URI: https://sitepilot.io
 * Copyright: (c) 2020 Sitepilot
 * License: GNU General Public License v2.0
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: sitepilot
 */

// Check if plugin was already loaded
if (defined('SITEPILOT_VERSION')) {
    return;
}

// Useful global constants
define('SITEPILOT_VERSION', '2.0.0-dev');
define('SITEPILOT_FILE', trailingslashit(dirname(__FILE__)) . 'sitepilot.php');
define('SITEPILOT_DIR', plugin_dir_path(SITEPILOT_FILE));
define('SITEPILOT_URL', plugins_url('/', SITEPILOT_FILE));

// Require Composer autoloader if it exists
if (file_exists(SITEPILOT_DIR . '/vendor/autoload.php')) {
    require_once SITEPILOT_DIR . 'vendor/autoload.php';
}

Sitepilot\Sitepilot::init();
