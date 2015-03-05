<?php
/**
 * @name Geocode
 * @description geocoding/reverse-geocoding utility script used to lookup latitude/longitude from a given address. Also available as a hook (e.g. for Formit or Login).
 *
 * The results for any address are returned from cache whenever possible to help reduce the load on the Google Maps API.
 *
 * When used as a hook (e.g. for Formit or for the Login's Register Snippet), the 
 * Snippet parameters are irrelevant and you must instead rely on the system settings. 
 * Any fields referenced in your gmarkers.formatting_string must be available as form fields
 * and accessible via the $hook->getValue() method.  After executing, the Geocode hook
 * will add 2 fields named after the gmarker.lat_tv and gmarker.lng_tv.  This is useful
 * if another hook writes this data to the database.
 * 
 *
 * LICENSE:
 * See the core/components/gmarkers/docs/license.txt for full licensing info.
 *
 *
 * SNIPPET PARAMETERS:
 *
 * @param string &address - What you might type into a Google Maps search. If not present, the [[++gmarkers.formatting_string]] will be used, which should in turn pull in relevant address info from the page on which this snippet appears.
 * @param string &latlng - This overrides the &address argument and is used only for reverse geocoding, e.g. "40.714224,-73.961452"
 * @param string &prefix Used to prefix placeholder names, useful if you have multiple instances of this Snippet on one page.
 * @param string &bounds Defaults to [[++gmarkers.bounds]] System Setting
 * @param string &components Defaults to [[++gmarkers.components]] System Setting
 * @param string &region Defaults to [[++gmarkers.region]] System Setting
 * @param string &language Defaults to [[++gmarkers.language]] System Setting
 * @param boolean &secure Defaults to [[++gmarkers.secure]] System Setting
 *
 * USAGE 1 (Snippet):
 *
 * Place the uncached snippet call anywhere on your page:
 *
 * [[!Geocode? &address=`123 Main St. Anywhere, OH`]]
 *
 * On your page, you may use the following placeholders:
 *
 * 	[[+formatted_address]] : a sanitized version of the input address.
 *	[[+location.lat]] : The pinpoint latitude, e.g. "37.42291810"
 * 	[[+location.lng]] : The pinpoint longitude, e.g. "-122.08542120"
 *	[[+northeast.lat]] : The latitude of the northeast boundary, e.g. "37.42426708029149"
 * 	[[+northeast.lng]] : The longitude of the northeast boundary, e.g. "-122.0840722197085"
 *	[[+southwest.lat]] : The latitude of the southwest boundary, e.g. "37.42156911970850"
 *	[[+southwest.lng]] : The longitude of the southwest boundary, e.g. "-122.0867701802915"
 * 	[[+location_type]] e.g. "ROOFTOP"
 * 	[[+status]] e.g. "OK"
 *
 * 
 * USAGE 2 (Hook)
 *
 * Make sure your form passes all the information required to construct a valid address.
 * E.g. if your gmarkers.formatting_string is "[[+address]],[[+city]],[[+state]]", then 
 * the following FormIt call could be used to perform Geocoding.
 *
 * 	[[!FormIt? &hooks=`Geocode` ... ]]
 * 		<!-- other form fields here... -->
 * 		<input type="text" name="address" />
 * 		<input type="text" name="city" />
 * 		<input type="text" name="state" />
 *		<!-- the rest of the form... -->
 * 
 * @var array $scriptProperties
 *
 * @url http://craftsmancoding.com/
 * @author Everett Griffiths <everett@craftsmancoding.com>
 * @package gmarker
 */

$core_path = $modx->getOption('gmarker.core_path', null, MODX_CORE_PATH.'components/gmarker/');
include_once $core_path .'vendor/autoload.php';

//require_once(MODX_CORE_PATH.'components/gmarker/model/gmarker/Gmarker.class.php');

$Gmarker = new Gmarker($modx);
$modx->lexicon->load('gmarker:default');

$goog = array();

//------------------------------------------------------------------------------
//! Hook Mode
//------------------------------------------------------------------------------
if (is_object($hook)) {
	$modx->log(xPDO::LOG_LEVEL_DEBUG, '[Geocode] being used as a hook.');
	$props = $hook->getValues();
	
	$uniqid = uniqid();
	$chunk = $modx->newObject('modChunk', array('name' => "{geocoding_tmp}-{$uniqid}"));
	$chunk->setCacheable(false);
	
	$tpl = $modx->getOption('gmarker.formatting_string');
	$secure = $modx->getOption('gmarker.secure');
	$lat_tv = ($hook->getValue('gmarker.lat_tv') ? $hook->getValue('gmarker.lat_tv') : $modx->getOption('gmarker.lat_tv'));
	$lng_tv = ($hook->getValue('gmarker.lng_tv') ? $hook->getValue('gmarker.lng_tv') : $modx->getOption('gmarker.lng_tv'));
	
	if (empty($lat_tv) || empty($lng_tv)) {
		$modx->log(xPDO::LOG_LEVEL_ERROR, '[Geocode] '.$modx->lexicon('hook_error'));
		return true;
	}
	
	$goog['address'] = $chunk->process($props, $tpl);
	$goog['bounds'] = ($hook->getValue('gmarker.bounds') ? $hook->getValue('gmarker.bounds') : $modx->getOption('gmarker.bounds'));
	$goog['components'] = ($hook->getValue('gmarker.components') ? $hook->getValue('gmarker.components') : $modx->getOption('gmarker.components'));
	$goog['region'] = ($hook->getValue('gmarker.region') ? $hook->getValue('gmarker.region') : $modx->getOption('gmarker.region'));
	$goog['language'] = ($hook->getValue('gmarker.language') ? $hook->getValue('gmarker.language') : $modx->getOption('gmarker.language'));

	$Gmarker->lookup($goog, $secure, $refresh);

	$lat = $Gmarker->get('location.lat');
	if (empty($lat)) {
		$lat = 0;
	}
	$lng = $Gmarker->get('location.lng');
	if (empty($lng)) {
		$lng = 0;
	}

	$hook->setValue($lat_tv, $lat);
	$hook->setValue($lng_tv, $lng);
	
	return true;
}

//------------------------------------------------------------------------------
//! Snippet Mode
//------------------------------------------------------------------------------
// Read inputs
$goog['address'] = $modx->getOption('address', $scriptProperties, $modx->getOption('gmarker.formatting_string'));
$goog['latlng'] = $modx->getOption('latlng', $scriptProperties, '');
$goog['bounds'] = $modx->getOption('bounds', $scriptProperties, $modx->getOption('gmarker.bounds'));
$goog['components'] = $modx->getOption('components', $scriptProperties, $modx->getOption('gmarker.components'));
$goog['region'] = $modx->getOption('region', $scriptProperties, $modx->getOption('gmarker.region'));
$goog['language'] = $modx->getOption('language', $scriptProperties, $modx->getOption('gmarker.language'));

$refresh = $modx->getOption('refresh', $scriptProperties, 0);
$prefix = $modx->getOption('prefix', $scriptProperties, '');
$secure = $modx->getOption('secure', $scriptProperties, $modx->getOption('gmarker.secure'));

// Verify inputs
if (empty($address) && empty($latlng) && empty($components)) {
	$modx->log(xPDO::LOG_LEVEL_ERROR, '[Geocode] '. $modx->lexicon('missing_params'));
	$modx->setPlaceholder($prefix.'status', $modx->lexicon('missing_params'));
	return;
}

$json = $Gmarker->lookup($goog, $secure, $refresh);

// Set placeholders
// ensure we have SOMETHING for the lat/lng
$lat = $Gmarker->get('location.lat');
if (empty($lat)) {
	$lat = 0;
}
$lng = $Gmarker->get('location.lng');
if (empty($lng)) {
	$lng = 0;
}
$modx->setPlaceholder($prefix.'formatted_address',$Gmarker->get('formatted_address'));
$modx->setPlaceholder($prefix.'location.lat',$lat);
$modx->setPlaceholder($prefix.'location.lng',$lng);
$modx->setPlaceholder($prefix.'northeast.lat',$Gmarker->get('northeast.lat'));
$modx->setPlaceholder($prefix.'southwest.lat',$Gmarker->get('northeast.lng'));
$modx->setPlaceholder($prefix.'southwest.lng',$Gmarker->get('southwest.lng'));
$modx->setPlaceholder($prefix.'location_type',$Gmarker->get('location_type'));
$modx->setPlaceholder($prefix.'status',$Gmarker->get('status'));
$modx->setPlaceholder($prefix.'json',$json);

/*EOF*/