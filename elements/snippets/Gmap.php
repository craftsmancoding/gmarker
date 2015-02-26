<?php
/**
 * @name Gmap
 *
 * @description Draws a dynamic google map; optionally add markers and info-windows by referencing JSON data-stores for Ajax lookups
 *
 * LICENSE:
 * See the core/components/gmarker/docs/license.txt for full licensing info.
 *
 *
 * SNIPPET PARAMETERS:
 *
 * @param string $center - Where the map should be centered. This can either be lat,lng coordinates, or an address (what you might type into a Google Maps search).
 * @param string $headTpl Name of chunk injected into the page <head> containing the JS call to Google Maps API [default=gmap-example]
 * @param string $outTpl Name of chunk containing the map canvas div (correlates with $id) [default=gmap-canvas]
 * @param string $height - height of the map (specify 'px' or '%'). Defaults to gmarker.default_height System Setting
 * @param string $width - width of the map (specify 'px' or '%'). Defaults to gmarker.default_width System Setting
 * @param integer $zoom - A zoom factor for the map. [default=15]
 * @param string $id - CSS dom id of the div where the map will be drawn. [default=map-canvas]
 * @param string $class - the CSS class of the outputted div (identified by &outTpl). Default is empty.
 * @param list $type Determines the type of map used (see https://developers.google.com/maps/documentation/javascript/maptypes) [default=ROADMAP] [options=["ROADMAP","SATELLITE","HYBRID","TERRAIN"]]
 *
 * All other parameters passed to the Snippet are made available to the &outTpl and &headTpl
 *
 * USAGE:
 *
 * Place the snippet call where you want your map to appear.
 *
 * [[!Gmap]]
 *
 * [[Gmap? &width=`100%` &height=`300px` &class=`my_class` &center=`40.3810679,-78.0758859` &zoom=`8`]]
 *
 * WARNING:
 * 	- Your map cannot use percentages for BOTH height and width.
 *
 * @var array $scriptProperties
 * @url http://craftsmancoding.com/
 * @author Everett Griffiths <everett@craftsmancoding.com>
 * @package gmarker
 */


$core_path = $modx->getOption('gmarker.core_path', null, MODX_CORE_PATH.'components/gmarker/');
include_once $core_path .'vendor/autoload.php';

$Gmarker = new Gmarker($modx);
$modx->lexicon->load('gmarker:default');

//------------------------------------------------------------------------------
//! Read inputs
//------------------------------------------------------------------------------
$secure = (int) $modx->getOption('secure', $scriptProperties, $modx->getOption('gmarker.secure'));
$headTpl = $modx->getOption('headTpl', $scriptProperties, 'gmap-example');
$outTpl = $modx->getOption('outTpl', $scriptProperties, 'gmap-canvas');
$center = $modx->getOption('center', $scriptProperties);

$props = $scriptProperties;
$apiParams = array();

// Make sure the map is centered
if (empty($center)) {
	$modx->log(xPDO::LOG_LEVEL_ERROR, '[Gmap] '. $modx->lexicon('missing_center'));
	return sprintf('<script type="text/javascript"> alert(%s);</script>',json_encode('[Gmap] '.$modx->lexicon('missing_center')));
}

// Hit the API only if we need to geocode an address
if (!$Gmarker->isLatLng($center))
{
	$apiParams['address'] = $center;
	$apiParams['bounds'] = $modx->getOption('bounds', $scriptProperties, $modx->getOption('gmarker.bounds'));
	$apiParams['components'] = $modx->getOption('components', $scriptProperties, $modx->getOption('gmarker.components'));
	$apiParams['region'] = $modx->getOption('region', $scriptProperties, $modx->getOption('gmarker.region'));
	$apiParams['language'] = $modx->getOption('language', $scriptProperties, $modx->getOption('gmarker.language'));

	// Look up the map center -- will pull address coordinates from cache if avail.
	$json = $Gmarker->lookup($apiParams, $secure);

	// Pull the coordinates out of the response
	$props['lat'] = number_format($Gmarker->get('location.lat'), 8);
	$props['lng'] = number_format($Gmarker->get('location.lng'), 8);
}
else
{
	list($lat, $lng) = explode(',', $center);
	$props['lat'] = number_format($lat, 8);
	$props['lng'] = number_format($lng, 8);
}

// Props used in the headerTpl or outTpl

$props['height'] = $modx->getOption('height', $scriptProperties, $modx->getOption('gmarker.default_height'));
$props['width'] = $modx->getOption('width', $scriptProperties, $modx->getOption('gmarker.default_width'));
$props['style'] = $modx->getOption('style', $scriptProperties, $modx->getOption('gmarker.style'));
$props['id'] = $modx->getOption('id', $scriptProperties, 'map-canvas');
$props['class'] = $modx->getOption('class', $scriptProperties);
$props['zoom'] = (int) $modx->getOption('zoom', $scriptProperties, 8);
$props['type'] = $modx->getOption('type', $scriptProperties, 'ROADMAP');

if (empty($props['style'])) {
	$props['style'] = '[]';
}


// Make sure we have viable dimensions
if (strpos($props['height'],'%') === false && strpos($props['height'],'px') === false)
{
	$modx->log(xPDO::LOG_LEVEL_ERROR, '[Gmap] '. $modx->lexicon('invalid_height'));
	return sprintf('<script type="text/javascript"> alert(%s);</script>',json_encode('[Gmap] '.$modx->lexicon('invalid_height')));
}
if (strpos($props['width'],'%') === false && strpos($props['width'],'px') === false)
{
	$modx->log(xPDO::LOG_LEVEL_ERROR, '[Gmap] '. $modx->lexicon('invalid_width'));
	return sprintf('<script type="text/javascript"> alert(%s);</script>',json_encode('[Gmap] '.$modx->lexicon('invalid_width')));
}
if (strpos($props['height'],'%') !== false && strpos($props['width'],'%') !== false)
{
	$modx->log(xPDO::LOG_LEVEL_ERROR, '[Gmap] '. $modx->lexicon('missing_center'));
	return sprintf('<script type="text/javascript"> alert(%s);</script>',json_encode('[Gmap] '.$modx->lexicon('invalid_dimensions')));
}




// Add the stuff to the head
$modx->regClientStartupHTMLBlock($modx->getChunk($headTpl, $props));
// Send output on its way
return $modx->parseChunk($outTpl, $props);

/*EOF*/