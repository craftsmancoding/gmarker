<?php
/**
 * geocoding Plugin
 *
 * This Plugin should fire on the OnDocFormSave event (i.e. when saving a MODX page).
 * This should only fire if the values for the lat and lng are found to be empty or zero.
 * It takes location information on the page (e.g. address, city, state, zip TVs), and 
 * passes them to the Google Geocoding API in order to retrieve latitude/longitude info
 * about that address, which it then stores inside the page in pre-defined TVs.
 * See the System Settings for ways to control the behavior of the plugin.
 *
 * @name Geocoding
 * @description Multi-purpose plugin for Moxycart handling URL routing and manager customizations
 * @PluginEvents OnDocFormSave
 */


$core_path = $modx->getOption('gmarker.core_path', null, MODX_CORE_PATH.'components/gmarker/');
include_once $core_path .'vendor/autoload.php';
$Gmarker = new Gmarker($modx);
$modx->lexicon->load('gmarker:default');

$cache_opts = array(xPDO::OPT_CACHE_KEY => 'gmarker');

// Check the event
$events = array('OnDocFormSave');

if (!in_array($modx->event->name, $events)) {
	$modx->log(xPDO::LOG_LEVEL_ERROR, "[Geocoding Plugin] attached to wrong event!");
}

$secure = (int) $modx->getOption('gmarker.secure');
$templates = $modx->getOption('gmarker.templates');
if (empty($templates)) {
	$modx->log(xPDO::LOG_LEVEL_ERROR, '[Gmarker] gmarker.templates not defined.');
	return;
}

$templates = array_map('trim', explode(',',$templates));

// We are only concerned with pages that use one of the special templates
if (!in_array($resource->template, $templates)) {
	return;
}

$tpl = $modx->getOption('gmarker.formatting_string');
$secure = $modx->getOption('gmarker.secure');
$lat_tv = $modx->getOption('gmarker.lat_tv');
$lng_tv = $modx->getOption('gmarker.lng_tv');

if (empty($tpl))
{
	$modx->log(xPDO::LOG_LEVEL_ERROR, '[Gmarker] gmarker.formatting_string cannot be empty for geolocation API queries.');
	return;
}

// Google props: what we will send to the API
$goog = array(); 

// Init w standard fields
$props = $resource->toArray();

// Add all TVs
$templateVars = $resource->getMany('TemplateVars');
foreach ($templateVars as $tv) {
	$tv_name = $tv->get('name');
	$props[$tv_name] = $resource->getTVValue($tv_name);
}

$tvList = array_keys($props);


// A few more checks.
if (!in_array($lat_tv, $tvList)) {
	$modx->log(xPDO::LOG_LEVEL_ERROR, "[Geocoding Plugin] Invalid gmarker.lat_tv setting. TV $lat_tv not assigned to template ". $resource->template);
	return;
}

if (!in_array($lng_tv,$tvList)) {
	$modx->log(xPDO::LOG_LEVEL_ERROR, "[Geocoding Plugin] Invalid gmarker.lng_tv TV $lng_tv not assigned to template ". $resource->template);
	return;
}

// Don't do the lookup if we already have values
//$lat = $resource->getTVValue($lat_tv);
//$lng = $resource->getTVValue($lng_tv);
//if ($lat && $lng)
//{
//	$modx->log(xPDO::LOG_LEVEL_DEBUG, "[Geocoding Plugin] values already present for latitude and longitude; skipping API lookup.");
//	return;
//}

// We load up an "imaginary" chunk: this is done so the output is parsed
// with all the output filters, chunk tags, snippet output etc. 
$uniqid = uniqid();
$chunk = $modx->newObject('modChunk', array('name' => "{geocoding_tmp}-{$uniqid}"));
$chunk->setCacheable(false);
$goog['address'] = $chunk->process($props, $tpl);
$goog['bounds'] = $modx->getOption('gmarker.bounds');
$goog['components'] = $modx->getOption('gmarker.components');
$goog['region'] = $modx->getOption('gmarker.region');
$goog['language'] = $modx->getOption('gmarker.language');


$json = $Gmarker->lookup($goog,$secure);

// Write lat/lng back to the page
$modx->log(xPDO::LOG_LEVEL_ERROR, "[Geocoding Plugin] ".$goog['address']." @COORDS lat:".$Gmarker->get('location.lat')." and lng:".$Gmarker->get('location.lng'));

//if(!$resource->getTVValue($lat_tv)) {
	if(!$resource->setTVValue($lat_tv, $Gmarker->get('location.lat'))) {
		$modx->log(xPDO::LOG_LEVEL_ERROR, $modx->lexicon('problem_saving', array('id'=> $resource->get('id'))));
	}
//}
//if(!$resource->getTVValue($lng_tv)) {
	if(!$resource->setTVValue($lng_tv, $Gmarker->get('location.lng'))) {
		$modx->log(xPDO::LOG_LEVEL_ERROR, $modx->lexicon('problem_saving', array('id'=> $resource->get('id'))));
	}
//}
/*EOF*/