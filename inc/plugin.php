<?php
abstract class HalfjeBruin_Plugin
{
	protected $_config;

	public function __construct( HalfjeBruin_Config $config )
	{
		$this->_config = $config;
		
		$this->init();
	}

	abstract protected function init();

	protected function add_action( $action, $function = '', $priority = 10, $accepted_args = 1 )
	{
		add_action( $action, array( 
			$this, 
			$function == '' ? $action : $function 
		), $priority, $accepted_args );
	}

	protected function add_filter( $filter, $function = '', $priority = 10, $accepted_args = 1 )
	{
		add_filter( $filter, array( 
			$this, 
			$function == '' ? $filter : $function 
		), $priority, $accepted_args );
	}

	protected function add_shortcode( $action, $function = '' )
	{
		add_shortcode( $action, array( 
			$this, 
			$function == '' ? $action . '_shortcode' : $function 
		) );
	}

	protected function get_plugin_option( $id )
	{
		$options = get_option( 'plugin_options' );
		if ( is_array( $options ) && array_key_exists( $id, $options ) )
		{
			return $options[ $id ];
		}
		return false;
	}
}
