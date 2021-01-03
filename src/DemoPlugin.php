<?php declare(strict_types = 1);
namespace JW;

class DemoPlugin
{
    private static $instance = false;

    /**
     * Returns an instance of itself or creates it if it doesn't exist
     * @return bool|DemoPlugin
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * DemoPlugin constructor. Private to follow the singelton pattern
     */
    private function __construct()
    {
    }

    public static function register()
    {
        $self = self::getInstance();
        register_activation_hook(__FILE__, array($self, 'demoSetup'));
        register_deactivation_hook(__FILE__, array($self, 'demoCleanup'));
        //permalink
        $self->setupPermalinks();
        //AJAX functions
        $self->setupAjaxHooks();
    }

    /**
     * Hooks for creating permalink leading to plugin page
     */
    public function setupPermalinks()
    {
        add_action('init', array($this, 'demoUsers'));
        add_filter('template_include', array($this, 'displayUsers'));
    }

    /**
     * Add hooks for AJAX calls
     */
    public function setupAjaxHooks()
    {
        add_action('wp_ajax_jwGetUsers', array($this, 'ajaxGetUsers'));
        add_action('wp_ajax_nopriv_jwGetUsers', array($this, 'ajaxGetUsers'));
        add_action('wp_ajax_jwGetUser', array($this, 'ajaxGetUser'));
        add_action('wp_ajax_nopriv_jwGetUser', array($this, 'ajaxGetUser'));
    }

    /**
     * Setup permalink, scripts, and styles for the plugin
     */
    public function demoSetup()
    {
        $this->demoUsers();
        flush_rewrite_rules();
    }

    /**
     * Remove the permalink for the plugin
     */
    public function demoCleanup()
    {
        remove_rewrite_tag('jw-demo');
        remove_permastruct('jw-demo');
        flush_rewrite_rules();
    }

    /**
     * Hook the permalink for the plugin
     */
    public function demoUsers()
    {
        add_rewrite_tag('%jw-demo%', '([^/]+)');
        add_permastruct('jw-demo', '/%jw-demo%/');
    }

    /**
     * Load template file for displaying the users table
     * @param string
     * @return string
     */
    public function displayUsers($template): string
    {
        if ($query_var = get_query_var('jw-demo')) {
            wp_enqueue_script('jw-demo-script', PLUGIN_URL.'/js/jw-demo-script.js', array(), '0.1');
            wp_enqueue_style('jw-demo-style', PLUGIN_URL.'/style.css', array(), '0.1');
            //provide ajax endpoint, , and initial user data
            wp_localize_script('jw-demo-script', 'jwdemo', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                '_nonce' => wp_create_nonce('jw_demo_nonce'),
                'users' => $this->getUsers()
            ));
            return PLUGIN_PATH.'templates/users.php';
        } else {
            return $template;
        }
    }

    /**
     * Provide AJAX access to the getUsers function
     */
    public function ajaxGetUsers()
    {
        //verify nonce
        if (empty($_POST) || !wp_verify_nonce($_POST['nonce'], 'jw_demo_nonce')) {
            wp_send_json_error(
                array(
                    'msg' => 'Unable to verify request.'
                ),
                400
            );
        }
        //retrieve users and send to user
        $users = $this->getUsers();
        if ($users) {
            wp_send_json_success(
                array(
                    'users' => $users
                )
            );
        }
        //report errors
        wp_send_json_error(
            array(
                'msg' => 'Unable to get users'
            ),
            500
        );
    }

    /**
     * Gets JSON of user data from API or cache if available
     * @return string | bool
     */
    public function getUsers()
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

    /**
     * Provide AJAX access to the getUser function
     */
    public function ajaxGetUser()
    {
        //verify nonce
        if (empty($_POST) || !wp_verify_nonce($_POST['nonce'], 'jw_demo_nonce')) {
            wp_send_json_error(
                array(
                    'msg' => 'Unable to verify request.'
                ),
                400
            );
        }
        if (empty($_POST['jw_userid'])) {
            wp_send_json_error(
                array(
                    'msg' => 'Must specify user ID'
                ),
                400
            );
        }
        if (!is_numeric($_POST['jw_userid'])) {
            wp_send_json_error(
                array(
                    'msg' => 'Not a valid user ID'
                ),
                400
            );
        }
        $user_id = (int)$_POST['jw_userid'];
        //retrieve users and send to user
        $user = $this->getUser($user_id);
        if ($user) {
            wp_send_json_success(
                array(
                    'user' => $user
                )
            );
        }
        //report errors
        wp_send_json_error(
            array(
                'msg' => 'Unable to find user'
            ),
            500
        );
    }

    /**
     * Get info of a specific user by ID
     * @param int $id
     * @return string
     */
    public function getUser(int $id)
    {
        //check if cache exists
        $user = get_transient('jw_user_'.$id);
        if ($user) {
            return json_decode($user);
        }

        //obtain data from API
        $req = wp_remote_get("https://jsonplaceholder.typicode.com/users?id=$id");
        if (is_wp_error($req)) {
            return false;
        }
        $userdata = wp_remote_retrieve_body($req);
        //set cache for user data
        set_transient('jw_user_'.$id, $userdata, 60);
        return json_decode($userdata);
    }
}
