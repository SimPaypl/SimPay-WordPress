<?php
/**
 *
 * @link              https://www.simpay.pl
 * @since             1.0.0
 * @package           simpay-wordpress
 *
 * @wordpress-plugin
 * Plugin Name:       SimPay.pl Płatności SMS dla WordPress
 * Plugin URI:        https://www.simpay.pl
 * Description:       Plugin umożliwający pobieranie opłat za dostęp do treści lub dostęp do rejestracji
 * Version:           1.1.0
 * Author:            Krzysztof Grzelak
 * Author URI:        https://www.simpay.pl
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 *
 **/

if (!defined('ABSPATH')) {
    exit();
}

require_once(plugin_dir_path(__FILE__) . 'req/class/SimPay.class.php');
require_once(plugin_dir_path(__FILE__) . 'req/functions.php');
require_once(plugin_dir_path(__FILE__) . 'req/simpay.php');

$simpay = new SimPay(get_option('simpay_key'), get_option('simpay_secret'));

register_activation_hook(__FILE__, 'simpay_install');
register_deactivation_hook(__FILE__, 'simpay_uninstall');

/*
	* Definiowanie formularza odpowiedzialnego za płatność przy rejestracji.
*/
add_action('register_form', 'simpay_register_form');
add_filter('registration_errors', 'simpay_register_validate', 10, 3);

/*
	* Definiowanie nowej zakładki w menu.
*/
add_action('admin_menu' , 'simpay_menu_add');

add_filter('the_content', 'simpay_content');