<?php

/**
 * Plugin Name:       SimPay Wordpress
 * Plugin URI:        https://simpay.pl
 * Description:       Use SimPay SMS service to use during registration or access to the post.
 * Version:           2.1.0
 * Author:            SimPay
 * Author URI:        https://simpay.pl
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simpay-wordpress.
 */

use SimPay\SimPayWordpressPlugin\PluginManagement\PluginManagerFactory;

defined('ABSPATH') || exit;

define('SIMPAY_PLUGIN_FILE', __FILE__);
define('SIMPAY_ABSPATH', __DIR__.'/');
define('SIMPAY_CONFIG_PATH', __DIR__.'/config/simpay-wordpress.json');

require_once __DIR__.'/vendor/autoload.php';

function activateSimPayWordpressPlugin(): void
{
    $pluginManager = PluginManagerFactory::create();

    $pluginManager->activatePlugin();
}

function deactivateSimPayWordpressPlugin(): void
{
    $pluginManager = PluginManagerFactory::create();

    $pluginManager->deactivatePlugin();
}

register_activation_hook(__FILE__, 'activateSimPayWordpressPlugin');
register_deactivation_hook(__FILE__, 'deactivateSimPayWordpressPlugin');

function initSimPay(): void
{
    $pluginManager = PluginManagerFactory::create();

    $pluginManager->init();
}

add_action('plugins_loaded', 'initSimPay', 11);
