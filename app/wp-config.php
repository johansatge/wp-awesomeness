<?php

error_reporting(-1);

define('ENV_PATH', rtrim(dirname(__FILE__), DIRECTORY_SEPARATOR) . '/.environment');
preg_replace_callback('/^([A-Z0-9_]+)=(.*)\n?/m', function ($matches)
{
    define($matches[1], $matches[2]);
}, is_readable(ENV_PATH) ? file_get_contents(ENV_PATH) : '');

define('DISALLOW_FILE_MODS', true);
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');
define('WPLANG', 'en_US');
define('WP_DEBUG', true);
define('WP_DEBUG_DISPLAY', true);

$table_prefix  = 'wp_';

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');