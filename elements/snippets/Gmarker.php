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
 * &address string (optional) what you might type into a Google Maps search that defines where the map should be centered.
 * &latlng mixed (optional) latitude,longitude coordinates for centering the map.  Overrides &address when present.
 * &lng long (optional) longitude.  Overrides &address.
 * &height mixed (optional) height of the map (include 'px' or '%'). Defaults to [[++gmarker.default_height]]
 * &width mixed (optional) width of the map (include 'px' or '%'). Defaults to [[++gmarker.default_width]]
 * &zoom integer (optional) A zoom factor for the map. default: 15.
 * &id string CSS id of the div where the map will be drawn. Default: map-canvas
 * &class the CSS class of the outputted div (identified by &outTpl). Default is empty.
 * &zoom integer (optional) zoom level of the map. Default: 8
 * &type string (optional) ROADMAP (default), SATELLITE, HYBRID, TERRAIN; From https://developers.google.com/maps/documentation/javascript/maptypes
 * All other parameters passed to the Snippet are made available to the
 *
 * USAGE:
 *
 * Place the snippet call where you want your map to appear.
 *
 * [[!Gmarker]]
 *
 * [[Gmarker? &width=`100%` &height=`300px` &class=`my_class` &latlng=`40.3810679,-78.0758859` &zoom=`8`]]
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