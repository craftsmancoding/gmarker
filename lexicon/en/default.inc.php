<?php
/**
 * Gmarker
 *
 * Copyright 2012 by Everett Griffiths <everett@craftsmancoding.com>
 *
 * This file is part of Gmarker,  component for MODx Revolution.
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
 * Gmarker; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package gmarker
 */
/**
 * Gmarker English language file
 *
 * @package gmarker
 * @subpackage lexicon
 */
$_lang['gmarker'] = 'Gmarker';
$_lang['gmarker_desc'] = 'Google Maps Geocoding API (v3)';


// License
// License stuff
$_lang['status'] = 'Status';
$_lang['valid'] = 'Valid';
$_lang['invalid'] = 'Invalid';
$_lang['expired'] = 'Expired';

// Warning Boxes
$_lang['warning'] = 'Warning';
$_lang['error'] = 'Error';
$_lang['invalid_expired_msg'] = 'Your license for Gmarker is invalid or expired. <strong>gmarker.license_key</strong> System Setting.  <a href="https://craftsmancoding.com/products/gmarker/">Renew License Key</a>';
$_lang['activation_problem_msg'] = 'There was a problem activating your license.';
$_lang['reqs_license_msg'] = 'Gmarker requires a license key. Paste it into your <strong>gmarker.license_key</strong> System Setting.  <a href="https://craftsmancoding.com/products/gmarker/">Buy License Key Now.</a>';

// Errors
$_lang['node_not_found'] = 'The following node was not found in the JSON: [[+node]]';
$_lang['missing_params'] = 'Missing required parameters.';
$_lang['bad_dimensions'] = 'Map has bad dimensions!  Height and width must be non-zero.';
$_lang['missing_center'] = 'Map center must be defined! Set an address or valid latitude/longitude coordinates.';
$_lang['invalid_dimensions'] = 'Map dimensions must include at least one pixel width. You cannot use percentages for both height and width.';
$_lang['invalid_height'] = '&height parameter must include a unit: either "px" or "&"';
$_lang['invalid_width'] = '&width parameter must include a unit: either "px" or "&"';
$_lang['hook_error'] = 'Hook needs gmaps.lat_tv and gmaps.lng_tv set.';
$_lang['problem_saving'] = 'There was a problem saving the latitude/longitude TVs on page id [[+id]]';
$_lang['invalid_resource'] = 'Resource id [[+id]] is missing required latitude/longitude TVs.';
$_lang['tv_not_found'] = 'TV [[+tv]] not found.';
$_lang['utf8_error'] = 'String for page id [[+id]] "[[+var]]" is not UTF-8 encoded.';


// System Settings
$_lang['setting_gmarker.license_key'] = 'License Key';
$_lang['setting_gmarker.license_key_desc'] = 'Gmarker requires a valid license key. You may purchase one at <a href="https://craftsmancoding.com/products/downloads/gmarker/">https://craftsmancoding.com/products/downloads/gmarker/</a> and enter it here.';

$_lang['setting_gmarker.formatting_string'] = 'Formatting String';
$_lang['setting_gmarker.formatting_string_desc'] = 'Assemble all the Template Variables so that when they are parsed they will contain a valid address that you could conceivably type into a Google Maps search field.';

$_lang['setting_gmarker.templates'] = 'Templates to Geocode';
$_lang['setting_gmarker.templates_desc'] = 'Enter a comma-separated list of template IDs. Pages using these templates should contain address information.  When saving pages of this type, Geocoding lookups will be performed and used to auto-populate dedicated latitude and longitude TVs (see gmaps.lat_tv and gmaps.lng_tv)';

$_lang['setting_gmarker.components'] = 'Geocoding Components';
$_lang['setting_gmarker.components_desc'] = 'Optionally, you can restrict address results to a certain area, e.g. "country:NZL". <a href="https://developers.google.com/maps/documentation/geocoding/#ComponentFiltering">more info</a>';

$_lang['setting_gmarker.bounds'] = 'Geocoding Bounds';
$_lang['setting_gmarker.bounds_desc'] = 'The optional lat|lng bounding box of the viewport within which to bias geocode results more prominently, e.g. "34.172684,-118.604794|34.236144,-118.500938". This parameter will only influence, not fully restrict, results from the geocoder. <a href="https://developers.google.com/maps/documentation/geocoding/#Viewports">more info</a>';

$_lang['setting_gmarker.lat_tv'] = 'Latitude TV';
$_lang['setting_gmarker.lat_tv_desc'] = 'The name of the Template Variable where latitude information will be automatically stored.  All templates listed in the gmaps.templates Setting should have this TV assigned to them.';

$_lang['setting_gmarker.lng_tv'] = 'Longitude TV';
$_lang['setting_gmarker.lng_tv_desc'] = 'The name of the Template Variable where longitude information will be automatically stored. All templates listed in the gmaps.templates Setting should have this TV assigned to them.';

$_lang['setting_gmarker.pin_img_tv'] = 'Pin Image TV';
$_lang['setting_gmarker.pin_img_desc'] = 'Optionally, you can use a custom image for the pin drawn on the map.  Specify the TV name here.';

$_lang['setting_gmarker.secure'] = 'Geocoding Secure';
$_lang['setting_gmarker.secure_desc'] = "This controls the protocol used when accessing the Google Geocoding API.  No = HTTP, Yes = HTTPS. HTTPS is recommended for applications that include sensitive user data, such as a user's location, in requests.";

$_lang['setting_gmarker.apikey'] = 'Google Maps API Key';
$_lang['setting_gmarker.apikey_desc'] = 'Log into <a href="https://code.google.com/apis/console">https://code.google.com/apis/console</a> using your Google account, activate the <strong>Google Maps JavaScript API v3</strong> and the <strong>Static Maps API</strong>, then click on the API Access and copy your key here.  See https://developers.google.com/maps/documentation/javascript/tutorial#api_key for more info.';

$_lang['setting_gmarker.default_height'] = 'Default Height';
$_lang['setting_gmarker.default_height_desc'] = 'The default height (specify px or %) for maps drawn using the Gmap or Gmarker Snippets';

$_lang['setting_gmarker.default_width'] = 'Width';
$_lang['setting_gmarker.default_width_desc'] = 'The default width (specify px or %) for maps drawn using the Gmap or Gmarker Snippets';

$_lang['setting_gmarker.style'] = 'Style';
$_lang['setting_gmarker.style_desc'] = 'Paste a JSON array from <a href="https://snazzymaps.com">https://snazzymaps.com</a> here. Browse Snazzy Maps for hundreds of map themes and styles!';

$_lang['setting_gmarker.language'] = 'Language';
$_lang['setting_gmarker.language_desc'] = 'The language in which to return results. <a href="https://developers.google.com/maps/faq#languagesupport">Available languages</a>';

$_lang['setting_gmarker.region'] = 'Region';
$_lang['setting_gmarker.region_desc'] = 'The region code, specified as a ccTLD ("top-level domain") two-character value. This parameter will only influence, not fully restrict, results from the geocoder. <a href="https://developers.google.com/maps/documentation/geocoding/#RegionCodes">more info</a>';

$_lang['setting_gmarker.pincolor'] = 'Default Pin Color';
$_lang['setting_gmarker.pincolor_desc'] = 'Set a valid CSS color, e.g. FE7569 (do not include the #), to set the default color of your pins.  Used by the Gmarker Snippet.';


/*EOF*/