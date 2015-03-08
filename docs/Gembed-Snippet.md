The Gembed Snippet is the easiest way to embed a Google Map on your site.  It uses a simple iframe, so no extra JavaScript is necessary.





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
[[Gembed? &center=`New York, NY, USA` 
    &height=`300px` 
    &width=`610px`
    &zoom=`12`
]]
````

![Sample Map: New York City](http://maps.googleapis.com/maps/api/staticmap?center=40.712784,-74.005941&size=610x300&sensor=true&zoom=12)

For more flexible layouts, you can specify percentages for the height or width.