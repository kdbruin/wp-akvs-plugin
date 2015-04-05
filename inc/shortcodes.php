<?php

/**
 * WordPress shortcodes voor AKV Soesterkwartier
 *
 * @package AKVS-Plugin
 * @author Kees de Bruin
 */

/**
 * Email adres met icon
 *
 * adres:	e-mail adres
 */
function akvs_email_shortcode( $atts, $content = '' )
{
	extract( shortcode_atts( array( 
		'adres' => '' 
	), $atts ) );
	
	if ( $adres == '' ) return $content;
	if ( $content == '' ) $content = $adres;
	
	return '<a href="mailto:' . $adres . '" class="email">' . $content . '</a>';
}
add_shortcode( 'akvs_email', 'akvs_email_shortcode' );

/**
 * Telefoonnummer met icon
 *
 * nummer:	telefoonnummer
 */
function akvs_telefoon_shortcode( $atts, $content = '' )
{
	extract( shortcode_atts( array( 
		'nummer' => '' 
	), $atts ) );
	
	return '<span class="telefoon">' . $nummer . '</span>';
}
add_shortcode( 'akvs_telefoon', 'akvs_telefoon_shortcode' );

/**
 * Lokatie URL
 *
 * lokatie:	naam van de lokatie zoals die
 * in route.xml staat
 */
function akvs_lokatie_shortcode( $atts, $content = '' )
{
	extract( shortcode_atts( array( 
		'lokatie' => '' 
	), $atts ) );
	
	return akvs_get_lokatie_url( $lokatie, FALSE );
}
add_shortcode( 'akvs_lokatie', 'akvs_lokatie_shortcode' );

/**
 * Overzicht van een enkele wedstrijd
 *
 * xml:	naam van de XML file met het competitieoverzicht
 * poule:	poule voor de indeling
 * wsnum:	wedstrijdnummer
 */
function akvs_wedstrijd_shortcode( $atts, $content = '' )
{
	extract( shortcode_atts( array( 
		'xml' => '', 
		'poule' => '', 
		'wsnum' => '' 
	), $atts ) );
	
	if ( $xml == '' || $poule == '' || $wsnum == '' ) return '';
	
	$competitie = new AKVS_Competitie( akvs_get_xml_filename( $xml ) );
	$wedstrijd = $competitie->getWedstrijd( $poule, $wsnum );
	
	if ( $wedstrijd === FALSE )
	{
		return '';
	}
	
	$schema[ $wedstrijd->datum() ][] = $wedstrijd;
	$result = akvs_format_poulewedstrijden( $schema, $poule );
	
	return $result;
}

add_shortcode( 'akvs_wedstrijd', 'akvs_wedstrijd_shortcode' );

/**
 * Overzicht van uitslagen in een periode
 *
 * xml:	naam van de XML file met het competitieoverzicht
 * vanaf:	vanaf datum (optioneel, 1 week terug)
 * tot:	tot datum (optioneel, vandaag)
 *
 * Een van de 2 data moeten ingevuld zijn!
 */
function akvs_uitslagen_shortcode( $atts, $content = '' )
{
	extract( shortcode_atts( array( 
		'xml' => '', 
		'vanaf' => '', 
		'tot' => '' 
	), $atts ) );
	
	if ( $xml == '' ) return '';
	if ( $vanaf == '' && $tot == '' ) return '';
	
	if ( $vanaf == '' )
	{
		// 1 week voor de aangegeven datum
		$vanaf = date( 'Y-m-d', strtotime( "-1 week", strtotime( $tot ) ) );
		$tot = akvs_format_iso( $tot );
	}
	if ( $tot == '' )
	{
		// 1 week vanf de aangegeven datum
		$tot = date( 'Y-m-d', strtotime( "+1 week", strtotime( $vanaf ) ) );
		$vanaf = akvs_format_iso( $vanaf );
	}
	
	$competitie = new AKVS_Competitie( akvs_get_xml_filename( $xml ) );
	$uitslagen = $competitie->getUitslagen( $vanaf, $tot );
	
	if ( count( $uitslagen ) == 0 )
	{
		return '</p>Er zijn geen uitslagen voor deze week.</p>';
	}
	
	$result = akvs_table( 'akvs-uitslagen' );
	$result .= akvs_thead( array( 
		'datum', 
		'wedstrijd@3', 
		'uitslag', 
		'strafw' 
	) );
	$result .= akvs_tbody();
	
	$count = 0;
	foreach ( $uitslagen as $datum => $wedstrijden )
	{
		foreach ( $wedstrijden as $wedstrijd )
		{
			$count++;
			$result .= akvs_tr( $count );
			$result .= akvs_td( akvs_format_ddm( $datum ), 'datum' );
			$result .= akvs_td( akvs_get_team_url( $wedstrijd->thuis() ), 'ploeg-thuis' );
			$result .= akvs_td( '-', 'dash' );
			$result .= akvs_td( akvs_get_team_url( $wedstrijd->uit() ), 'ploeg-uit' );
			$result .= akvs_td( akvs_format_uitslag( $wedstrijd->uitslag() ), 'uitslag' );
			$result .= akvs_td( akvs_format_uitslag( $wedstrijd->strafworpen() ), 'uitslag' );
			$result .= '</tr>';
		}
	}
	
	$result .= '</tbody></table>';
	
	return $result;
}

add_shortcode( 'akvs_uitslagen', 'akvs_uitslagen_shortcode' );

/**
 * Overzicht van alle wedstrijden
 *
 * xml: naam van de XML file met het competitieoverzicht
 */
function akvs_afgelast_class( $status, $class )
{
	if ( $status == 'afgelast' ) $class = $class . ' afgelast';
	return $class;
}

function akvs_wedstrijdoverzicht_shortcode( $atts, $content = '' )
{
	extract( shortcode_atts( array( 
		'xml' => '' 
	), $atts ) );
	
	if ( $xml == '' ) return '';
	
	$competitie = new AKVS_Competitie( akvs_get_xml_filename( $xml ) );
	
	$vanaf = date( 'Y-m-d' );
	$schema = $competitie->getTotaalSchema( $vanaf );
	if ( count( $schema ) == 0 )
	{
		return '<p>Er staan geen wedstrijden meer op het programma.</p>';
	}
	
	$result = do_shortcode( '[akvs_ws]' );
	$result .= '<br /><br />';
	foreach ( $schema as $datum => $wedstrijden )
	{
		$result .= '<h4>' . date_i18n( 'l j F', strtotime( $datum ) ) . '</h4>';
		$result .= akvs_table( 'akvs-overzicht' );
		$result .= akvs_thead( array( 
			'aanvang', 
			'wedstrijd@3', 
			'wsnum', 
			'fluiten' 
		) );
		$result .= akvs_tbody();
		
		$count = 0;
		foreach ( $wedstrijden as $wedstrijd )
		{
			$count++;
			$result .= akvs_tr( $count );
			$status = $wedstrijd->status();
			$result .= akvs_td( $wedstrijd->atijd(), akvs_afgelast_class( $status, 'tijd' ) );
			$result .= akvs_td( akvs_get_team_url( $wedstrijd->thuis() ), akvs_afgelast_class( $status, 'ploeg-thuis' ) );
			$result .= akvs_td( '-', 'dash' );
			$result .= akvs_td( akvs_get_team_url( $wedstrijd->uit() ), akvs_afgelast_class( $status, 'ploeg-uit' ) );
			$result .= akvs_td( $wedstrijd->wsnum(), akvs_afgelast_class( $status, 'wsnum' ) );
			if ( $wedstrijd->isThuis() )
			{
				$result .= akvs_td( $wedstrijd->scheidsrechter(), akvs_afgelast_class( $status, 'scheidsrechter' ) );
			}
			else
			{
				$result .= akvs_td( '', 'empty' );
			}
			$result .= '</tr>';
		}
		
		$result .= '</tbody></table>';
	}
	
	return $result;
}

add_shortcode( 'akvs_wedstrijdoverzicht', 'akvs_wedstrijdoverzicht_shortcode' );

/**
 * Indeling voor de gehele competitie
 *
 * xml:	naam XML file met competitieoverzicht
 */
function akvs_competitie_indeling_shortcode( $atts, $content = '' )
{
	extract( shortcode_atts( array( 
		'xml' => '' 
	), $atts ) );
	
	if ( $xml == '' ) return '';
	
	$competitie = new AKVS_Competitie( akvs_get_xml_filename( $xml ) );
	$indeling = $competitie->getIndeling();
	
	if ( count( $indeling ) == 0 )
	{
		return '<p>Er is nog geen teamindeling bekend.</p>';
	}
	
	$result = '';
	$count = 0;
	foreach ( $indeling as $poule => $teams )
	{
		$count++;
		$result .= '<div class="one_half';
		if ( $count % 2 == 0 ) $result .= ' last';
		$result .= '">';
		$result .= '<h4>' . $competitie->teamSK( $poule ) . ' - ' . $poule . '</h4>';
		$result .= akvs_table( 'akvs-indeling' );
		$result .= akvs_thead( array( 
			'ploeg@2', 
			'plaats' 
		) );
		$result .= akvs_tbody();
		
		$teamIndex = 0;
		foreach ( $teams as $naam => $team )
		{
			$teamIndex++;
			
			$dag = $team->dag();
			if ( !empty( $dag ) )
			{
				$dag = ' (' . $dag . ')';
			}
			
			$result .= akvs_tr( $teamIndex );
			$result .= akvs_td( $team->index() );
			$result .= akvs_td( $naam . $dag, 'ploeg' );
			$result .= akvs_td( $team->plaats(), 'plaats' );
			$result .= '</tr>';
		}
		
		$result .= '</tbody></table>';
		$result .= '</div>';
	}
	
	$result .= '<div class="clear"></div>';
	
	return $result;
}

add_shortcode( 'akvs_competitie_indeling', 'akvs_competitie_indeling_shortcode' );

/**
 * Indeling voor een specifieke poule
 *
 * xml:	naam XML file met het competitieoverzicht
 * poule:	poule voor de indeling
 */
function akvs_format_pouleindeling( $teams )
{
	$result = akvs_table( 'akvs-indeling' );
	$result .= akvs_thead( array( 
		'ploeg@2', 
		'plaats' 
	) );
	$result .= akvs_tbody();
	
	$teamIndex = 0;
	foreach ( $teams as $team )
	{
		$teamIndex++;
		$result .= akvs_tr( $teamIndex );
		$result .= akvs_td( $team->index() );
		$result .= akvs_td( $team->naam(), 'ploeg' );
		$result .= akvs_td( $team->plaats(), 'plaats' );
		$result .= '</tr>';
	}
	
	$result .= '</tbody></table>';
	
	return $result;
}

function akvs_pouleindeling_shortcode( $atts, $content = '' )
{
	extract( shortcode_atts( array( 
		'xml' => '', 
		'poule' => '' 
	), $atts ) );
	
	if ( $xml == '' || $poule == '' ) return '';
	
	$competitie = new AKVS_Competitie( akvs_get_xml_filename( $xml ) );
	$teams = $competitie->getTeams( $poule );
	
	if ( count( $teams ) == 0 )
	{
		return '<p>Er is nog geen teamindeling bekend voor ' . the_title() . '</p>';
	}
	
	return akvs_format_pouleindeling( $teams );
}

add_shortcode( 'akvs_pouleindeling', 'akvs_pouleindeling_shortcode' );

/**
 * Wedstrijden voor een specifieke poule
 *
 * xml:	naam XML file met het competitieoverzicht
 * poule:	poule voor de wedstrijden
 */
function akvs_format_poulewedstrijden( $schema, $poule, $showDetails = TRUE )
{
	$result = akvs_table( 'akvs-wedstrijden' );
	$result .= akvs_thead( array( 
		'datum', 
		'aanvang', 
		'wedstrijd@3', 
		'wsnum' 
	) );
	$result .= akvs_tbody();
	
	foreach ( $schema as $datum => $wedstrijden )
	{
		$wIndex = 0;
		foreach ( $wedstrijden as $wedstrijd )
		{
			$wIndex++;
			
			// generieke informatie over een wedstrijd
			$result .= akvs_tr( $wIndex );
			$status = $wedstrijd->status();
			$result .= akvs_td( akvs_format_ddm( $datum ), akvs_afgelast_class( $status, 'datum' ) );
			$result .= akvs_td( $wedstrijd->atijd(), akvs_afgelast_class( $status, 'tijd' ) );
			$result .= akvs_td( $wedstrijd->thuis(), akvs_afgelast_class( $status, 'ploeg-thuis' ) );
			$result .= akvs_td( '-', 'dash' );
			$result .= akvs_td( $wedstrijd->uit(), akvs_afgelast_class( $status, 'ploeg-uit' ) );
			$result .= akvs_td( $wedstrijd->wsnum(), akvs_afgelast_class( $status, 'wsnum' ) );
			$result .= '</tr>';
			
			// specifieke informatie over een wedstrijd
			if ( empty( $status ) && ( $showDetails == TRUE ) )
			{
				$isThuis = $wedstrijd->isThuis();
				$result .= akvs_tr( $wIndex );
				$result .= '<td></td>';
				$result .= '<td colspan="5">';
				$result .= 'Plaats: ' . $poule->teamPlaats( $wedstrijd->thuis() ) . ', ' . akvs_get_lokatie_url( $wedstrijd->lokatie(), $isThuis ) . '<br />';
				$ondergrond = $poule->teamOndergrond( $wedstrijd->thuis() );
				if ( $ondergrond != '' )
				{
					$result .= 'Ondergrond: ' . $ondergrond . '<br />';
				}
				if ( $isThuis )
				{
					$result .= 'Aanwezig: ' . $wedstrijd->vtijd() . '<br />';
					$result .= 'Fluiten: ' . $wedstrijd->scheidsrechter();
				}
				else
				{
					$result .= 'Vertrek: ' . $wedstrijd->vtijd() . '<br />';
					$result .= 'Rijders: ' . $wedstrijd->rijders();
				}
				$result .= '</td>';
				$result .= '</tr>';
			}
		}
	}
	
	$result .= '</tbody></table>';
	
	return $result;
}

function akvs_poulewedstrijden_shortcode( $atts, $content = '' )
{
	extract( shortcode_atts( array( 
		'xml' => '', 
		'poule' => '' 
	), $atts ) );
	
	if ( $xml == '' || $poule == '' ) return '';
	
	$competitie = new AKVS_Competitie( akvs_get_xml_filename( $xml ) );
	$vanaf = date( 'Y-m-d' );
	$schema = $competitie->getSchema( $poule, $vanaf );
	
	if ( count( $schema ) == 0 )
	{
		return '<p>Er zijn geen wedstrijden meer voor ' . the_title() . '</p>';
	}
	
	return akvs_format_poulewedstrijden( $schema, $competitie->poule( $poule ) );
}

add_shortcode( 'akvs_poulewedstrijden', 'akvs_poulewedstrijden_shortcode' );

/**
 * Stand voor een specifieke poule
 *
 * xml:	naam XML file met het competitieoverzicht
 * poule:	poule voor de stand
 */
function akvs_format_poulestand( $stand )
{
	$result = akvs_table( 'akvs-stand' );
	$result .= akvs_thead( array( 
		'ploeg@2', 
		'ws', 
		'w', 
		'g', 
		'v', 
		'pu', 
		'dv', 
		'dt' 
	) );
	$result .= akvs_tbody();
	
	$index = 0;
	foreach ( $stand as $std )
	{
		$index++;
		$result .= akvs_tr( $index );
		$result .= akvs_td( $index );
		$result .= akvs_td( $std->team()->naam(), 'ploeg' );
		$result .= akvs_td( $std->gespeeld(), 'stand' );
		$result .= akvs_td( $std->gewonnen(), 'stand' );
		$result .= akvs_td( $std->gelijk(), 'stand' );
		$result .= akvs_td( $std->verloren(), 'stand' );
		$result .= akvs_td( $std->punten(), 'stand' );
		$result .= akvs_td( $std->voor(), 'stand' );
		$result .= akvs_td( $std->tegen(), 'stand' );
		$result .= '</tr>';
	}
	
	$result .= '</tbody></table>';
	
	return $result;
}

function akvs_poulestand_shortcode( $atts, $content = '' )
{
	extract( shortcode_atts( array( 
		'xml' => '', 
		'poule' => '' 
	), $atts ) );
	
	if ( $xml == '' || $poule == '' ) return '';
	
	$competitie = new AKVS_Competitie( akvs_get_xml_filename( $xml ) );
	$stand = $competitie->getStand( $poule );
	
	if ( count( $stand ) == 0 )
	{
		return '<p>Er zijn geen uitslagen bekend voor ' . the_title() . '</p>';
	}
	
	return akvs_format_poulestand( $stand );
}

add_shortcode( 'akvs_poulestand', 'akvs_poulestand_shortcode' );

/**
 * Eindstanden voor een competitie
 *
 * xml:	naam XML file met het competitieoverzicht
 */
function akvs_eindstanden_shortcode( $atts, $content = '' )
{
	extract( shortcode_atts( array( 
		'xml' => '' 
	), $atts ) );
	
	if ( $xml == '' ) return '';
	
	$competitie = new AKVS_Competitie( akvs_get_xml_filename( $xml ) );
	$standen = $competitie->getEindStanden();
	
	if ( count( $standen ) == 0 )
	{
		return '<p>Er zijn geen uitslagen bekend voor ' . $competitie->naam() . '</p>';
	}
	
	$result = '';
	foreach ( $standen as $poule => $stand )
	{
		$result .= akvs_start_end_element( 'h4', $competitie->teamSK( $poule ) . ' (' . $poule . ')' );
		$result .= akvs_format_poulestand( $stand );
	}
	
	return $result;
}

add_shortcode( 'akvs_eindstanden', 'akvs_eindstanden_shortcode' );

/**
 * Uitslagen matrix voor een specifieke poule
 *
 * xml:	naam XML file met het competitieoverzicht
 * poule:	poule voor de uitslagen matrix
 */
function akvs_format_pouleuitslagen( $matrix, $competitie, $poule )
{
	$teamCount = count( $matrix );
	
	$result = akvs_table( 'akvs-uitslagen' );
	$result .= '<thead><tr>';
	$result .= '<th colspan="2">ploeg</th>';
	for ( $index1 = 1; $index1 <= $teamCount; $index1++ )
	{
		$result .= '<th class="ploeg-index">' . $index1 . '</th>';
	}
	$result .= '</tr></thead>';
	$result .= akvs_tbody();
	
	for ( $index1 = 1; $index1 <= $teamCount; $index1++ )
	{
		$result .= akvs_tr( $index1 );
		$result .= akvs_td( $index1, 'index' );
		$result .= akvs_td( $competitie->getTeam( $poule, $index1 )->naam(), 'ploeg' );
		
		for ( $index2 = 1; $index2 <= $teamCount; $index2++ )
		{
			if ( $index1 == $index2 )
			{
				$result .= akvs_td( '', 'empty' );
			}
			else
			{
				$result .= akvs_td( akvs_format_uitslag( $matrix[ $index1 ][ $index2 ] ), 'uitslag' );
			}
		}
	}
	
	$result .= '</tbody></table>';
	
	return $result;
}

function akvs_pouleuitslagen_shortcode( $atts, $content = '' )
{
	extract( shortcode_atts( array( 
		'xml' => '', 
		'poule' => '' 
	), $atts ) );
	
	if ( $xml == '' || $poule == '' ) return '';
	
	$competitie = new AKVS_Competitie( akvs_get_xml_filename( $xml ) );
	$matrix = $competitie->getMatrix( $poule );
	
	if ( count( $matrix ) == 0 )
	{
		return '<p>Er zijn geen uitslagen bekend voor ' . the_title() . '</p>';
	}
	
	return akvs_format_pouleuitslagen( $matrix, $competitie, $poule );
}

add_shortcode( 'akvs_pouleuitslagen', 'akvs_pouleuitslagen_shortcode' );

/**
 * Compleet overzicht van wedstrijden, standen, uitslagen en indeling voor een specifieke poule
 *
 * xml:	naam XML file met het competitieoverzicht
 * poule:	poule voor het overzicht
 */
function akvs_pouleoverzicht_shortcode( $atts, $content = '' )
{
	extract( shortcode_atts( array( 
		'xml' => '', 
		'poule' => '', 
		'foto' => '', 
		'spelers' => '', 
		'coach' => '' 
	), $atts ) );
	
	if ( $xml == '' || $poule == '' ) return '';
	
	$competitie = new AKVS_Competitie( akvs_get_xml_filename( $xml ) );
	$vanaf = date( 'Y-m-d' );
	
	// Afschrijven en inleveren wedstrijdformulieren
	$result = do_shortcode( '[akvs_ws]' );
	
	// wedstrijden
	$result .= do_shortcode( '[tabby title="Wedstrijden"]' );
	$schema = $competitie->getSchema( $poule, $vanaf );
	$result .= akvs_format_poulewedstrijden( $schema, $competitie->poule( $poule ) );
	
	// stand
	$result .= do_shortcode( '[tabby title="Stand"]' );
	$stand = $competitie->getStand( $poule );
	$result .= akvs_format_poulestand( $stand );
	
	// uitslagen
	$result .= do_shortcode( '[tabby title="Uitslagen"]' );
	$matrix = $competitie->getMatrix( $poule );
	$result .= akvs_format_pouleuitslagen( $matrix, $competitie, $poule );
	
	// indeling
	$result .= do_shortcode( '[tabby title="Indeling"]' );
	$teams = $competitie->getTeams( $poule );
	$result .= akvs_format_pouleindeling( $teams );
	
	// team info
	$result .= do_shortcode( '[tabby title="Team Info"]' );
	$result .= '<p class="akvs-teaminfo">';
	if ( empty( $foto ) )
	{
		$result .= 'Er is nog geen teamfoto beschikbaar.';
	}
	else
	{
		$result .= do_shortcode( '[shashin type="photo" id="' . $foto . '" size="large"]' );
	}
	$result .= '</p><p class="akvs-teaminfo">';
	$result .= 'Spelers: ' . $spelers . '<br />' . 'Trainer/coach: ' . $coach;
	$result .= '</p>';
	
	// einde tab content
	$result .= do_shortcode( '[tabbyending]' );
	
	return $result;
}

add_shortcode( 'akvs_pouleoverzicht', 'akvs_pouleoverzicht_shortcode' );

/**
 * Toon de activiteiten kalender
 *
 * xml:	naam van het XML bestand met de activiteiten
 * vanaf:	start datum (optioneel, default is vandaag)
 * tot:	eind datum (optioneel, default is einde der tijden)
 * aantal:	laat 'aantal' activiteiten zien (voor widget)
 */
function akvs_kalender_shortcode( $atts, $content = '' )
{
	extract( shortcode_atts( array( 
		'xml' => 'kalender.xml', 
		'vanaf' => '', 
		'tot' => '', 
		'aantal' => '0' 
	), $atts ) );
	
	$kalender = new AKVS_Kalender( akvs_get_xml_filename( $xml ) );
	$activiteiten = $kalender->activiteiten( $vanaf, $tot );
	
	if ( count( $activiteiten ) == 0 )
	{
		$result = '<p>Er staan geen activiteiten meer op de kalender</p>';
	}
	else
	{
		$result = '';
		$fdatum = '';
		$ldatum = '';
		
		if ( $aantal != 0 )
		{
			$result .= '<ul>';
		}
		
		$count = 0;
		foreach ( $activiteiten as $activiteit )
		{
			$count++;
			if ( $aantal > 0 && $aantal < $count ) break;
			
			$datum = $activiteit->datum();
			if ( $aantal == 0 )
			{
				// toon maanden alleen bij totaal overzicht, niet in de widget
				$fdatum = akvs_extract_mj( $datum );
				if ( $fdatum != $ldatum )
				{
					if ( $ldatum != '' ) $result .= '</ul>';
					$result .= '<h4>' . akvs_format_mj( $datum ) . '</h4>';
					$result .= '<ul>';
					
					$ldatum = $fdatum;
				}
			}
			
			$naam = $activiteit->naam();
			$postid = $activiteit->postid();
			
			$result .= '<li>' . akvs_format_dm( $datum ) . ': ';
			if ( empty( $postid ) )
			{
				$result .= $naam;
			}
			else
			{
				$result .= '<a href="' . get_permalink( $postid ) . '">' . $naam . '</a>';
			}
			$result .= '</li>';
		}
		
		$result .= '</ul>';
	}
	
	return $result;
}

add_shortcode( 'akvs_kalender', 'akvs_kalender_shortcode' );

/**
 * Toon alle sponsoren
 *
 * xml:	naam van het XML bestand met alle sponsors (optioneel, default is 'sponsors.xml')
 */
function akvs_sponsors_shortcode( $atts, $content = '' )
{
	extract( shortcode_atts( array( 
		'xml' => 'sponsors.xml' 
	), $atts ) );
	
	$sponsors = new AKVS_Sponsors( akvs_get_xml_filename( $xml ) );
	$overzicht = $sponsors->sponsors();
	
	if ( count( $overzicht ) == 0 ) return '';
	
	$imgdir = get_stylesheet_directory_uri() . '/images/sponsors';
	$result = '<ul class="sponsors">';
	foreach ( $overzicht as $sponsor )
	{
		$naam = $sponsor->naam();
		$image = $sponsor->image();
		$url = $sponsor->url();
		
		$result .= '<li>';
		if ( $url != '' )
		{
			$result .= '<a href="' . $url . '" title="' . $naam . '" target="_blank">';
		}
		$result .= '<img class="logo" src="' . $imgdir . '/' . $image . '" alt="' . $naam . '"/>';
		if ( $url != '' )
		{
			$result .= '</a>';
		}
		$result .= '</li>';
	}
	$result .= '</ul>';
	
	return $result;
}

add_shortcode( 'akvs_sponsors', 'akvs_sponsors_shortcode' );

/**
 * Toon een lijst met verjaardagen
 *
 * xml:	naam XML bestand met verjaardagen (optioneel, default is 'verjaardagen.xml')
 */
function akvs_format_jarige( $jarig, $dit_jaar )
{
	$leeftijd = $dit_jaar - $jarig->geb_jaar();
	$datum = sprintf( "%4d-%02d-%02d", $dit_jaar, $jarig->geb_maand(), $jarig->geb_dag() );
	return akvs_format_dm( $datum ) . ': ' . $jarig->naam() . ' (' . $leeftijd . ')';
}

function akvs_verjaardagen_shortcode( $atts, $content = '' )
{
	extract( shortcode_atts( array( 
		'xml' => 'verjaardagen.xml' 
	), $atts ) );
	
	$verjaardagen = new AKVS_Verjaardagen( akvs_get_xml_filename( $xml ) );
	$deze_week = $verjaardagen->jarigen();
	
	if ( count( $deze_week ) == 0 )
	{
		return '<p>Er zijn de komende week geen jarigen.</p>';
	}
	
	$jaar = ( int ) date( 'Y' );
	$result = '<ul>';
	foreach ( $deze_week as $jarig )
	{
		$result .= '<li class="verjaardag">' . akvs_format_jarige( $jarig, $jaar ) . '</li>';
	}
	$result .= '</ul>';
	
	return $result;
}

add_shortcode( 'akvs_verjaardagen', 'akvs_verjaardagen_shortcode' );

/**
 * Toon social links
 */
function akvs_social_links_shortcode( $atts, $content = '' )
{
	$imgdir = get_stylesheet_directory_uri() . '/images/social';
	$result = '<ul class="social-links">';
	
	// RSS feed
	$result .= '<li>';
	$img = akvs_img_link( $imgdir, 'Feed_32x32.png', 'Abonneer via RSS', 'RSS feed', array( 
		'width="32px"', 
		'height="32px"' 
	) );
	$result .= akvs_href( get_bloginfo( 'rss2_url' ), $img, array( 
		'target="_blank"' 
	) );
	$result .= '</li>';
	
	// Facebook
	$result .= '<li>';
	$img = akvs_img_link( $imgdir, 'FaceBook_32x32.png', 'Volg via Facebook', 'Facebook', array( 
		'width="32px"', 
		'height="32px"' 
	) );
	$result .= akvs_href( 'https://www.facebook.com/pages/AKV-Soesterkwartier/323520690054', $img, array( 
		'target="_blank"' 
	) );
	$result .= '</li>';
	
	// end of list
	$result .= '</ul><div class="clear"></div>';
	
	return $result;
}

add_shortcode( 'akvs_social_links', 'akvs_social_links_shortcode' );

/**
 * Toon tekst voor wedstrijdsecretariaat
 */
function akvs_ws_shortcode( $atts, $content = '' )
{
	$result = '<p>';
	$result .= 'Als je niet kunt spelen, schrijf dan vroegtijdig af (uiterlijk woensdag) bij het wedstrijdsecretariaat! Voor het midweek team is dit uiterlijk maandagavond 20:00 uur!<br /><br />';
	$result .= '<b>Wedstrijdsecretariaat Senioren en Jeugd</b><br />';
	$result .= 'Patricia van IJzerlooy, <span class="telefoon">(06) 250 86 985</span><br /><br />';
	$result .= 'Het wedstrijdformulier moet na afloop afgegeven worden bij:<br /><br />';
	$result .= 'Patrick Winter, Zeeuwsestraat 27, 3812 GH Amersfoort';
	$result .= '</p>';
	
	return do_shortcode( $result );
}

add_shortcode( 'akvs_ws', 'akvs_ws_shortcode' );
