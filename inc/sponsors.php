<?php

class AKVS_Sponsor
{
	protected $m_naam = '';
	protected $m_image = '';
	protected $m_url = '';

	public function __construct( $elt )
	{
		// print_r($elt);
		$this->m_naam = ( string ) $elt->attributes()->naam;
		$this->m_image = ( string ) $elt->attributes()->image;
		$this->m_url = ( string ) $elt->attributes()->url;
	}

	public function naam()
	{
		return $this->m_naam;
	}

	public function image()
	{
		return $this->m_image;
	}

	public function url()
	{
		return $this->m_url;
	}
}

class AKVS_Sponsors
{
	protected $m_data = array();

	public function __construct( $fnaam )
	{
		$xml = simpleXML_load_file( $fnaam, "SimpleXMLElement", LIBXML_NOCDATA );
		if ( $xml !== FALSE )
		{
			foreach ( $xml->sponsor as $sponsor )
			{
				$v = new AKVS_Sponsor( $sponsor );
				$this->m_data[] = $v;
			}
		}
	}

	public function sponsors()
	{
		return $this->m_data;
	}
}
