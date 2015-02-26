<?php
/**
 * Gmarker v2
 *
 * This Snippet draws a map with one or more markers on it to identify locations. 
 * Its syntax is similar to getResources: it iterates over multiple locations, e.g. over pages
 * which contain address information.
 *
 *
 * LICENSE:
 * See the core/components/gmarker/docs/license.txt for full licensing info.
 *
 *
 * SNIPPET PARAMETERS:
 *
 * @param string $center - Where the map should be centered. This can either be lat,lng coordinates, or an address (what you might type into a Google Maps search).
 * @param string $height - height of the map (specify 'px' or '%'). Defaults to gmarker.default_height System Setting
 * @param string $width - width of the map (specify 'px' or '%'). Defaults to gmarker.default_width System Setting
 * @param integer $zoom - A zoom factor for the map. [default=15]
 * @param string $id - CSS dom id of the div where the map will be drawn. [default=map-canvas]
 * @param string $class - the CSS class of the outputted div (identified by &outTpl). Default is empty.
 * @param list $type Determines the type of map used (see https://developers.google.com/maps/documentation/javascript/maptypes) [default=ROADMAP] [options=["ROADMAP","SATELLITE","HYBRID","TERRAIN"]]
 * @param string $headTpl Name of chunk injected into the page <head> [default=gmarkershead]
 * @param string $outTpl Name of chunk containing the map canvas div (correlates with $id) [default=g_out]
 * @param boolean $secure
 * All other parameters passed to the Snippet are made available to the $outTpl Chunk as placeholders.
 *
 * USAGE:
 *
 * Place the snippet call where you want your map to appear.
 *
 * [[!Gmarker]]
 *
 * [[Gmarker? &width=`100%` &height=`300px` &class=`my_class` &center=`40.3810679,-78.0758859` &zoom=`8`]]
 *
 * @var array $scriptProperties
 *
 * @name Gmarker
 * @no_import
 * @description Iterates over pages containing location data to draw a Google Map with markers on it.
 * @url http://craftsmancoding.com/
 * @author Everett Griffiths <everett@craftsmancoding.com>
 * @package garmker
 */


$core_path = $modx->getOption('gmarker.core_path', null, MODX_CORE_PATH.'components/gmarker/');
include_once $core_path .'vendor/autoload.php';
//require_once(MODX_CORE_PATH.'components/gmarker/model/gmarker/Gmarker.class.php');

$Gmarker = new Gmarker($modx);
$modx->lexicon->load('gmarker:default');

//------------------------------------------------------------------------------
//! Read inputs
//------------------------------------------------------------------------------
$secure = (int) $modx->getOption('secure', $scriptProperties, $modx->getOption('gmarker.secure'));
$headTpl = $modx->getOption('headTpl', $scriptProperties, 'gmarkershead');
$outTpl = $modx->getOption('outTpl', $scriptProperties, 'g_out');


// Props that influence the address fingerprint and the Lat/Lng cache
$apiParams = array();
$apiParams['address'] = $modx->getOption('address', $scriptProperties, '');
$apiParams['latlng'] = $modx->getOption('latlng', $scriptProperties, '');
$apiParams['bounds'] = $modx->getOption('bounds', $scriptProperties, $modx->getOption('gmarker.bounds'));
$apiParams['components'] = $modx->getOption('components', $scriptProperties, $modx->getOption('gmarker.components'));
$apiParams['region'] = $modx->getOption('region', $scriptProperties, $modx->getOption('gmarker.region'));
$apiParams['language'] = $modx->getOption('language', $scriptProperties, $modx->getOption('gmarker.language'));

// Props used in the headerTpl or outTpl
$props = $scriptProperties;
$props['h'] = $modx->getOption('height', $scriptProperties, $modx->getOption('gmarker.default_height'));
$props['w'] = $modx->getOption('width', $scriptProperties, $modx->getOption('gmarker.default_width'));
$props['style'] = $modx->getOption('style', $scriptProperties, $modx->getOption('gmarker.style',null,'[]'));
$props['id'] = $modx->getOption('id', $scriptProperties, 'map-canvas');
$props['class'] = $modx->getOption('class', $scriptProperties);
$props['zoom'] = (int) $modx->getOption('zoom', $scriptProperties, 8);
$props['type'] = $modx->getOption('type', $scriptProperties, 'ROADMAP');

if (empty($apiParams['address']) && empty($apiParams['latlng'])) {
	$modx->log(xPDO::LOG_LEVEL_ERROR, '[Gmarker] '. $modx->lexicon('missing_center'));
	return sprintf('<script type="text/javascript"> alert(%s);</script>',json_encode($modx->lexicon('missing_center')));
}

// Look up the map center
$json = $Gmarker->lookup($apiParams, $secure);

// Pull the coordinates out of the response
$props['lat'] = number_format($Gmarker->get('location.lat'), 8);
$props['lng'] = number_format($Gmarker->get('location.lng'), 8);


// Add the stuff to the head
$modx->regClientStartupHTMLBlock($modx->getChunk($headTpl, $props));

return $modx->parseChunk($outTpl, $props);

/*EOF*/