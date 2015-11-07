<?php

/**
 * Plugin Name: Mandatory Plugins
 * Plugin URI: https://github.com/johansatge/mandatory-plugins
 * Description: Forces activation of specific plugins
 * Version: 1.0.0
 * Author: Johan Satgé
 * Author URI: http://johansatge.com
 * License: MIT
 */

defined('ABSPATH') or die('Cheatin\' uh?');

if (!class_exists('MandatoryPlugins'))
{

    class MandatoryPlugins
    {

        private $plugins = array();

        public function __construct()
        {
            $this->plugins = $this->getMandatoryPlugins();
            if (count($this->plugins) > 0)
            {
                $this->checkAndUpdateActivePlugins();
                add_filter('plugin_action_links', array($this, 'disableDeactivationLinks'), 10, 2);
                add_action('deactivate_plugin', array($this, 'blockPluginDeactivation'));
            }
        }

        /**
         * Removes "Deactivate" links on the wp-admin/plugins.php page
         * @param array  $actions
         * @param string $plugin
         * @return array
         */
        public function disableDeactivationLinks($actions, $plugin)
        {
            if (in_array($plugin, $this->plugins) && !empty($actions['deactivate']))
            {
                $actions['deactivate'] = __('Deactivate');
            }
            return $actions;
        }

        /**
         * Blocks direct plugin deactivation if needed
         * @param string $plugin
         */
        public function blockPluginDeactivation($plugin)
        {
            if (in_array($plugin, $this->plugins))
            {
                wp_die('Cheatin\' uh?');
            }
        }

        /**
         * Gets the list of mandatory plugins from the project configuration
         * @return array
         */
        private function getMandatoryPlugins()
        {
            $plugins       = array();
            $maybe_plugins = defined('MANDATORY_PLUGINS') ? explode(',', MANDATORY_PLUGINS) : array();
            foreach ($maybe_plugins as $plugin)
            {
                if (file_exists(WP_PLUGIN_DIR . '/' . $plugin))
                {
                    $plugins[] = $plugin;
                }
                else
                {
                    trigger_error(sprintf('Mandatory plugin <strong>%s</strong> not found.', esc_html($plugin)), E_USER_NOTICE);
                }
            }
            return $plugins;
        }

        /**
         * Checks active plugins and activate mandatory ones, if needed
         */
        private function checkAndUpdateActivePlugins()
        {
            $active_plugins = get_option('active_plugins', array());
            foreach ($this->plugins as $plugin)
            {
                if (!in_array($plugin, $active_plugins))
                {
                    $this->activatePlugin($plugin);
                }
            }
        }

        /**
         * Activates a plugin
         * Based on wp-admin/includes/plugin.php:activate_plugin()
         * @param string $plugin
         */
        private function activatePlugin($plugin)
        {
            $active_plugins   = get_option('active_plugins', array());
            $active_plugins[] = $plugin;
            sort($active_plugins);
            do_action('activate_plugin', $plugin);
            update_option('active_plugins', $active_plugins);
            do_action('activate_' . $plugin);
            do_action('activated_plugin', $plugin);
        }

    }

    new MandatoryPlugins();

}