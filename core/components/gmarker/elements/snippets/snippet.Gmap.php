<?php
/**
 * Gmap
 *
 * This Snippet draws a map centered on a single location, identified either by an address 
 * or by latitude/longitude coordinates.
 *
 * LICENSE:
 * See the core/components/gmarker/docs/license.txt for full licensing info.
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
 * [[Gmap? &lat=`-12.043333` &lng=`-77.028333`]]
 *
 * OR
 *
 * [[Gmap? &lat=`-12.043333` &lng=`-77.028333` &height=`400` &width=`800`]]
 *
 * @var array $scriptProperties
 *
 * @name Gmap
 * @url http://craftsmancoding.com/
 * @author Everett Griffiths <everett@craftsmancoding.com>
 * @package gmarker
 */



require_once(MODX_CORE_PATH.'components/gmarker/model/gmarker/Gmarker.class.php');

$cache_opts = array(xPDO::OPT_CACHE_KEY => 'gmarker');

$Gmarker = new Gmarker();
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
$props2 = array();
$props2['h'] = (int) $modx->getOption('height', $scriptProperties, $modx->getOption('gmarker.default_height'));
$props2['w'] = (int) $modx->getOption('width', $scriptProperties, $modx->getOption('gmarker.default_width'));
$props2['id'] = $modx->getOption('id', $scriptProperties, 'map');
$props2['zoom'] = $modx->getOption('zoom', $scriptProperties, 15);
$props2['type'] = $modx->getOption('type', $scriptProperties, 'ROADMAP');
$props2['gmarker_url'] = $Gmarker->get_maps_url(array('key'=>$modx->getOption('gmarker.apikey')), $secure);

// Verify inputs
if (empty($address) && empty($latlng) && empty($components)) {
	$modx->log(xPDO::LOG_LEVEL_ERROR, '[Gmap] '. $modx->lexicon('missing_params'));
	return $Gmarker->alert($modx->lexicon('missing_params'));
}

if (!$props2['h']) {
	$props2['h'] = 300;
}
if (!$props2['w']) {
	$props2['w'] = 500;
}

// Handle lookups and caching
// Fingerprint the lookup
$json = $Gmarker->lookup($props);

// Pull the coordinates out
$props2['lat'] = $Gmarker->get('location.lat');
$props2['lng'] = $Gmarker->get('location.lng');


// Add the stuff to the head
$modx->regClientStartupHTMLBlock($modx->getChunk($headTpl, $props2));

return $modx->parseChunk($outTpl, $props2);

/*EOF*/