<?php

/**
 * Plugin Name: jQuery to Footer
 * Plugin URI: https://github.com/johansatge/jquery-to-footer
 * Description: Moves jQuery and its dependencies to the footer
 * Version: 1.0.1
 * Author: Johan Satgé
 * Author URI: http://johansatge.com
 * License: MIT
 */

defined('ABSPATH') or die('Cheatin\' uh?');

add_action('init', function ()
{
    if (is_admin() || (!empty($_SERVER['SCRIPT_NAME']) && preg_match('/wp-login\.php$/', $_SERVER['SCRIPT_NAME'])))
    {
        return;
    }

    $wp_scripts = wp_scripts();

    $core_script    = $wp_scripts->registered['jquery-core'];
    //$migrate_script = $wp_scripts->registered['jquery-migrate'];

    wp_deregister_script('jquery');
    wp_deregister_script('jquery-core');
    wp_deregister_script('jquery-migrate');

    wp_register_script('jquery', $core_script->src, array(), $core_script->ver, true);
    //wp_register_script('jquery-core', $core_script->src, array(), $core_script->ver, true);
    //wp_register_script('jquery', $migrate_script->src, array('jquery-core'), $migrate_script->ver, true);
});
