<?php

function hb_say_hello( $name = '' )
{
	global $hb_plugin;
	$hb_plugin->say_hello( $name );
}
