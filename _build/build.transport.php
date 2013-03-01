<?php
/**
 * Gmarker
 * 
 * Copyright 2012 by Everett Griffiths <everett@craftsmancoding.com>
 *
 * This is an Extra for MODX 2.2.x.
 *
 * Gmarker is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Gmarker is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Quip; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * Gmarker build script.  
 *
 * NOTICE TO DEVELOPERS:
 * This extra was built on an installation of MODX where the core directory was
 * inside the public web root.  If your installation of MODX uses a different
 * location, adjust the MODX_CORE_PATH declaration below so this script will
 * know where the MODX core is.
 *
 * The Git directory was installed inside assets/components/gmarkers
 *
 * @package gmarker
 * @subpackage build
 */



// The deets...
define('PKG_NAME', 'Gmarker');
define('PKG_NAME_LOWER', strtolower(PKG_NAME));
define('PKG_VERSION', '1.0');
define('PKG_RELEASE', 'pl');

if (!defined('MODX_CORE_PATH')) {
	define('MODX_CORE_PATH', dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/core/');
}
if (!defined('MODX_CONFIG_KEY')) {
	define('MODX_CONFIG_KEY', 'config');
}

 
// Start the stopwatch...
$mtime = microtime();
$mtime = explode(' ', $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
// Prevent global PHP settings from interrupting
set_time_limit(0); 

echo 'Creating Package...';

// fire up MODX
require_once( MODX_CORE_PATH . 'model/modx/modx.class.php');
$modx = new modx();
$modx->initialize('mgr');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO'); echo '<pre>'; 
flush();

$modx->loadClass('transport.modPackageBuilder', '', false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_NAME, PKG_VERSION, PKG_RELEASE);
$builder->registerNamespace(PKG_NAME_LOWER, false, true, '{core_path}components/' . PKG_NAME_LOWER.'/');

//------------------------------------------------------------------------------
//! Categories
//------------------------------------------------------------------------------
$cat_attributes = array(
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => false,
    xPDOTransport::UNIQUE_KEY => array('category'),
	xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
        'Snippets' => array(
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => 'name',
        ),
        'Chunks' => array (
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => 'name',
        ),
        'Plugins' => array (
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => 'name',
			xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
		        'PluginEvents' => array(
		            xPDOTransport::PRESERVE_KEYS => true,
		            xPDOTransport::UPDATE_OBJECT => false,
		            xPDOTransport::UNIQUE_KEY => array('pluginid','event'),
		        ),
    		),
        ),
    )    
);
    
$Category = $modx->newObject('modCategory');
$Category->set('category', PKG_NAME);

$vehicle = $builder->createVehicle($Category, $cat_attributes);
$builder->putVehicle($vehicle);



//------------------------------------------------------------------------------
//! Snippets
//------------------------------------------------------------------------------
// Not used: see $cat_attributes
$attributes = array(
	xPDOTransport::UNIQUE_KEY => 'name',
	xPDOTransport::PRESERVE_KEYS => false,
	xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => true,	
	xPDOTransport::RELATED_OBJECT_ATTRIBUTES => $cat_attributes
);

$Snippet = $modx->newObject('modSnippet');
$Snippet->fromArray(array(
    'name' => 'Glocation',
    'description' => '<strong>Version '.PKG_VERSION.'-'.PKG_RELEASE.'</strong> lookup latitude and longitude from a given address and set a series of placeholders. The results for any address are returned from cache whenever possible',
    'snippet' => file_get_contents('../core/components/'.PKG_NAME_LOWER.'/elements/snippets/snippet.Glocation.php'),
));
$Category->addMany($Snippet);


$Snippet = $modx->newObject('modSnippet');
$Snippet->fromArray(array(
    'name' => 'Gmap',
    'description' => '<strong>Version '.PKG_VERSION.'-'.PKG_RELEASE.'</strong> Draws a Google Map of the area specified by &address or &latlng.',
    'snippet' => file_get_contents('../core/components/'.PKG_NAME_LOWER.'/elements/snippets/snippet.Gmap.php'),
));
$Category->addMany($Snippet);


$Snippet = $modx->newObject('modSnippet');
$Snippet->fromArray(array(
    'name' => 'Gmarker',
    'description' => '<strong>Version '.PKG_VERSION.'-'.PKG_RELEASE.'</strong> A wrapper around getResources. This draws a Google Map with markers on it, e.g. if your pages contain addresses.',
    'snippet' => file_get_contents('../core/components/'.PKG_NAME_LOWER.'/elements/snippets/snippet.Gmarker.php'),
));
$Category->addMany($Snippet);


//------------------------------------------------------------------------------
//! Chunks
//------------------------------------------------------------------------------
// Not used: see $cat_attributes
$attributes = array(
	xPDOTransport::UNIQUE_KEY => 'name',
	xPDOTransport::PRESERVE_KEYS => false,
	xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => $cat_attributes
);
$Chunk = $modx->newObject('modChunk');
$Chunk->fromArray(array(
    'name' => 'g_out',
    'description' => 'Output wrapper used by the Gmap and Gmarker Snippets.',
    'snippet' => file_get_contents('../core/components/'.PKG_NAME_LOWER.'/elements/chunks/g_out.html'),
));
$Category->addMany($Chunk);

$Chunk = $modx->newObject('modChunk');
$Chunk->fromArray(array(
    'name' => 'gcheckbox',
    'description' => 'Used by the Gmarker Snippet: draws checkboxes that control hiding/showing of markers',
    'snippet' => file_get_contents('../core/components/'.PKG_NAME_LOWER.'/elements/chunks/gcheckbox.html'),
));
$Category->addMany($Chunk);

$Chunk = $modx->newObject('modChunk');
$Chunk->fromArray(array(
    'name' => 'ginfo',
    'description' => 'Used by the Gmarker Snippet: use this Chunk (or a copy of it) to style the info boxes for each marker.',
    'snippet' => file_get_contents('../core/components/'.PKG_NAME_LOWER.'/elements/chunks/ginfo.html'),
));
$Category->addMany($Chunk);

$Chunk = $modx->newObject('modChunk');
$Chunk->fromArray(array(
    'name' => 'gmapshead',
    'description' => 'Used by the Gmap Snippet: this contains Javascript and CSS styling info that controls the output.',
    'snippet' => file_get_contents('../core/components/'.PKG_NAME_LOWER.'/elements/chunks/gmapshead.html'),
));
$Category->addMany($Chunk);

$Chunk = $modx->newObject('modChunk');
$Chunk->fromArray(array(
    'name' => 'gmarker',
    'description' => 'Used by the Gmarker Snippet: this contains Javascript that defines the look of the markers.',
    'snippet' => file_get_contents('../core/components/'.PKG_NAME_LOWER.'/elements/chunks/gmarker.html'),
));
$Category->addMany($Chunk);


$Chunk = $modx->newObject('modChunk');
$Chunk->fromArray(array(
    'name' => 'gmarkershead',
    'description' => 'Used by the Gmarker Snippet: this contains Javascript and CSS styling info that controls the output.',
    'snippet' => file_get_contents('../core/components/'.PKG_NAME_LOWER.'/elements/chunks/gmarkershead.html'),
));
$Category->addMany($Chunk);

$Chunk = $modx->newObject('modChunk');
$Chunk->fromArray(array(
    'name' => 'gresult',
    'description' => 'Used by the Gmarker Snippet: formats a list item corresponding to a marker.',
    'snippet' => file_get_contents('../core/components/'.PKG_NAME_LOWER.'/elements/chunks/gresult.html'),
));
$Category->addMany($Chunk);


//------------------------------------------------------------------------------
//! Plugins
//------------------------------------------------------------------------------
// Not used: see $cat_attributes
$attributes = array(
	xPDOTransport::UNIQUE_KEY => 'name',
	xPDOTransport::PRESERVE_KEYS => false,
	xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
        'PluginEvents' => array(
            xPDOTransport::PRESERVE_KEYS => true,
            xPDOTransport::UPDATE_OBJECT => false,
            xPDOTransport::UNIQUE_KEY => array('pluginid','event'),
        ),
    ),
);
$Events = array();

$Plugin = $modx->newObject('modPlugin');
$Plugin->fromArray(array(
    'name' => 'Geocoding',
    'description' => 'Looks up latitude and longitude coordinates when a page containing location information is saved.',
    'plugincode' => file_get_contents('../core/components/'.PKG_NAME_LOWER.'/elements/plugins/plugin.geocoding.php'),
));

// Plugin Events
$Events['OnDocFormSave'] = $modx->newObject('modPluginEvent');
$Events['OnDocFormSave']->fromArray(array(
    'event' => 'OnDocFormSave',
    'priority' => 0,
    'propertyset' => 0,
),'',true,true);

$Plugin->addMany($Events);

$Category->addMany($Plugin);


//------------------------------------------------------------------------------
//! System Settings
//------------------------------------------------------------------------------
$attributes = array(
	xPDOTransport::UNIQUE_KEY => 'key',
	xPDOTransport::PRESERVE_KEYS => true,
	xPDOTransport::UPDATE_OBJECT => false,	
);

$Setting = $modx->newObject('modSystemSetting');
$Setting->fromArray(array(
    'key' => 'gmarker.formatting_string',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => PKG_NAME_LOWER,
    'area' => 'default',
),'',true,true);

$vehicle = $builder->createVehicle($Setting, $attributes);
$builder->putVehicle($vehicle);

$Setting = $modx->newObject('modSystemSetting');
$Setting->fromArray(array(
    'key' => 'gmarker.templates',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => PKG_NAME_LOWER,
    'area' => 'default',
),'',true,true);

$vehicle = $builder->createVehicle($Setting, $attributes);
$builder->putVehicle($vehicle);

$Setting = $modx->newObject('modSystemSetting');
$Setting->fromArray(array(
    'key' => 'gmarker.components',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => PKG_NAME_LOWER,
    'area' => 'default',
),'',true,true);

$vehicle = $builder->createVehicle($Setting, $attributes);
$builder->putVehicle($vehicle);

$Setting = $modx->newObject('modSystemSetting');
$Setting->fromArray(array(
    'key' => 'gmarker.bounds',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => PKG_NAME_LOWER,
    'area' => 'default',
),'',true,true);

$vehicle = $builder->createVehicle($Setting, $attributes);
$builder->putVehicle($vehicle);

$Setting = $modx->newObject('modSystemSetting');
$Setting->fromArray(array(
    'key' => 'gmarker.lat_tv',
    'value' => 'latitude',
    'xtype' => 'textfield',
    'namespace' => PKG_NAME_LOWER,
    'area' => 'default',
),'',true,true);

$vehicle = $builder->createVehicle($Setting, $attributes);
$builder->putVehicle($vehicle);

$Setting = $modx->newObject('modSystemSetting');
$Setting->fromArray(array(
    'key' => 'gmarker.lng_tv',
    'value' => 'longitude',
    'xtype' => 'textfield',
    'namespace' => PKG_NAME_LOWER,
    'area' => 'default',
),'',true,true);

$vehicle = $builder->createVehicle($Setting, $attributes);
$builder->putVehicle($vehicle);

$Setting = $modx->newObject('modSystemSetting');
$Setting->fromArray(array(
    'key' => 'gmarker.secure',
    'value' => 1,
    'xtype' => 'combo-boolean',
    'namespace' => PKG_NAME_LOWER,
    'area' => 'default',
),'',true,true);

$vehicle = $builder->createVehicle($Setting, $attributes);
$builder->putVehicle($vehicle);

$Setting = $modx->newObject('modSystemSetting');
$Setting->fromArray(array(
    'key' => 'gmarker.apikey',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => PKG_NAME_LOWER,
    'area' => 'default',
),'',true,true);

$vehicle = $builder->createVehicle($Setting, $attributes);
$builder->putVehicle($vehicle);

$Setting = $modx->newObject('modSystemSetting');
$Setting->fromArray(array(
    'key' => 'gmarker.default_height',
    'value' => 300,
    'xtype' => 'textfield',
    'namespace' => PKG_NAME_LOWER,
    'area' => 'default',
),'',true,true);

$vehicle = $builder->createVehicle($Setting, $attributes);
$builder->putVehicle($vehicle);

$Setting = $modx->newObject('modSystemSetting');
$Setting->fromArray(array(
    'key' => 'gmarker.default_width',
    'value' => 500,
    'xtype' => 'textfield',
    'namespace' => PKG_NAME_LOWER,
    'area' => 'default',
),'',true,true);

$vehicle = $builder->createVehicle($Setting, $attributes);
$builder->putVehicle($vehicle);

$Setting = $modx->newObject('modSystemSetting');
$Setting->fromArray(array(
    'key' => 'gmarker.language',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => PKG_NAME_LOWER,
    'area' => 'default',
),'',true,true);

$vehicle = $builder->createVehicle($Setting, $attributes);
$builder->putVehicle($vehicle);

$Setting = $modx->newObject('modSystemSetting');
$Setting->fromArray(array(
    'key' => 'gmarker.region',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => PKG_NAME_LOWER,
    'area' => 'default',
),'',true,true);

$vehicle = $builder->createVehicle($Setting, $attributes);
$builder->putVehicle($vehicle);


//------------------------------------------------------------------------------
//! Files
//------------------------------------------------------------------------------
/*
// Assets
$vehicle->resolve('file', array(
    'source' => MODX_ASSETS_PATH .'components/'.PKG_NAME_LOWER.'/assets/components/'.PKG_NAME_LOWER,
    'target' => "return MODX_ASSETS_PATH . 'components/';",
));
$builder->putVehicle($vehicle);
*/

// Core: TODO - this shouldn't be hard-coded!
$vehicle->resolve('file', array(
    'source' => MODX_ASSETS_PATH .'components/'.PKG_NAME_LOWER.'/core/components/'.PKG_NAME_LOWER,
    'target' => "return MODX_CORE_PATH . 'components/';",
));
$builder->putVehicle($vehicle);


//------------------------------------------------------------------------------
//! DOCS
//------------------------------------------------------------------------------
$builder->setPackageAttributes(array(
    'license' => file_get_contents(MODX_ASSETS_PATH .'components/'.PKG_NAME_LOWER.'/core/components/'.PKG_NAME_LOWER.'/docs/license.txt'),
    'readme' => file_get_contents(MODX_ASSETS_PATH .'components/'.PKG_NAME_LOWER.'/core/components/'.PKG_NAME_LOWER.'/docs/readme.txt'),
    'changelog' => file_get_contents(MODX_ASSETS_PATH .'components/'.PKG_NAME_LOWER.'/core/components/'.PKG_NAME_LOWER.'/docs/changelog.txt'),
//    'setup-options' => array(
//        'source' => MODX_ASSETS_PATH .'components/docs/user.input.html',
//   ),
));


// Add everything we put into the category
$vehicle = $builder->createVehicle($Category, $cat_attributes);
$builder->putVehicle($vehicle);



// Zip up the package
$builder->pack();

echo '<br/>Package complete. Check your '.MODX_CORE_PATH . 'packages/ directory for the newly created package.';
/*EOF*/