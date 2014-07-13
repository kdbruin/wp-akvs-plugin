<?php
class HBAdmin extends HBPlugin
{

	protected function init()
	{
		register_activation_hook($this->_config->plugin_file, array(
				$this,
				'activate'
		));
	}

	public function activate()
	{
		// plugin activate code
	}
}
