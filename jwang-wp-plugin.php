<?php declare(strict_types = 1);
/*
Plugin Name: Johnny Wang Demo Plugin
Plugin URI: https://johnnywang.dev/wp-demo
Description: Demo Plugin for Inpsyde
Version: 1.0
Author: Johnny Wang
Author URI: https://johnnywang.dev/
License: GPLv3
*/

//directory URL for scripts, stylesheets, etc
define('JWDEMO_DIR', plugins_url('', __FILE__));

register_activation_hook(__FILE__, 'jw_demo_setup');
/**
 * Setup permalink, scripts, and styles for the plugin
 *
 * @return void
 */
function jw_demo_setup()
{
    jw_demo_users();
    flush_rewrite_rules();
}

register_deactivation_hook(__FILE__, 'jw_demo_cleanup');
/**
 * Remove the permalink for the plugin
 */
function jw_demo_cleanup()
{
    remove_rewrite_tag('jw-demo');
    remove_permastruct('jw-demo');
    flush_rewrite_rules();
}

add_action('init', 'jw_demo_users');
/**
 * Hook the permalink for the plugin
 */
function jw_demo_users()
{
    add_rewrite_tag('%jw-demo%', '([^/]+)');
    add_permastruct('jw-demo', '/%jw-demo%/');
}

add_filter('template_include', 'jw_get_users');
/**
 * Load template file for displaying the users table
 * @param string
 * @return string
 */
function jw_get_users($template)
{
    if ($query_var = get_query_var('jw-demo')) {
        wp_enqueue_script('jw-demo-script', JWDEMO_DIR.'/js/jw-demo-script.js', array(), '0.1');
        wp_enqueue_style('jw-demo-style', JWDEMO_DIR.'/style.css', array(), '0.1');
        return plugin_dir_path(__FILE__).'/templates/users.php';
    } else {
        return $template;
    }
}
