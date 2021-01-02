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
define('PLUGIN_PATH', plugin_dir_path(__FILE__));
define('PLUGIN_URL', plugins_url('', __FILE__));
include PLUGIN_PATH.'src/DemoPlugin.php';
use JW\DemoPlugin;

DemoPlugin::register();
