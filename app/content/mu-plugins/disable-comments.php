<?php

/**
 * Plugin Name: Disable Comments
 * Plugin URI: https://github.com/johansatge/disable-comments
 * Description: Disables comments, pingbacks and trackbacks
 * Version: 1.0.1
 * Author: Johan Satgé
 * Author URI: http://johansatge.com
 * License: MIT
 */

if (!class_exists('DisableComments'))
{

    class DisableComments
    {

        private static $instance = null;

        public static function getInstance()
        {
            if (is_null(self::$instance))
            {
                self::$instance = new DisableComments();
            }
            return self::$instance;
        }

        public function disableAll()
        {
            add_filter('comments_open', '__return_false');
            add_filter('pings_open', '__return_false');
            add_action('wp_before_admin_bar_render', array($this, 'cleanAdminBar'));
            add_filter('wp_headers', array($this, 'cleanHttpHeaders'));
            add_filter('xmlrpc_methods', array($this, 'cleanXMLRPCMethods'));
            add_action('wp_loaded', array($this, 'cleanPostTypes'));
            add_action('admin_menu', array($this, 'cleanAdminMenu'));
            add_action('wp', array($this, 'blockCommentsFeed'));
        }

        public function cleanAdminBar()
        {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('comments');
        }

        public function cleanHttpHeaders($headers)
        {
            unset($headers['X-Pingback']);
        }

        public function cleanXMLRPCMethods($methods)
        {
            unset($methods['pingback.ping']);
            return $methods;
        }

        public function cleanPostTypes()
        {
            $post_types = get_post_types();
            foreach ($post_types as $post_type)
            {
                if (post_type_supports($post_type, 'comments'))
                {
                    remove_post_type_support($post_type, 'comments');
                }
            }
        }

        public function cleanAdminMenu()
        {
            if (!empty($_SERVER['REQUEST_URI']) && preg_match('/(edit-comments|options-discussion)\.php$/', $_SERVER['REQUEST_URI']))
            {
                wp_die(__('Cheatin&#8217; uh?'), 403);
            }
            foreach ($GLOBALS['menu'] as $position => $item)
            {
                if (!empty($item[2]) && $item[2] === 'edit-comments.php')
                {
                    unset($GLOBALS['menu'][$position]);
                }
            }
            foreach ($GLOBALS['submenu'] as $position => $items)
            {
                foreach ($items as $sub_position => $sub_item)
                {
                    if (!empty($sub_item[2]) && $sub_item[2] === 'options-discussion.php')
                    {
                        unset($GLOBALS['submenu'][$position][$sub_position]);
                    }
                }
            }
        }

        public function blockCommentsFeed()
        {
            if (is_comment_feed())
            {
                status_header(404);
                $template = get_query_template('404');
                if ($template)
                {
                    require $template;
                }
                exit;
            }
        }

    }

    DisableComments::getInstance()->disableAll();

}