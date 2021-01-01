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

add_filter('template_include', 'jw_display_users');
/**
 * Load template file for displaying the users table
 * @param string
 * @return string
 */
function jw_display_users($template): string
{
    if ($query_var = get_query_var('jw-demo')) {
        wp_enqueue_script('jw-demo-script', JWDEMO_DIR.'/js/jw-demo-script-debug.js', array(), '0.1');
        wp_enqueue_style('jw-demo-style', JWDEMO_DIR.'/style.css', array(), '0.1');
        //provide ajax endpoint, nonce, and initial user data
        wp_localize_script('jw-demo-script', 'jwdemo', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('jw-demo'),
            'users' => jw_get_users()
        ));
        return plugin_dir_path(__FILE__).'/templates/users.php';
    } else {
        return $template;
    }
}

add_action('wp_ajax_nopriv_get_users', 'jw_ajax_get_users');
/**
 * Provide AJAX access to the jw_get_users function
 */
function jw_ajax_get_users()
{
    //verify nonce
    $nonce = $_POST['nonce'];
    if (empty($_POST) || !wp_verify_nonce($nonce, 'jw-demo')) {
        wp_send_json_error(
            array(
            'success' => false,
            'msg' => 'Unable to verify request.',
            'nonce' => $nonce
            ),
            400
        );
    }
    //retrieve users and send to user
    $users = jw_get_users();
    if ($users) {
        wp_send_json_success(
            array(
                'success' => true,
                'users' => $users
            )
        );
    }
    //report errors
    wp_send_json_error(
        array(
            'success' => false,
            'msg' => 'Unable to get users'
        ),
        500
    );
}

/**
 * Gets JSON of user data from API or cache if available
 * @return string | bool
 */
function jw_get_users()
{
    //check if cache exists
    $users = get_transient('jw_users');
    if ($users) {
        return json_decode($users);
    }

    //obtain data from API
    $req = wp_remote_get('https://jsonplaceholder.typicode.com/users');
    if (is_wp_error($req)) {
        return false;
    }
    $userdata = wp_remote_retrieve_body($req);
    //set cache for user data
    set_transient('jw_users', $userdata, 60);
    return json_decode($userdata);
}
