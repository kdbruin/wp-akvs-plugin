<?php
/*
 * Plugin Name:	AKV Soesterkwartier Plugin
 * Description:	A plugin that contains common code for the AKV Soesterkwartier website
 * Version:	1.0
 * Author:	Kees de Bruin
 * Author URI:	http://www.halfje-bruin.nl/
 */

define( 'AKVS_PLUGIN_PATH', dirname( __FILE__ ) );
define( 'AKVS_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'AKVS_PLUGIN_FILE', plugin_basename( __FILE__ ) );
define( 'AKVS_PLUGIN_INC', AKVS_PLUGIN_PATH . '/inc' );

require_once ( AKVS_PLUGIN_INC . '/plugin.php' );
require_once ( AKVS_PLUGIN_INC . '/config.php' );

$akvs_class = 'AKVSoesterkwartier';

if ( is_admin() )
{
	$akvs_class .= '_Admin';
	require_once ( AKVS_PLUGIN_INC . '/admin.php' );
}
else
{
	$akvs_class .= '_Public';
	require_once ( AKVS_PLUGIN_INC . '/theme-functions.php' );
	require_once ( AKVS_PLUGIN_INC . '/public.php' );
}

$akvs_config_data = array( 
	'plugin_file' => AKVS_PLUGIN_FILE 
);

$akvs_plugin = new $akvs_class( new AKVSoesterkwartier_Config( $akvs_config_data ) );

unset( $akvs_class, $akvs_config_data );
