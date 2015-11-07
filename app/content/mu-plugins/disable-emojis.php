<?php

/**
 * Plugin Name: Disable Emojis
 * Plugin URI: https://github.com/johansatge/disable-emojis
 * Description: Disables emoji support
 * Version: 1.0.0
 * Author: Johan Satgé
 * Author URI: http://johansatge.com
 * License: MIT
 */

defined('ABSPATH') or die('Cheatin\' uh?');

add_action('init', function ()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
});

add_filter('tiny_mce_plugins', function ($plugins)
{
    $index = is_array($plugins) ? array_search('wpemoji', $plugins) : -1;
    if ($index > -1)
    {
        unset($plugins[$index]);
    }
    return $plugins;
});
