<?php
/*
Plugin Name: My Plugin
Description: How to design a plugin with classes
Version: 1.0
Author: Greg F
Author URI: http://www.keleko.com
*/

define( 'MY_PLUGIN_PATH', dirname( __FILE__ ) );
define( 'MY_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'MY_PLUGIN_FILE', plugin_basename( __FILE__ ) );
define( 'MY_PLUGIN_INC', MY_PLUGIN_PATH . '/inc' );

require_once( MY_PLUGIN_INC . '/plugin.php' );
require_once( MY_PLUGIN_INC . '/config.php' );

$my_class = 'My_';

if ( is_admin() ) {
    $my_class .= 'Admin';
    require_once( MY_PLUGIN_INC . '/admin.php' );
} else {
    $my_class .= 'Public';
    require_once( MY_PLUGIN_INC . '/theme-functions.php' );
    require_once( MY_PLUGIN_INC . '/public.php' );
}

$my_config_data = array(
    'plugin_file' => MY_PLUGIN_FILE,
);

$my_plugin = new $my_class( new My_Config( $my_config_data ) );

unset( $my_class, $my_config_data );
