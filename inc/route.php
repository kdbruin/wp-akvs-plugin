<?php

class AKVS_RouteBeschrijving
{
	protected $m_naam = '';
	protected $m_slug = '';
	protected $m_straat = '';
	protected $m_postcode = '';
	protected $m_plaats = '';
	protected $m_telefoon = '';
	protected $m_lat = '';
	protected $m_lng = '';

	public function __construct( $elt )
	{
		$atts = $elt->attributes();
		$this->m_naam = ( string ) $atts->naam;
		$this->m_slug = akvs_get_slug( $this->m_naam );
		$this->m_straat = ( string ) $atts->straat;
		$this->m_postcode = ( string ) $atts->postcode;
		$this->m_plaats = ( string ) $atts->plaats;
		$this->m_telefoon = ( string ) $atts->telefoon;
		$this->m_lat = ( string ) $atts->lat;
		$this->m_lng = ( string ) $atts->lng;
	}

	public function naam()
	{
		return $this->m_naam;
	}

	public function slug()
	{
		return $this->m_slug;
	}

	public function straat()
	{
		return $this->m_straat;
	}

	public function postcode()
	{
		return $this->m_postcode;
	}

	public function plaats()
	{
		return $this->m_plaats;
	}

	public function telefoon()
	{
		return $this->m_telefoon;
	}

	public function lat()
	{
		return $this->m_lat;
	}

	public function lng()
	{
		return $this->m_lng;
	}

	public function adres()
	{
		return $this->m_straat . ', ' . $this->m_postcode . ' ' . $this->m_plaats;
	}

	public function infotext()
	{
		$result = $this->adres() . '<br /><span class="telefoon">' . $this->m_telefoon . '</span>';
		return $result;
	}
}

class AKVS_RouteBeschrijvingen
{
	protected $m_routes = array();

	public function __construct( $fnaam )
	{
		$xml = simpleXML_load_file( $fnaam, "SimpleXMLElement", LIBXML_NOCDATA );
		if ( $xml !== FALSE )
		{
			foreach ( $xml->route as $route )
			{
				$r = new AKVS_RouteBeschrijving( $route );
				$this->m_routes[ $r->slug() ] = $r;
			}
		}
	}

	public function route( $slug )
	{
		if ( array_key_exists( $slug, $this->m_routes ) )
		{
			return $this->m_routes[ $slug ];
		}
		
		return FALSE;
	}
}
