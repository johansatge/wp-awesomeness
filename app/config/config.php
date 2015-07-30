<?php

$config_found = false;
$hosts        = glob(rtrim(dirname(__FILE__), '/') . '/host/*.php');
foreach ($hosts as $host_path)
{
    $domains = require_once $host_path;
    if (is_array($domains))
    {
        foreach ($domains as $domain)
        {
            if (!empty($_SERVER['HTTP_HOST']) && preg_match('#^' . preg_quote($domain) . '$#i', $_SERVER['HTTP_HOST']))
            {
                $config_path = str_replace('config/host', 'config/config', $host_path);
                if (is_readable($config_path))
                {
                    require_once $config_path;
                    $config_found = true;
                    break;
                }
            }
        }
    }
    if ($config_found)
    {
        break;
    }
}
if (php_sapi_name() === 'cli')
{
    require_once rtrim(dirname(__FILE__), '/') . '/config/local.php';
    $config_found = true;
}
if (!$config_found)
{
    exit('<!-- Config file not found! -->');
}

if (!function_exists('printr'))
{
    function printr($stuff)
    {
        echo '<pre>';
        print_r($stuff);
        echo '</pre>';
    }
}