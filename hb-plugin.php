<?php
/*
 * Plugin Name:	Halfje-Bruin Plugin
 * Description:	A plugin that contains common code for the Halfje-Bruin website
 * Version:		1.0
 * Author:		Kees de Bruin
 * Author URI:	http://www.halfje-bruin.nl/
 */
define('HB_PLUGIN_PATH', dirname(__FILE__));
define('HB_PLUGIN_URL', plugins_url('', __FILE__));
define('HB_PLUGIN_FILE', plugin_basename(__FILE__));
define('HB_PLUGIN_INC', HB_PLUGIN_PATH . '/inc');

require_once (HB_PLUGIN_INC . '/plugin.php');
require_once (HB_PLUGIN_INC . '/config.php');

$hb_class = 'HB';

if (is_admin())
{
	$hb_class .= 'Admin';
	require_once (HB_PLUGIN_INC . '/admin.php');
}
else
{
	$hb_class .= 'Public';
	require_once (HB_PLUGIN_INC . '/theme-functions.php');
	require_once (HB_PLUGIN_INC . '/public.php');
}

$hb_config_data = array(
		'plugin_file' => HB_PLUGIN_FILE
);

$hb_plugin = new $hb_class(new HBConfig($hb_config_data));

unset($hb_class, $hb_config_data);
