<?php

/**
 * Environment variables
 */
define('ENV_PATH', rtrim(dirname(__FILE__), DIRECTORY_SEPARATOR) . '/.environment');
preg_replace_callback('/^([A-Z0-9_]+)=(.*)\n?/m', function ($matches)
{
    define($matches[1], $matches[2]);
}, is_readable(ENV_PATH) ? file_get_contents(ENV_PATH) : '');

/**
 * Errors
 */
error_reporting(-1);
define('WP_DEBUG', true);
define('WP_DEBUG_DISPLAY', true);

/**
 * Database
 */
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');
$GLOBALS['table_prefix'] = 'wp_';

/**
 * File settings
 */
define('DISALLOW_FILE_MODS', true);
define('WP_CONTENT_DIR', dirname(__FILE__) . '/content');
define('WP_CONTENT_URL', !empty($_SERVER['HTTP_HOST']) ? 'http://' . $_SERVER['HTTP_HOST'] . '/content' : '/content');

/**
 * Boot
 */
if (!defined('ABSPATH'))
{
    define('ABSPATH', dirname(__FILE__) . '/wp/');
}
require_once(ABSPATH . 'wp-settings.php');