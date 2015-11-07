<?php

/**
 * Plugin Name: HTTP Auth
 * Plugin URI: https://github.com/johansatge/http-auth
 * Description: HTTP authentication for WordPress
 * Version: 1.0.0
 * Author: Johan Satgé
 * Author URI: http://johansatge.com
 * License: MIT
 */

if (!class_exists('HTTPAuth') && defined('ABSPATH'))
{

    class HTTPAuth
    {

        private static $instance = null;

        public static function getInstance()
        {
            if (is_null(self::$instance))
            {
                self::$instance = new HTTPAuth();
            }
            return self::$instance;
        }

        public function checkCredentials()
        {
            if ($this->isDoingSpecialOperation() || !$this->isAuthenticationNeeded())
            {
                return;
            }
            $realm = defined('HTTP_AUTH_REALM') ? HTTP_AUTH_REALM : 'Restricted area';
            $users = $this->getUsers();
            if (count($users) === 0)
            {
                return;
            }
            if (defined('HTTP_AUTH_TYPE') && strtolower(HTTP_AUTH_TYPE) === 'digest')
            {
                $this->checkDigestAuth($realm, $users);
            }
            else
            {
                $this->checkBasicAuth($realm, $users);
            }
        }

        private function checkBasicAuth($realm, $users)
        {
            $headers        = apache_request_headers();
            $credentials    = !empty($headers['Authorization']) ? base64_decode(str_replace('Basic ', '', $headers['Authorization'])) : '';
            $maybe_user     = substr($credentials, 0, strpos($credentials, ':'));
            $maybe_password = substr($credentials, strpos($credentials, ':') + 1);
            foreach ($users as $user => $password)
            {
                if ($user === $maybe_user && $password === md5($maybe_password))
                {
                    return;
                }
            }
            $this->dieWithAuth($realm, 'Basic');
        }

        private function checkDigestAuth($realm, $users)
        {
            $headers = apache_request_headers();
            $digest  = !empty($headers['Authorization']) ? str_replace('Digest ', '', $headers['Authorization']) : '';
            if (empty($digest))
            {
                $this->dieWithAuth($realm, 'Digest');
            }
            $data         = array('nonce' => '', 'nc' => '', 'cnonce' => '', 'qop' => '', 'username' => '', 'uri' => '', 'response' => '');
            $digest_parts = explode(',', $digest);
            foreach ($digest_parts as $part)
            {
                preg_match_all('/([a-z]+)=(.*)/', $part, $matches);
                if (!empty($matches[1][0]) && isset($matches[2][0]))
                {
                    $data[$matches[1][0]] = trim($matches[2][0], '\'"');
                }
            }
            $password       = isset($users[$data['username']]) ? $users[$data['username']] : '';
            $a1             = md5($data['username'] . ':' . $realm . ':' . $password);
            $a2             = md5($_SERVER['REQUEST_METHOD'] . ':' . $data['uri']);
            $valid_response = md5($a1 . ':' . $data['nonce'] . ':' . $data['nc'] . ':' . $data['cnonce'] . ':' . $data['qop'] . ':' . $a2);
            if ($data['response'] != $valid_response)
            {
                $this->dieWithAuth($realm, 'Digest');
            }
        }

        private function dieWithAuth($realm, $type)
        {
            header('HTTP/1.0 401 Unauthorized');
            $headers = array(
                'Basic'  => 'Basic realm="' . $realm . '"',
                'Digest' => 'Digest realm="' . $realm . '",qop="auth",nonce="' . uniqid() . '",opaque="' . md5($realm) . '"'
            );
            header('WWW-Authenticate: ' . $headers[$type]);
            die($realm);
        }

        private function isAuthenticationNeeded()
        {
            if (empty($_SERVER['REQUEST_URI']))
            {
                return false;
            }
            $enabled_on_front = defined('HTTP_AUTH_FRONTEND') ? HTTP_AUTH_FRONTEND : false;
            $enabled_on_back  = defined('HTTP_AUTH_BACKEND') ? HTTP_AUTH_BACKEND : true;
            $is_admin         = preg_match('/(wp-login\.php|wp-admin)/', $_SERVER['REQUEST_URI']);
            if ((!$enabled_on_front && !$is_admin) || (!$enabled_on_back && $is_admin))
            {
                return false;
            }
            return true;
        }

        private function isDoingSpecialOperation()
        {
            $operations = array('DOING_AJAX', 'DOING_CRON');
            foreach ($operations as $op)
            {
                if (defined($op) && constant($op))
                {
                    return true;
                }
            }
            return false;
        }

        private function getUsers()
        {
            $users     = array();
            $raw_users = defined('HTTP_AUTH_USERS') ? explode(',', HTTP_AUTH_USERS) : array();
            foreach ($raw_users as $raw_user)
            {
                if (preg_match('/^([^:,]+):([^:,]+)$/', $raw_user, $matches))
                {
                    $users[$matches[1]] = $matches[2];
                }
            }
            return $users;
        }

    }

    HTTPAuth::getInstance()->checkCredentials();

}
