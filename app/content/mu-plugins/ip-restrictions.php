<?php

/**
 * Plugin Name: IP Restrictions
 * Plugin URI: https://github.com/johansatge/ip-restrictions
 * Description: Access restrictions based on IP addresses
 * Version: 1.0.0
 * Author: Johan Satgé
 * Author URI: http://johansatge.com
 * License: MIT
 */

if (!class_exists('IPRestrictions'))
{

    class IPRestrictions
    {

        private static $instance = null;

        public static function getInstance()
        {
            if (is_null(self::$instance))
            {
                self::$instance = new IPRestrictions();
            }
            return self::$instance;
        }

        public function checkRestrictions()
        {
            if ($this->isDoingSpecialOperation() || !$this->isRestrictionNeeded() || empty($_SERVER['REMOTE_ADDR']))
            {
                return;
            }
            $current_ip = $_SERVER['REMOTE_ADDR'];
            $ips        = defined('IP_RESTRICTIONS_LIST') && IP_RESTRICTIONS_LIST != '' ? explode(',', IP_RESTRICTIONS_LIST) : array();
            $is_allowed = false;
            foreach ($ips as $ip)
            {
                list($base_ip, $mask) = array_pad(explode('/', $ip), 2, false);
                if (!preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $base_ip) || (!empty($mask) && !is_numeric($mask)))
                {
                    trigger_error('IP Restrictions: "' . $ip . '" is not a valid IP address', E_USER_NOTICE);
                    continue;
                }
                if ($this->isInRange($current_ip, $base_ip, $mask))
                {
                    $is_allowed = true;
                    break;
                }
            }
            if (!$is_allowed && count($ips) > 0)
            {
                $this->dieWithMessage();
            }
        }

        private function dieWithMessage()
        {
            header('HTTP/1.0 403 Forbidden');
            $message = defined('IP_RESTRICTIONS_FALLBACK') ? IP_RESTRICTIONS_FALLBACK : '<h1>403 Forbidden</h1>';
            if (preg_match('/\.(php|html)$/', $message) && is_readable($message))
            {
                require $message;
            }
            else
            {
                echo $message;
            }
            exit;
        }

        private function isInRange($ip, $base_ip, $mask)
        {
            if (empty($mask))
            {
                return $ip == $base_ip;
            }
            $min_ip = ip2long($base_ip) & ((-1 << (32 - (int)$mask)));
            $max_ip = ip2long($base_ip) + pow(2, (32 - (int)$mask)) - 1;
            $ip     = ip2long($ip);
            return $ip >= $min_ip && $ip <= $max_ip;
        }

        private function isRestrictionNeeded()
        {
            if (empty($_SERVER['REQUEST_URI']))
            {
                return false;
            }
            $enabled_on_front = defined('IP_RESTRICTIONS_FRONTEND') ? IP_RESTRICTIONS_FRONTEND : false;
            $enabled_on_back  = defined('IP_RESTRICTIONS_BACKEND') ? IP_RESTRICTIONS_BACKEND : true;
            $is_admin         = preg_match('/(wp-login\.php|wp-admin)/', $_SERVER['REQUEST_URI']);
            if ((!$enabled_on_front && !$is_admin) || (!$enabled_on_back && $is_admin))
            {
                return false;
            }
            return true;
        }

        private function isDoingSpecialOperation()
        {
            $operations = array('DOING_AJAX');
            foreach ($operations as $op)
            {
                if (defined($op) && constant($op))
                {
                    return true;
                }
            }
            return false;
        }

    }

    IPRestrictions::getInstance()->checkRestrictions();

}