# Gmarker

Gmarker is an integration of the Google Maps API (v3) for MODX Revolution. This AddOn will let you easily display maps and markers and it will perform geocoding on address information and cache the results.

In includes the following elements:

 * [Gmap Snippet](https://github.com/craftsmancoding/gmarker/wiki/Gmap-Snippet) : use this Snippet to easily draw a simple Google Map.
 * [Geocode Snippet](https://github.com/craftsmancoding/gmarker/wiki/Glocation-Snippet) : this is a utility Snippet, used to geocode submitted forms or to add geocoding functionality to other elements on your page.
 * [Geocoding-Plugin](https://github.com/craftsmancoding/gmarker/wiki/Geocoding-Plugin) : this will automatically look up latitude and longitude coordinates for pages containing location information (address, city, state, etc.) and store the values as TVs on your pages.

See the Wiki for more information: https://github.com/craftsmancoding/gmarker/wiki

Overview video here: https://www.youtube.com/watch?v=dayu6RXZld4

## Required Google APIs:

Head to your [Google Developer Console](https://console.developers.google.com/project) and enable the following 3 APIs:
 
- Google Maps Embed API
- Google Maps JavaScript API v3
- Static Maps API

## Example 

To populate your map with markers, we rely on Ajax requests to other pages.  

On a dedicated page set as JSON Content Type:

````
[[!queryResources? 
    &template=`6` 
    &published=`1` 
    &_view=`json`
    &_select=`id,pagetitle,lat,lng` 
    &_rename=`{"pagetitle":"title"}` 
    &project_type:LIKE=`project_type:get` 
    &project_year=`project_year:get` 
    &project_status=`project_status:get` 
    &project_scale=`project_scale:get` 
    &_debug=`debug:get`
]]
````
 
````
[[!queryResources? &id=`page_id:get` &published=`1` &_tpl=`gmap-tooltip` ]]
````
