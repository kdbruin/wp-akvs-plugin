<?php

/**
 * Generieke utility functies
 *
 * @package AKVS-Plugin
 * @author Kees de Bruin
 */

/**
 * DATUM EN TIJD
 */

/**
 * formateer dag (naam, afkorting) voor de gegeven datum
 */
function akvs_format_d( $datum )
{
	return date_i18n( 'D', strtotime( $datum ) );
}

/**
 * formateer dag en maand (afkorting) voor de gegeven datum
 */
function akvs_format_dm( $datum )
{
	return date_i18n( 'j M', strtotime( $datum ) );
}

/**
 * formateer dag (naam, afkorting), dag en maand (afkorting) voor de gegeven datum
 */
function akvs_format_ddm( $datum )
{
	return date_i18n( 'D j M', strtotime( $datum ) );
}

/**
 * verwijder dag van datum
 */
function akvs_extract_mj( $datum )
{
	return preg_replace( '/-\d\d$/', '', $datum );
}

/**
 * formateer jaar en maand
 */
function akvs_format_mj( $datum )
{
	return date_i18n( 'F Y', strtotime( $datum ) );
}

/**
 * formateer ISO datum
 */
function akvs_format_iso( $datum )
{
	return date( 'Y-m-d', strtotime( $datum ) );
}

/**
 * geef datums van zondag tot zondag waarbinnen de opgegeven datum valt
 */
function akvs_week_range( $datum )
{
	$ts = strtotime( $datum );
	$start = strtotime( 'monday this week', $ts );
	$end = strtotime( 'monday this week + 1 week', $ts );
	return array( 
		date( 'Y-m-d', $start ), 
		date( 'Y-m-d', $end ) 
	);
}

/**
 * PERMALINK VOOR ROUTES
 */
function akvs_get_slug( $naam )
{
	return sanitize_title( $naam );
}

/**
 * LINKS
 */
function akvs_get_team_url( $team )
{
	if ( preg_match( '/^soesterkw/i', $team ) )
	{
		$url = home_url( '/competitie/' . akvs_get_slug( $team ) . '/' );
		$result = akvs_href( $url, $team );
	}
	else
	{
		$result = $team;
	}
	return $result;
}

function akvs_get_lokatie_url( $lokatie, $isThuis )
{
	$result = '';
	if ( $isThuis )
	{
		$result = $lokatie;
	}
	else
	{
		$lokatie_stripped = preg_replace( '/\s+\([^)]+\)\s*$/', '', $lokatie );
		$url = home_url( '/routes/' . akvs_get_slug( $lokatie_stripped ) . '/' );
		$result = akvs_href( $url, $lokatie, array( 
			'target="_blank"' 
		) );
	}
	return $result;
}

function akvs_img_link( $dir, $fname, $title = '', $alt = '', $atts = array() )
{
	$result = '<img src="' . $dir . '/' . $fname . '"';
	if ( !empty( $title ) ) $result .= ' title="' . $title . '"';
	if ( !empty( $alt ) ) $result .= ' alt="' . $alt . '"';
	foreach ( $atts as $att )
	{
		$result .= ' ' . $att;
	}
	$result .= ' />';
	
	return $result;
}

function akvs_href( $url, $content, $atts = array() )
{
	$result .= '<a href="' . $url . '"';
	foreach ( $atts as $att )
	{
		$result .= ' ' . $att;
	}
	$result .= '>' . $content . '</a>';
	
	return $result;
}

/**
 * TABELLEN
 */
function akvs_start_element( $e, $class = '', $atts = array() )
{
	$result = '<' . $e;
	if ( !empty( $class ) ) $result .= ' class="' . $class . '"';
	foreach ( $atts as $att )
	{
		$result .= ' ' . $att;
	}
	$result .= '>';
	
	return $result;
}

function akvs_end_element( $e )
{
	return '</' . $e . '>';
}

function akvs_start_end_element( $e, $content, $class = '', $atts = array() )
{
	$result = '<' . $e;
	if ( !empty( $class ) ) $result .= ' class="' . $class . '"';
	foreach ( $atts as $att )
	{
		$result .= ' ' . $att;
	}
	$result .= '>' . $content . '</' . $e . '>';
	
	return $result;
}

function akvs_table( $class = '' )
{
	return akvs_start_element( 'table', $class );
}

function akvs_thead( $elts, $class = 'header' )
{
	$result = '<thead>';
	$result .= akvs_start_element( 'tr', $class );
	foreach ( $elts as $e )
	{
		if ( preg_match( '/(.*)@(\d+)$/', $e, $m ) )
		{
			$result .= akvs_start_end_element( 'th', $m[ 1 ], '', array( 
				'colspan="' . $m[ 2 ] . '"' 
			) );
		}
		else
		{
			$result .= akvs_start_end_element( 'th', $e );
		}
	}
	$result .= '</tr></thead>';
	
	return $result;
}

function akvs_tbody( $class = '' )
{
	return akvs_start_element( 'tbody', $class );
}

function akvs_tr( $count, $class = 'alt' )
{
	$result = '<tr';
	if ( $count % 2 == 0 ) $result .= ' class="' . $class . '"';
	$result .= '>';
	
	return $result;
}

function akvs_td( $content, $class = '' )
{
	$result = '<td';
	if ( $class != '' ) $result .= ' class="' . $class . '"';
	$result .= '>' . $content . '</td>';
	
	return $result;
}

/**
 * UITSLAGEN
 */
function akvs_format_uitslag_1( $uitslag )
{
	if ( preg_match( '/(\d+)\s*-\s*(\d+)/', $uitslag, $matches ) )
	{
		return "$matches[1] - $matches[2]";
	}
	
	return $uitslag;
}

function akvs_format_uitslag( $uitslag )
{
	$result = '';
	if ( is_array( $uitslag ) )
	{
		foreach ( $uitslag as $index => $u )
		{
			if ( !empty( $result ) ) $result .= '<br />';
			$result .= akvs_format_uitslag_1( $u );
		}
	}
	else
	{
		$result = akvs_format_uitslag_1( $uitslag );
	}
	
	return $result;
}

/**
 * ALGEMEEN
 */
function get_category_id( $slug )
{
	$term = get_term_by( 'slug', $slug, 'category' );
	if ( $term ) return $term->term_id;

	return '';
}

function akvs_get_xml_meta( $post_id, $meta_name )
{
	return akvs_get_xml_filename( get_post_meta( $post_id, $meta_name, TRUE ) );
}

function akvs_get_xml_filename( $xml )
{
	$result = '';
	$upload_dir = wp_upload_dir();
	if ( !empty( $upload_dir[ 'error' ] ) )
	{
		$result = trailingslashit( $upload_dir[ 'basedir' ] );
	}
	else
	{
		$result = trailingslashit( WP_CONTENT_DIR ) . 'uploads/';
	}
	$result = $result . 'akvsoesterkwartier/' . $xml;
	return $result;
}
