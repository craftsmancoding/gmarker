<?php
/**
 * Gmarker
 *
 * This Snippet draws a map with one or more markers on it to identify locations. 
 * It leverages getResources to iterate over multiple locations, e.g. over pages
 * which contain address information.
 * It is centered centered by either by an address or by lat/lng coordinates.
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
 * &suppressLookup default 0.
 * &group string name of a field to group by. A group of markers has its own color and a checkbox control to show/hide
 * &class the CSS class of the outputted div (identified by &outTpl). Default is empty.
 *
 * USAGE:
 *
 * Place the snippet call where you want your map to appear.
 *
 * [[!Gmarker]]
 *
 * [[Gmarker? &width=`260` &height=`206` &class=`my_class` &latlng=`40.3810679,-78.0758859` &parents=`7` &zoom=`8` &tvName=`category` &tvValue=`[[*id]]`]]
 *
 * @var array $scriptProperties
 *
 * @name Gmarker
 * @url http://craftsmancoding.com/
 * @author Everett Griffiths <everett@craftsmancoding.com>
 * @package garmker
 */


require_once(MODX_CORE_PATH.'components/gmarker/model/gmarker/Gmarker.class.php');

$Gmarker = new Gmarker(); 
$modx->lexicon->load('gmarker:default');

//------------------------------------------------------------------------------
//! Read inputs
//------------------------------------------------------------------------------
// Basic controls (only some...)
$secure = (int) $modx->getOption('secure', $scriptProperties, $modx->getOption('gmarker.secure'));
$headTpl = $modx->getOption('headTpl', $scriptProperties, 'gmarkershead');
$markerTpl = $modx->getOption('markerTpl', $scriptProperties, 'gmarker');
$resultTpl = $modx->getOption('resultTpl', $scriptProperties, 'gresult');
$checkboxTpl = $modx->getOption('resultTpl', $scriptProperties, 'gcheckbox'); 
$outTpl = $modx->getOption('outTpl', $scriptProperties, 'g_out'); 
$showResults = $modx->getOption('showResults', $scriptProperties, 0);
$info = (int) $modx->getOption('info', $scriptProperties, 1);
$infoTpl = $modx->getOption('infoTpl', $scriptProperties, 'ginfo');
$tvPrefix = $modx->getOption('tvPrefix', $scriptProperties, '');
$resources = $modx->getOption('resources', $scriptProperties, '');
$shadow = $modx->getOption('shadow', $scriptProperties, 1);
$suppressLookup = $modx->getOption('suppressLookup', $scriptProperties, 0);
$lat_tv = $modx->getOption('gmarker.lat_tv');
$lng_tv = $modx->getOption('gmarker.lng_tv');
$tpl = $modx->getOption('gmarker.formatting_string');
$drop = $modx->getOption('gmarker.formatting_string', $scriptProperties, 0);
$marker_color = $modx->getOption('marker_color',$scriptProperties,'FE7569');
$checkbox = $modx->getOption('checkbox', $scriptProperties, 0);
$group = $modx->getOption('group', $scriptProperties, null); // see http://gmap3.net/examples/tags.html
$templates = $modx->getOption('templates',$scriptProperties);
$tvName = $modx->getOption('tvName',$scriptProperties);
$tvValue = $modx->getOption('tvValue',$scriptProperties);
$groupCallback = $modx->getOption('groupCallback',$scriptProperties);
$parents = $modx->getOption('parents',$scriptProperties);
$tvs = $modx->getOption('tvs',$scriptProperties);
$debug = $modx->getOption('debug',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties);
$offset = $modx->getOption('offset',$scriptProperties,0);

$marker_center = '%E2%80%A2';
$distinct_groups = array(); // init
$parents = (!empty($parents)) ? explode(',', $parents) : array();
$templates = (!empty($templates)) ? explode(',', $templates) : array();
$tvs = (!empty($tvs)) ? explode(',', $tvs) : array();

array_walk($parents, 'trim');
array_walk($templates, 'trim');
array_walk($tvs, 'trim');

$LatTV = $modx->getObject('modTemplateVar', array('name'=>$lat_tv));
$LngTV = $modx->getObject('modTemplateVar', array('name'=>$lng_tv));
if (!$LatTV) {
	$modx->log(xPDO::LOG_LEVEL_ERROR, '[Gmarker] '. $modx->lexicon('tv_not_found', array('tv'=> $lat_tv)));
	return $modx->lexicon('tv_not_found', array('tv'=> $lat_tv));
}
if (!$LngTV) {
	$modx->log(xPDO::LOG_LEVEL_ERROR, '[Gmarker] '. $modx->lexicon('tv_not_found', array('tv'=> $lng_tv)));
	return $modx->lexicon('tv_not_found', array('tv'=> $lng_tv));
}
$lat_tv_id = $LatTV->get('id');
$lng_tv_id = $LngTV->get('id');

$tv_filters = array();

// Trigger a query on the modTemplateVarResource (for performance reasons).
if ($tvName && $tvValue) {

	$tvValues = array();
	$tvValues = explode(',',$tvValue);
	array_walk($tvValues, 'trim');
	
	$tv = $modx->getObject('modTemplateVar', array('name'=>$tvName));
	
	if (!$tv) {
		$modx->log(xPDO::LOG_LEVEL_ERROR, '[Gmarker] '. $modx->lexicon('tv_not_found', array('tv'=> $tvName)));
		return $modx->lexicon('tv_not_found', array('tv'=> $tvName));
	}
	
	$criteria = array();
	$criteria['tmplvarid'] = $tv->get('id');
	$criteria['value:IN'] = $tvValues;

	$criteria = $modx->newQuery('modTemplateVarResource', $criteria);
	$tvrs = $modx->getCollection('modTemplateVarResource', $criteria);
	foreach ($tvrs as $tvr) {
		$tv_filters[] = $tvr->get('contentid');
	}
}

// Props that influence the address fingerprint and the Lat/Lng cache
$goog = array();
$goog['address'] = $modx->getOption('address', $scriptProperties, '');
$goog['latlng'] = $modx->getOption('latlng', $scriptProperties, '');
$goog['bounds'] = $modx->getOption('bounds', $scriptProperties, $modx->getOption('gmarker.bounds'));
$goog['components'] = $modx->getOption('components', $scriptProperties, $modx->getOption('gmarker.components'));
$goog['region'] = $modx->getOption('region', $scriptProperties, $modx->getOption('gmarker.region'));
$goog['language'] = $modx->getOption('language', $scriptProperties, $modx->getOption('gmarker.language'));

// Props used in the headerTpl
$props = array();
$props['h'] = (int) $modx->getOption('height', $scriptProperties, $modx->getOption('gmarker.default_height'));
$props['w'] = (int) $modx->getOption('width', $scriptProperties, $modx->getOption('gmarker.default_width'));
$props['id'] = $modx->getOption('id', $scriptProperties, 'map');
$props['class'] = $modx->getOption('class', $scriptProperties);
$props['zoom'] = (int) $modx->getOption('zoom', $scriptProperties, 15);
$props['gmarker_url'] = $Gmarker->get_maps_url(array('key'=>$modx->getOption('gmarker.apikey')), $secure);
$props['type'] = $modx->getOption('type', $scriptProperties, 'ROADMAP');

// Used for search results
$results = '';


// Verify inputs
if (!$props['h']) {
	$props['h'] = 300;
}
if (!$props['w']) {
	$props['w'] = 500;
}

if (empty($goog['address']) && empty($goog['latlng'])) {
	$modx->log(xPDO::LOG_LEVEL_ERROR, '[Gmarker] '. $modx->lexicon('missing_center'));
	return $modx->lexicon('missing_center');
}

// build query
$criteria = array();
if ($parents) {
	$criteria['modResource.parent:IN'] = $parents;
}
if (!empty($templates)) {
	$criteria['modResource.template:IN'] = $templates;
}
if (empty($showDeleted)) {
    $criteria['deleted'] = '0';
}
if (empty($showUnpublished)) {
    $criteria['published'] = '1';
}
if (empty($showHidden)) {
    $criteria['hidemenu'] = '0';
}
if (!empty($hideContainers)) {
    $criteria['isfolder'] = '0';
}
if (!empty($tv_filters)) {
	$criteria['modResource.id:IN'] = $tv_filters;
}
// If we set tv filters and got no results, we need to clip the query
elseif ($tvName && $tvValue) {
	$criteria = array(); // kill it
	$criteria['modResource.id:IN'] = array('0');
}


$criteria = $modx->newQuery('modResource', $criteria);



// Handle resources that were specifically included, e.g. &resources=`1,2,3`
// and resources that were specifically omitted, e.g. &resources=`-4,-5,-6`
if (!empty($resources)) {
    $resources = explode(',',$resources);
    $include = array();
    $exclude = array();
    foreach ($resources as $resource) {
        $resource = (int)$resource;
        if ($resource == 0) continue;
        if ($resource < 0) {
            $exclude[] = abs($resource);
        } else {
            $include[] = $resource;
        }
    }
    if (!empty($include)) {
        $criteria->where(array('OR:modResource.id:IN' => $include), xPDOQuery::SQL_OR);
    }
    if (!empty($exclude)) {
        $criteria->where(array('modResource.id:NOT IN' => $exclude), xPDOQuery::SQL_AND, null, 1);
    }
}

if (!empty($limit)) {
	$criteria->limit($limit, $offset);
}

//$pages = $modx->getCollectionGraph('modResource', '{"TemplateVarResources":{"TemplateVar":{}}}', $criteria);
$pages = $modx->getCollection('modResource', $criteria);
// Debugging
if ($debug) {
	$criteria->prepare();
	return $criteria->toSQL();
}

// Iterate over markers
$idx = 1;
$letter = 'A';
$props['markers'] = '';
$props['marker_color'] = $marker_color;

foreach ($pages as $p) {
	$prps = $p->toArray();
	$raw_prps = $prps; // we need a version w/o prefixes for Google lookups
	$prps['idx'] = $idx;
	
	// Add lat and lng TVs
	$lat = $p->getTVValue($lat_tv_id);
	
	$lng = $p->getTVValue($lng_tv_id);
	if (mb_check_encoding($lat, 'UTF-8')) {
		$val = json_encode($lat);
		$prps[$tvPrefix.$lat_tv] = $val;
		$raw_prps[$lat_tv] = $val;
	}
	else {
		$modx->log(xPDO::LOG_LEVEL_ERROR, '[Gmarker] '.$modx->lexicon('utf8_error', array('id'=> $p->get('id'),'var'=> $lat_tv)));
	}
	if (mb_check_encoding($lng, 'UTF-8')) {
		$val = json_encode($lng);
		$prps[$tvPrefix.$lng_tv] = $val;
		$raw_prps[$lng_tv] = $val;
	}
	else {
		$modx->log(xPDO::LOG_LEVEL_ERROR, '[Gmarker] '.$modx->lexicon('utf8_error', array('id'=> $p->get('id'),'var'=> $lng_tv)));	
	}
	// ... and optionally any other TVs specified by &tvs
	foreach ($tvs as $t) {
		$prps[$tvPrefix.$t] = $p->getTVValue($t);
		$raw_prps[$t] = $p->getTVValue($t);
	}
	
		
	if (!isset($raw_prps[$lat_tv]) || !isset($raw_prps[$lng_tv])) {
		$modx->log(xPDO::LOG_LEVEL_ERROR, '[Gmarker] '.$modx->lexicon('invalid_resource', array('id'=> $p->get('id'))));
		continue;
	}
	if ($shadow) {
		$prps['shadow'] = '"shadow": pinShadow,';
	}
	else {
		$prps['shadow'] = '"flat":true,';
	}
	if ($drop) {
		$prps['drop'] = '"animation": google.maps.Animation.DROP,';
	}
	else {
		$prps['drop'] = '';
	}
	if ($info) {
		$info_txt = trim($modx->getChunk($infoTpl, $prps));
		if (mb_check_encoding($info_txt, 'UTF-8')) {
			$prps['info'] = "
			var contentString{$idx} = ".json_encode($info_txt).";
			google.maps.event.addListener(marker{$idx}, 'click', function() { 
				infowindow.close();
				infowindow.setContent(contentString{$idx});
				infowindow.open(myMap,marker{$idx}); 
			});
			";
		}
		else {
			$prps['info'] = '';
			$modx->log(xPDO::LOG_LEVEL_ERROR, '[Gmarker] '.$modx->lexicon('invalid_resource', array('id'=> $p->get('id'),'var'=>'Chunk:'.$infoChunk)));
		}
	}
	else {
		$prps['info'] = '';
	}

	// If there are no geocoordinates, optionally look them up
	if (!$suppressLookup && (empty($prps[$lat_tv]) ||  empty($prps[$lng_tv]))) { 
		$uniqid = uniqid();
		$chunk = $modx->newObject('modChunk', array('name' => "{geocoding_tmp}-{$uniqid}"));
		$chunk->setCacheable(false);
		$goog['address'] = $chunk->process($raw_prps, $tpl);
		$goog['bounds'] = $modx->getOption('gmarker.bounds');
		$goog['components'] = $modx->getOption('gmarker.components');
		$goog['region'] = $modx->getOption('gmarker.region');
		$goog['language'] = $modx->getOption('gmarker.language');	
		
		$json = $Gmarker->lookup($goog, $secure);
		
		if(!$p->setTVValue($lat_tv, $Gmarker->get('location.lat'))) {
			$modx->log(xPDO::LOG_LEVEL_ERROR, '[Gmarker] '.$modx->lexicon('problem_saving', array('id'=> $resource->get('id'))));
		}
		if(!$p->setTVValue($lng_tv, $Gmarker->get('location.lng'))) {
			$modx->log(xPDO::LOG_LEVEL_ERROR, '[Gmarker] '.$modx->lexicon('problem_saving', array('id'=> $resource->get('id'))));
		}	
	}


	// Set checkbox group
	$this_group = $p->getTVValue($group);
	$distinct_groups[$this_group] = 1;
	
	$prps['group_json'] = '""';
	if ($group) {
		$group_str = trim($p->getTVValue($group));
		if ($groupCallback) {
			$group_str = $modx->runSnippet($groupCallback,array('group'=>$group_str));
		}
		$prps['group_json'] = json_encode($group_str);
	}
	$prps['marker_color'] = $Gmarker->get_color($p->getTVValue($group),$idx);

	if ($showResults) {
		$prps['marker_center'] = $letter;
		$results .= $modx->getChunk($resultTpl, $prps);	
	}
	else {
		$prps['marker_center'] = $marker_center;
	}
	$props['markers'] .= $modx->getChunk($markerTpl, $prps);
	
	$idx++;
	$letter++;
}

// Get Checkbox Controls
$cb_group = array_keys($distinct_groups);
$checkboxes = '';
if($checkbox == 1 && $group != null) {
	$i = 0;
	foreach ($cb_group as $g ) {
		$props3 = array();
		$props3['group_id'] = 'gmarker_group_'.$i;
		$group_str = trim($g);
		if ($groupCallback) {
			$group_str = $modx->runSnippet($groupCallback,array('group'=>$group_str));

		}
		$props3['group'] = $group_str;
		$props3['group_json'] = json_encode($group_str);
		$props3['marker_color'] = $Gmarker->get_color($g,0);
		$checkboxes .= $modx->getChunk($checkboxTpl, $props3);
		$i++;
	};
}

// Look up the map center
$json = $Gmarker->lookup($goog, $secure);

// Pull the coordinates out of the response
$props['lat'] = number_format($Gmarker->get('location.lat'), 8);
$props['lng'] = number_format($Gmarker->get('location.lng'), 8);

// Add some styling to hide the info-window shadows
$props['hide_shadow'] = '';
if (!$shadow) {
	$props['hide_shadow'] = 'img[src*="iws3.png"] { display: none;}';
}

// Add the stuff to the head
$modx->regClientStartupHTMLBlock($modx->getChunk($headTpl, $props));

$modx->setPlaceholder('gmarker.results',$results);
$modx->setPlaceholder('gmarker.checkboxes',$checkboxes);

return $modx->parseChunk($outTpl, $props);

/*EOF*/
