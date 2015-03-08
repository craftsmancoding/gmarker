The Gstatic Snippet draws static map images on your pages.  These are useful for mobile applications or any time when your pages cannot be burdened with the extra assets required by the dynamic maps.  


````
https://maps.googleapis.com/maps/api/staticmap?center=Brooklyn+Bridge,New+York,NY&zoom=13&size=600x300&maptype=roadmap
&markers=color:blue%7Clabel:S%7C40.702147,-74.015794&markers=color:green%7Clabel:G%7C40.711614,-74.012318
&markers=color:red%7Clabel:C%7C40.718217,-73.998284
````

##  Parameters

* `$mode` - place, directions, search, view, or streetview. [default=place]

* `$center` - Where the map should be centered. This can either be lat,lng coordinates, or an address (what you might type into a Google Maps search).
* `$zoom` - A zoom factor for the map. (Defaults to `gmarker.zoom` System Setting)
* `$type` - Determines the type of map used (see https://developers.google.com/maps/documentation/javascript/maptypes) [default=ROADMAP] [options=["ROADMAP","SATELLITE","HYBRID","TERRAIN"]]
* `$language` - defines the language to use for UI elements and for the display of labels on map tiles.  Defaults to MODX manager setting. 
* `$region` - defines the appropriate borders and labels to display, based on geo-political sensitivities. Accepts a region code specified as a two-character ccTLD (top-level domain) value.  Defaults to the System Setting.


* `$headTpl` - Name of chunk injected into the page <head> containing the JS call to Google Maps API [default=gmap-example]
* `$outTpl` - Name of chunk containing the map canvas div (correlates with $id) [default=gmap-canvas]
* `$height` - height of the map (specify 'px' or '%'). Defaults to gmarker.default_height System Setting
* `$width` - width of the map (specify 'px' or '%'). Defaults to gmarker.default_width System Setting

* `$id` - CSS dom id of the div where the map will be drawn. [default=map-canvas]
* `$class` - the CSS class of the outputted div (identified by &outTpl). Default is empty.


## Examples


````
[[Gstatic? &center=`New York, NY, USA` 
    &height=`300px` 
    &width=`610px`
    &zoom=`12`
]]
````

![Sample Map: New York City](http://maps.googleapis.com/maps/api/staticmap?center=40.712784,-74.005941&size=610x300&sensor=true&zoom=12)

For more flexible layouts, you can specify percentages for the height or width.

## Markers

You can populate a static map with markers gathered using a query Snippet such as the *queryResources Snippet* (available in the [Query Package](https://github.com/craftsmancoding/query)).