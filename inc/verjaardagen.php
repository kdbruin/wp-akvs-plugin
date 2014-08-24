<?php

class AKVS_Verjaardag
{
	protected $m_skip = '';
	protected $m_naam = '';
	protected $m_geb_jaar = '';
	protected $m_geb_maand = '';
	protected $m_geb_dag = '';
	protected $m_datum = '';
	protected $m_dit_jaar = '';
	protected $m_volgend_jaar = '';

	protected function updateJaar( $checkMaand )
	{
		list( $jaar, $maand, $dag ) = explode( '-', date( 'Y-m-d' ) );
		$jaar = ( int ) $jaar;
		
		if ( $checkMaand )
		{
			$jaar++;
		}
		
		return $jaar . "-" . $this->m_geb_maand . "-" . $this->m_geb_dag;
	}

	public function __construct( $elt )
	{
		// print_r($elt);
		$this->m_skip = ( string ) $elt->attributes()->skip;
		$this->m_naam = ( string ) $elt->attributes()->naam;
		$this->m_datum = ( string ) $elt->attributes()->datum;
		
		list( $this->m_geb_jaar, $this->m_geb_maand, $this->m_geb_dag ) = explode( '-', $this->m_datum );
		
		$this->m_dit_jaar = $this->updateJaar( FALSE );
		$this->m_volgend_jaar = $this->updateJaar( TRUE );
	}

	public function skip()
	{
		return $this->m_skip;
	}

	public function naam()
	{
		return $this->m_naam;
	}

	public function datum()
	{
		return $this->m_datum;
	}

	public function dit_jaar()
	{
		return $this->m_dit_jaar;
	}

	public function volgend_jaar()
	{
		return $this->m_volgend_jaar;
	}

	public function geb_jaar()
	{
		return ( int ) $this->m_geb_jaar;
	}

	public function geb_maand()
	{
		return ( int ) $this->m_geb_maand;
	}

	public function geb_dag()
	{
		return ( int ) $this->m_geb_dag;
	}
}

class AKVS_Verjaardagen
{
	protected $m_data = array();

	public function __construct( $fnaam )
	{
		$xml = simpleXML_load_file( $fnaam, "SimpleXMLElement", LIBXML_NOCDATA );
		if ( $xml === FALSE )
		{
			exit( 'Er gaat hier iets niet helemaal goed\n' );
		}
		
		foreach ( $xml->verjaardag as $verjaardag )
		{
			$v = new AKVS_Verjaardag( $verjaardag );
			$this->m_data[ $v->dit_jaar() ][] = $v;
			$this->m_data[ $v->volgend_jaar() ][] = $v;
		}
		
		ksort( $this->m_data );
	}

	public function jarigen()
	{
		// alle jarigen van 1 week terug tot 1 week verder
		$begin = strftime( '%Y-%m-%d', strtotime( "-6 day" ) );
		$einde = strftime( '%Y-%m-%d', strtotime( "+1 week" ) );
		
		$result = array();
		foreach ( $this->m_data as $datum => $verjaardagen )
		{
			if ( $datum >= $begin && $datum < $einde )
			{
				foreach ( $verjaardagen as $verjaardag )
				{
					if ( !$verjaardag->skip() ) $result[] = $verjaardag;
				}
			}
		}
		
		return $result;
	}
}
