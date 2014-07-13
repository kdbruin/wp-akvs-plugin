<?php

function my_say_hello($name = '')
{
	global $my_plugin;
	$my_plugin->say_hello($name);
}
