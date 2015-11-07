<?php

/**
 * Plugin Name: Roles Loader
 * Plugin URI: https://github.com/johansatge/roles-loader
 * Description: Easily override user roles and menus
 * Version: 1.0.0
 * Author: Johan Satgé
 * Author URI: http://johansatge.com
 * License: MIT
 */

defined('ABSPATH') or die('Cheatin\' uh?');

if (!class_exists('RolesLoader'))
{

    class RolesLoader
    {

        private $roles = array();
        private $restricted_screens = array();

        public function __construct()
        {
            $this->loadConfiguration();
            add_action('plugins_loaded', array($this, 'overrideRoles'));
            add_action('admin_menu', array($this, 'overrideAdminMenu'));
        }

        public function overrideRoles()
        {
            if (count($this->roles) > 0)
            {
                $GLOBALS['wp_user_roles'] = $this->roles;
            }
        }

        private function loadConfiguration()
        {
            $config_path = WP_CONTENT_DIR . '/roles.php';
            $maybe_roles = is_readable($config_path) ? require_once $config_path : false;
            if (!is_array($maybe_roles))
            {
                return;
            }
            foreach ($maybe_roles as $maybe_role_name => $maybe_role)
            {
                if (!empty($maybe_role['name']) && isset($maybe_role['capabilities']))
                {
                    $this->roles[$maybe_role_name] = array(
                        'name'         => $maybe_role['name'],
                        'capabilities' => is_array($maybe_role['capabilities']) ? $maybe_role['capabilities'] : array()
                    );
                }
                if (!empty($maybe_role['restricted_screens']) && is_array($maybe_role['restricted_screens']))
                {
                    $this->restricted_screens[$maybe_role_name] = $maybe_role['restricted_screens'];
                }
            }
        }

        public function overrideAdminMenu()
        {
            // Checks the current role
            $current_role = $this->getCurrentUserRole();
            if (empty($current_role) || empty($this->restricted_screens[$current_role]))
            {
                return;
            }
            $restricted_screens = $this->restricted_screens[$current_role];

            // Checks each first-level menu entry
            foreach ($GLOBALS['menu'] as $key => $item)
            {
                if (in_array($item[2], $restricted_screens)) // $item[2] is the path of the menu (ex: options-general.php)
                {
                    unset($GLOBALS['menu'][$key]);
                }

                // Checks the submenus of the current first-level entry
                if (!empty($GLOBALS['submenu'][$item[2]]))
                {
                    foreach ($GLOBALS['submenu'][$item[2]] as $sub_key => $sub_item)
                    {
                        if (in_array($item[2] . ':' . $sub_item[2], $restricted_screens))
                        {
                            unset($GLOBALS['submenu'][$item[2]][$sub_key]);
                        }
                    }
                }
            }
        }

        private function getCurrentUserRole()
        {
            $current_user = get_user_by('id', get_current_user_id());
            foreach ($GLOBALS['wp_roles']->role_names as $role_name => $role)
            {
                if (!empty($current_user) && isset($current_user->caps[$role_name]))
                {
                    return $role_name;
                }
            }
            return false;
        }

    }

    new RolesLoader();

}