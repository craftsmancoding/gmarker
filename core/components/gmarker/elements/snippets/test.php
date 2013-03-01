<?php
/*
Cheap knock off of getResources

parents			Comma-delimited list of ids serving as parents. Use -1 to ignore parents when specifying resources to include. ID of the current resource
resources		Comma-delimited list of ids to include in the results. Prefix an id with a dash to exclude the resource from the result.
hideContainers	0
showHidden		0
showDeleted		0
showUnpublished	0
limit			0 (no limit)
tvPrefix		'' null
markerTpl
headerTpl

*/

$parents = (!empty($parents) || $parents === '0') ? explode(',', $parents) : array($modx->resource->get('id'));
array_walk($parents, 'trim');

$criteria = array("modResource.parent IN (" . implode(',', $parents) . ")");
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
$criteria = $modx->newQuery('modResource', $criteria);


if (!empty($resources)) {
    $resourceConditions = array();
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

if (!empty($limit)) $criteria->limit($limit, $offset);
$pages = $modx->getCollection('modResource', $criteria);

foreach ($pages as $p) {
	// Add all TVs
	$templateVars = $p->getMany('TemplateVars');
	foreach ($templateVars as $tv) {
		$tv_name = $tv->get('name');
		$props[$tvPrefix.$tv_name] = $p->getTVValue($tv_name);
	}
}
/*EOF*/