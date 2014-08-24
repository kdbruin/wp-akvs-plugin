<?php
class AKVS_Activiteit
{
	protected $m_naam = '';
	protected $m_datum = '';
	protected $m_postid = '';

	public function __construct( $elt )
	{
		$this->m_naam = ( string ) $elt->attributes()->naam;
		$this->m_datum = ( string ) $elt->attributes()->datum;
		$this->m_postid = ( string ) $elt->attributes()->id;
	}

	public function naam()
	{
		return $this->m_naam;
	}

	public function datum()
	{
		return $this->m_datum;
	}

	public function postid()
	{
		return $this->m_postid;
	}
}
class AKVS_Kalender
{
	protected $m_data = array();

	public function __construct( $fnaam )
	{
		$xml = simpleXML_load_file( $fnaam, "SimpleXMLElement", LIBXML_NOCDATA );
		if ( $xml !== FALSE )
		{
			foreach ( $xml->activiteit as $activiteit )
			{
				$v = new AKVS_Activiteit( $activiteit );
				$this->m_data[ $v->datum() ][] = $v;
			}
			
			ksort( $this->m_data );
		}
	}

	public function activiteiten( $vanaf = '', $tot = '' )
	{
		// alle komende activiteiten
		if ( empty( $vanaf ) ) $vanaf = date( 'Y-m-d' );
		if ( empty( $tot ) ) $tot = '9999-12-31';
		
		$result = array();
		foreach ( $this->m_data as $datum => $activiteiten )
		{
			if ( $datum >= $vanaf && $datum < $tot )
			{
				foreach ( $activiteiten as $activiteit )
				{
					$result[] = $activiteit;
				}
			}
		}
		
		return $result;
	}
}
