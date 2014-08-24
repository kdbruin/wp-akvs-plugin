<?php

class AKVS_SchemaElement
{

	protected function getNodeValue( $elt )
	{
		if ( $elt )
		{
			return $elt->attributes()->value;
		}
		else
		{
			return '';
		}
	}
}

class AKVS_Wedstrijd extends AKVS_SchemaElement
{
	protected $m_wsnum = '';
	protected $m_datum = '';
	protected $m_thuis = '';
	protected $m_uit = '';
	protected $m_atijd = '';
	protected $m_vtijd = '';
	protected $m_rijders = '';
	protected $m_scheidsrechter = '';
	protected $m_uitslag = '';
	protected $m_strafworpen = '';
	protected $m_lokatie = '';
	protected $m_status = '';

	public function __construct( $elt )
	{
		// print_r($elt);
		$this->m_status = ( string ) $elt->attributes()->status;
		$this->m_wsnum = ( int ) $this->getNodeValue( $elt->wsnum );
		$this->m_thuis = ( string ) $this->getNodeValue( $elt->thuis );
		$this->m_uit = ( string ) $this->getNodeValue( $elt->uit );
		$this->m_datum = ( string ) $this->getNodeValue( $elt->datum );
		$this->m_atijd = ( string ) $this->getNodeValue( $elt->aanvang );
		if ( $this->isThuis() )
		{
			$this->m_vtijd = ( string ) $this->getNodeValue( $elt->aanwezig );
			$this->m_scheidsrechter = ( string ) $this->getNodeValue( $elt->fluiten );
		}
		else
		{
			$this->m_vtijd = ( string ) $this->getNodeValue( $elt->vertrek );
			$this->m_rijders = ( string ) $this->getNodeValue( $elt->rijden );
		}
		$this->m_uitslag = ( string ) $this->getNodeValue( $elt->uitslag );
		$this->m_strafworpen = ( string ) $this->getNodeValue( $elt->strafworpen );
		$this->m_lokatie = ( string ) $this->getNodeValue( $elt->lokatie );
	}

	public function status()
	{
		return $this->m_status;
	}

	public function wsnum()
	{
		return $this->m_wsnum;
	}

	public function thuis()
	{
		return $this->m_thuis;
	}

	public function uit()
	{
		return $this->m_uit;
	}

	public function datum()
	{
		return $this->m_datum;
	}

	public function atijd()
	{
		return $this->m_atijd;
	}

	public function vtijd()
	{
		return $this->m_vtijd;
	}

	public function scheidsrechter()
	{
		return $this->m_scheidsrechter;
	}

	public function rijders()
	{
		return $this->m_rijders;
	}

	public function uitslag()
	{
		return $this->m_uitslag;
	}

	public function strafworpen()
	{
		return $this->m_strafworpen;
	}

	public function lokatie()
	{
		return $this->m_lokatie;
	}

	public function isThuis()
	{
		return preg_match( '/^soesterkw/i', $this->m_thuis );
	}

	public function isUit()
	{
		return preg_match( '/^soesterkw/i', $this->m_uit );
	}

	public function isWedstrijdSK()
	{
		return $this->isThuis() || $this->isUit();
	}

	public function isFutureDate( $date )
	{
		return $this->m_datum >= $date;
	}
}

function akvs_cmp_team( $t1, $t2 )
{
	return strcasecmp( $t1->naam(), $t2->naam() );
}

function akvs_cmp_wedstrijd_team( $w1, $w2 )
{
	$n1 = $w1->isThuis() ? $w1->thuis() : $w1->uit();
	$n2 = $w2->isThuis() ? $w2->thuis() : $w2->uit();
	$n1 = preg_replace( '/^soesterkwartier\s+/i', '', $n1 );
	$n2 = preg_replace( '/^soesterkwartier\s+/i', '', $n2 );
	return akvs_cmp_poule( $n1, $n2 );
}

class AKVS_Team extends AKVS_SchemaElement
{
	protected $m_naam = '';
	protected $m_plaats = '';
	protected $m_dag = '';
	protected $m_ondergrond = '';
	protected $m_index = 0;
	protected $m_strafpunten = '';

	public function __construct( $elt )
	{
		// print_r($elt);
		$this->m_naam = ( string ) $elt->attributes()->naam;
		$this->m_plaats = ( string ) $elt->attributes()->plaats;
		$this->m_dag = ( string ) $elt->attributes()->dag;
		$this->m_ondergrond = ( string ) $elt->attributes()->ondergrond;
		$this->m_strafpunten = ( int ) $elt->attributes()->strafpunten;
	}

	public function setIndex( $index )
	{
		$this->m_index = $index;
	}

	public function naam()
	{
		return $this->m_naam;
	}

	public function plaats()
	{
		return $this->m_plaats;
	}

	public function dag()
	{
		return $this->m_dag;
	}

	public function ondergrond()
	{
		return $this->m_ondergrond;
	}

	public function index()
	{
		return $this->m_index;
	}

	public function strafpunten()
	{
		return $this->m_strafpunten;
	}
}

class AKVS_Stand
{
	protected $m_team = '';
	protected $m_gespeeld = 0;
	protected $m_gewonnen = 0;
	protected $m_verloren = 0;
	protected $m_gelijk = 0;
	protected $m_voor = 0;
	protected $m_tegen = 0;
	protected $m_strafw_voor = 0;
	protected $m_strafw_tegen = 0;
	protected $m_punten = 0;

	public function __construct( $team )
	{
		$this->m_team = $team;
	}

	public function team()
	{
		return $this->m_team;
	}

	public function gespeeld()
	{
		return $this->m_gespeeld;
	}

	public function gewonnen()
	{
		return $this->m_gewonnen;
	}

	public function verloren()
	{
		return $this->m_verloren;
	}

	public function gelijk()
	{
		return $this->m_gelijk;
	}

	public function voor()
	{
		return $this->m_voor;
	}

	public function tegen()
	{
		return $this->m_tegen;
	}

	public function punten()
	{
		return $this->m_punten - $this->m_team->strafpunten();
	}

	protected function extractUitslag( $uitslag, $is_thuis )
	{
		$args = explode( '-', $uitslag );
		if ( $is_thuis == FALSE )
		{
			$tmp = $args[ 0 ];
			$args[ 0 ] = $args[ 1 ];
			$args[ 1 ] = $tmp;
		}
		
		return $args;
	}

	public function uitslagToevoegen( $uitslag, $strafw_uitslag, $is_thuis )
	{
		// wanneer de wedstrijd is afgelast, negeer de wedstrijd
		if ( $uitslag == 'afgelast' ) return;
		
		$args = $this->extractUitslag( $uitslag, $is_thuis );
		$voor = $args[ 0 ];
		$tegen = $args[ 1 ];
		
		$this->m_gespeeld++;
		$this->m_voor += $voor;
		$this->m_tegen += $tegen;
		if ( $voor > $tegen )
		{
			$this->m_gewonnen++;
			$this->m_punten += 2;
		}
		else if ( $voor < $tegen )
		{
			$this->m_verloren++;
		}
		else
		{
			$this->m_gelijk++;
			$this->m_punten++;
		}
		
		if ( $strafw_uitslag != '' )
		{
			$args = $this->extractUitslag( $strafw_uitslag, $is_thuis );
			$this->m_strafw_voor += $args[ 0 ];
			$this->m_strafw_tegen += $args[ 1 ];
		}
	}
}

function akvs_cmp_stand( $s1, $s2 )
{
	$tn1 = $s1->team()->naam();
	$pt1 = $s1->punten();
	$ws1 = $s1->gespeeld();
	$dv1 = $s1->voor();
	$dt1 = $s1->tegen();
	
	$tn2 = $s2->team()->naam();
	$pt2 = $s2->punten();
	$ws2 = $s2->gespeeld();
	$dv2 = $s2->voor();
	$dt2 = $s2->tegen();
	
	$res = 0;
	if ( $pt1 == $pt2 )
	{
		if ( $ws1 == $ws2 )
		{
			$tmp1 = $dv1 - $dt1;
			$tmp2 = $dv2 - $dt2;
			
			if ( $tmp1 == $tmp2 )
			{
				$res = strcasecmp( $tn1, $tn2 );
			}
			else
			{
				$res = ( $tmp1 > $tmp2 ) ? -1 : 1;
			}
		}
		else
		{
			$res = ( $ws1 < $ws2 ) ? -1 : 1;
		}
	}
	else
	{
		$res = ( $pt1 > $pt2 ) ? -1 : 1;
	}
	
	return $res;
}

class AKVS_Poule extends AKVS_SchemaElement
{
	protected $m_poule = '';
	protected $m_teams = array();
	protected $m_wedstrijden = array();
	protected $m_stand = array();
	protected $m_matrix = array();
	protected $m_wedstrByNumber = array();

	public function __construct( $elt )
	{
		// print_r($elt);
		$this->m_poule = ( string ) $elt->attributes()->value;
		
		foreach ( $elt->teams->team as $team )
		{
			$t = new AKVS_Team( $team );
			$this->m_teams[ $t->naam() ] = $t;
			$this->m_stand[ $t->naam() ] = new AKVS_Stand( $t );
		}
		
		// sorteer teams op naam
		ksort( $this->m_teams, SORT_STRING );
		
		// geef elk team een index
		$index = 1;
		$count = count( $this->m_teams );
		foreach ( array_keys( $this->m_teams ) as $naam )
		{
			$this->m_teams[ $naam ]->setIndex( $index );
			for ( $index2 = 1; $index2 <= $count; $index2++ )
			{
				$this->m_matrix[ $index ][ $index2 ] = array();
			}
			$index++;
		}
		
		foreach ( $elt->wedstrijden->wedstrijd as $wedstrijd )
		{
			$w = new AKVS_Wedstrijd( $wedstrijd );
			$this->m_wedstrijden[] = $w;
			$this->m_wedstrByNumber[ $w->wsnum() ] = $w;
			
			$uitslag = $w->uitslag();
			if ( $uitslag == '' || $uitslag == 'afgelast' ) continue;
			
			$thuis = $w->thuis();
			$uit = $w->uit();
			
			$this->m_stand[ $thuis ]->uitslagToevoegen( $uitslag, $w->strafworpen(), TRUE );
			$this->m_stand[ $uit ]->uitslagToevoegen( $uitslag, $w->strafworpen(), FALSE );
			
			$index_thuis = $this->m_teams[ $thuis ]->index();
			$index_uit = $this->m_teams[ $uit ]->index();
			$this->m_matrix[ $index_thuis ][ $index_uit ][] = $uitslag;
		}
		
		// sorteer de stand in de poule
		usort( $this->m_stand, "akvs_cmp_stand" );
	}

	public function poule()
	{
		return $this->m_poule;
	}

	public function teams()
	{
		return $this->m_teams;
	}

	public function matrix()
	{
		return $this->m_matrix;
	}

	public function stand()
	{
		return $this->m_stand;
	}

	public function teamSK()
	{
		foreach ( array_keys( $this->m_teams ) as $naam )
		{
			if ( preg_match( '/^soesterkw/i', $naam ) ) return $naam;
		}
		return '';
	}

	public function team( $index )
	{
		if ( $index < 1 || $index > count( $this->m_teams ) ) return '';
		
		foreach ( $this->m_teams as $naam => $team )
		{
			if ( $team->index() == $index ) return $team;
		}
	}

	public function teamOndergrond( $team )
	{
		return $this->m_teams[ $team ]->ondergrond();
	}

	public function teamPlaats( $team )
	{
		return $this->m_teams[ $team ]->plaats();
	}

	public function wedstrijd( $wsnum )
	{
		if ( array_key_exists( $this->m_wedstrByNumber, $wsnum ) )
		{
			return $this->m_wedstrByNumber[ $wsnum ];
		}
		
		return FALSE;
	}

	public function getTotaalSchema( $vanaf, &$schema, $alleenThuis = FALSE )
	{
		foreach ( $this->m_wedstrijden as $index => $wedstrijd )
		{
			$datum = $wedstrijd->datum();
			if ( $datum == '' || ( $vanaf != '' && $datum < $vanaf ) )
			{
				$uitslag = $wedstrijd->uitslag();
				if ( $uitslag != 'afgelast' ) continue;
			}
			if ( $alleenThuis && !$wedstrijd->isThuis() ) continue;
			
			$schema[ $datum ][] = $wedstrijd;
		}
	}

	public function getSchema( $vanaf )
	{
		$schema = array();
		foreach ( $this->m_wedstrijden as $index => $wedstrijd )
		{
			$datum = $wedstrijd->datum();
			if ( $datum == '' || ( $vanaf != '' && $datum < $vanaf ) )
			{
				$uitslag = $wedstrijd->uitslag();
				if ( $uitslag != 'afgelast' ) continue;
			}
			
			$schema[ $datum ][] = $wedstrijd;
		}
		
		// sorteer op datum
		ksort( $schema, SORT_STRING );
		return $schema;
	}

	public function getUitslagen( $vanaf, $tot, &$uitslagen )
	{
		foreach ( $this->m_wedstrijden as $index => $wedstrijd )
		{
			
			// check op wedstrijd van Soesterkwartier
			if ( !$wedstrijd->isWedstrijdSK() ) continue;
			
			// check uitslag
			$uitslag = $wedstrijd->uitslag();
			if ( $uitslag == '' ) continue;
			
			// check datum
			$datum = $wedstrijd->datum();
			if ( $datum == '' ) continue;
			if ( $vanaf != '' && $datum < $vanaf ) continue;
			if ( $tot != '' && $datum >= $tot ) continue;
			
			$uitslagen[ $datum ][] = $wedstrijd;
		}
	}
}

function akvs_make_tijd( $tijd )
{
	$args = explode( ':', $tijd );
	return 60 * ( int ) $args[ 0 ] + ( int ) $args[ 1 ];
}

function akvs_cmp_aanvang( $w1, $w2 )
{
	$at1 = akvs_make_tijd( $w1->atijd() );
	$tn1 = $w1->thuis();
	
	$at2 = akvs_make_tijd( $w2->atijd() );
	$tn2 = $w2->thuis();
	
	$res = 0;
	if ( $at1 == $at2 )
	{
		$res = strcasecmp( $tn1, $tn2 );
	}
	else
	{
		$res = ( $at1 < $at2 ? -1 : 1 );
	}
	
	return $res;
}

function akvs_cmp_poule( $p1, $p2 )
{
	$pp1 = $p1[ 0 ];
	$pp2 = $p2[ 0 ];
	
	if ( $pp1 == $pp2 )
	{
		// zelfde poule
		$res = strcasecmp( $p1, $p2 );
	}
	else if ( preg_match( '/[a-f]/i', $pp1 ) )
	{
		// jeugd poule tegen
		if ( preg_match( '/[a-f]/i', $pp2 ) )
		{
			// jeugd poule
			$res = strcasecmp( $p1, $p2 );
		}
		else
		{
			// senioren of midweek poule
			$res = 1;
		}
	}
	else if ( $pp1 == 'm' || $pp1 == 'M' )
	{
		// midweek poule tegen
		if ( preg_match( '/[a-f]/i', $pp2 ) )
		{
			// jeugd poule
			$res = -1;
		}
		else if ( preg_match( '/[0-9r]/i', $pp2 ) )
		{
			// senioren poule
			$res = 1;
		}
		else
		{
			$res = strcasecmp( $p1, $p2 );
		}
	}
	else if ( preg_match( '/[0-9r]/i', $pp1 ) )
	{
		// senioren poule tegen
		if ( preg_match( '/[a-fm]/i', $pp2 ) )
		{
			// jeugd of midweek poule
			$res = -1;
		}
		else
		{
			$res = strcasecmp( $p1, $p2 );
		}
	}
	else
	{
		$res = 0;
	}
	
	return $res;
}

class AKVS_Competitie extends AKVS_SchemaElement
{
	protected $m_poules = array();
	protected $m_naam = '';

	public function __construct( $fname )
	{
		$xml = simpleXML_load_file( $fname, "SimpleXMLElement", LIBXML_NOCDATA );
		if ( $xml !== FALSE )
		{
			$this->m_naam = ( string ) $xml->attributes()->naam;
			
			foreach ( $xml->poule as $poule )
			{
				$p = new AKVS_Poule( $poule );
				$this->m_poules[ $p->poule() ] = $p;
			}
		}
	}

	public function naam()
	{
		return $this->m_naam;
	}

	public function poules()
	{
		return array_keys( $this->m_poules );
	}

	public function poule( $poule )
	{
		return $this->m_poules[ $poule ];
	}

	public function teamSK( $poule )
	{
		return $this->m_poules[ $poule ]->teamSK();
	}

	public function getIndeling()
	{
		$indeling = array();
		foreach ( $this->m_poules as $naam => $poule )
		{
			$indeling[ $naam ] = $poule->teams();
		}
		uksort( $indeling, "akvs_cmp_poule" );
		
		return $indeling;
	}

	public function getTeams( $poule )
	{
		return $this->m_poules[ $poule ]->teams();
	}

	public function getTeamOndergrond( $poule, $team )
	{
		return $this->m_poules[ $poule ]->teamOndergrond( $team );
	}

	public function getThuisSchema( $vanaf = '' )
	{
		$schema = array();
		foreach ( $this->m_poules as $naam => $poule )
		{
			$poule->getTotaalSchema( $vanaf, $schema, TRUE );
		}
		
		// sorteer op datum
		ksort( $schema, SORT_STRING );
		foreach ( array_keys( $schema ) as $datum )
		{
			// sorteer per datum op aanvangstijd
			usort( $schema[ $datum ], "akvs_cmp_aanvang" );
		}
		
		return $schema;
	}

	public function getTotaalSchema( $vanaf = '' )
	{
		$schema = array();
		foreach ( $this->m_poules as $naam => $poule )
		{
			$poule->getTotaalSchema( $vanaf, $schema );
		}
		
		// sorteer op datum
		ksort( $schema, SORT_STRING );
		foreach ( array_keys( $schema ) as $datum )
		{
			// sorteer per datum op aanvangstijd
			usort( $schema[ $datum ], "akvs_cmp_aanvang" );
		}
		
		return $schema;
	}

	public function getEindStanden()
	{
		$standen = array();
		foreach ( $this->m_poules as $naam => $poule )
		{
			$standen[ $naam ] = $poule->stand();
		}
		
		uksort( $standen, "akvs_cmp_poule" );
		return $standen;
	}

	public function getSchema( $poule, $vanaf = '' )
	{
		return $this->m_poules[ $poule ]->getSchema( $vanaf );
	}

	public function getStand( $poule )
	{
		return $this->m_poules[ $poule ]->stand();
	}

	public function getMatrix( $poule )
	{
		return $this->m_poules[ $poule ]->matrix();
	}

	public function getTeam( $poule, $index )
	{
		return $this->m_poules[ $poule ]->team( $index );
	}

	public function getWedstrijd( $poule, $wsnum )
	{
		return $this->m_poules[ $poule ]->wedstrijd( $wsnum );
	}

	public function getUitslagen( $vanaf, $tot )
	{
		$uitslagen = array();
		
		foreach ( $this->m_poules as $naam => $poule )
		{
			$poule->getUitslagen( $vanaf, $tot, $uitslagen );
		}
		
		// sorteer op datum
		ksort( $uitslagen, SORT_STRING );
		foreach ( array_keys( $uitslagen ) as $datum )
		{
			// sorteer per datum op team
			usort( $uitslagen[ $datum ], "akvs_cmp_wedstrijd_team" );
		}
		
		return $uitslagen;
	}
}
