<?php
class AKVSoesterkwartier_Admin extends AKVSoesterkwartier_Plugin
{

	protected function init()
	{
		register_activation_hook( $this->_config->plugin_file, array( 
			$this, 
			'activate' 
		) );
	}

	public function activate()
	{
	}
}
