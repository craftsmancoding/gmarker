<?php
/**
 * @name Gstatic
 * @description Draws a Google Map of the area indicated by &address or &latlng.
 *
 * @no_import TODO
 *
 * LICENSE:
 * See the docs/license.txt for full licensing info.
 *
 *
 * SNIPPET PARAMETERS:
 *
 * &address string (optional) what you might type into a Google Maps search. If not present,
 * 	the [[++gmarker.formatting_string]] will be used, which should in turn pull in relevant
 *	address info from the page on which this snippet appears.
 * &lat long (optional) latitude.  Overrides &address.
 * &lng long (optional) longitude.  Overrides &address.
 * &height integer (optional) hieght of the map in pixels. Defaults to [[++gmarker.default_height]]
 * &width integer (optional) width of the map in pixels. Defaults to [[++gmarker.default_width]]
 * &static boolean 1|0 (option). If set, the map will be fixed, undraggable. Default: 0
 * &zoom integer (optional) A zoom factor for the map. default: 15.
 * &js string (optional) Default: [[++gmarker.jquery]]
 * 
 *
 * USAGE:
 *
 * Place the snippet call where you want your map to appear.
 *
 * [[!Gmap? &address=`221 B Baker St. London, England`]]
 *
 * OR
 *
 * [[Gmap? &latlng=`-12.043333,-77.028333`]]
 *
 * OR
 *
 * [[Gmap? &lat=`-12.043333` &lng=`-77.028333` &height=`400` &width=`800`]]
 *
 * @var array $scriptProperties
 *
  * @url http://craftsmancoding.com/
 * @author Everett Griffiths <everett@craftsmancoding.com>
 * @package gmarker
 */


$core_path = $modx->getOption('gmarker.core_path', null, MODX_CORE_PATH.'components/gmarker/');
include_once $core_path .'vendor/autoload.php';

$Gmarker = new Gmarker($modx);
$modx->lexicon->load('gmarker:default');


// Read inputs
// First some controlling props
$refresh = (int) $modx->getOption('refresh', $scriptProperties, 0);
$secure = (int) $modx->getOption('secure', $scriptProperties, $modx->getOption('gmarker.secure'));
$headTpl = $modx->getOption('headTpl', $scriptProperties, 'gmapshead');
$outTpl = $modx->getOption('outTpl', $scriptProperties, 'g_out'); 

// Props that influence the address fingerprint and the Lat/Lng cache
$props = array();
$props['address'] = $modx->getOption('address', $scriptProperties, $modx->getOption('gmarker.formatting_string'));
$props['latlng'] = $modx->getOption('latlng', $scriptProperties, '');
$props['bounds'] = $modx->getOption('bounds', $scriptProperties, $modx->getOption('gmarker.bounds'));
$props['components'] = $modx->getOption('components', $scriptProperties, $modx->getOption('gmarker.components'));
$props['region'] = $modx->getOption('region', $scriptProperties, $modx->getOption('gmarker.region'));
$props['language'] = $modx->getOption('language', $scriptProperties, $modx->getOption('gmarker.language'));

// Other props that are used in the output
$headerProps = array();
$headerProps['h'] = $modx->getOption('height', $scriptProperties, $modx->getOption('gmarker.default_height'));
$headerProps['w'] = $modx->getOption('width', $scriptProperties, $modx->getOption('gmarker.default_width'));
$headerProps['id'] = $modx->getOption('id', $scriptProperties, 'map');
$headerProps['zoom'] = $modx->getOption('zoom', $scriptProperties, 15);
$headerProps['type'] = $modx->getOption('type', $scriptProperties, 'ROADMAP');
$headerProps['gmarker_url'] = $Gmarker->get_maps_url(array('key'=>$modx->getOption('gmarker.apikey')), $secure);

// Verify inputs
if (empty($address) && empty($latlng)) {
	$modx->log(xPDO::LOG_LEVEL_ERROR, '[Gmap] '. $modx->lexicon('missing_params'));
	return $Gmarker->alert($modx->lexicon('missing_params'));
}

// Handle lookups and caching
// Fingerprint the lookup
$json = $Gmarker->lookup($props);

// Pull the coordinates out
$headerProps['lat'] = number_format($Gmarker->get('location.lat'), 8);
$headerProps['lng'] = number_format($Gmarker->get('location.lng'), 8);


// Add the stuff to the head
$modx->regClientStartupHTMLBlock($modx->getChunk($headTpl, $headerProps));

return $modx->parseChunk($outTpl, $headerProps);

/*EOF*/