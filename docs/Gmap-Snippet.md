The Gmap Snippet lets you draw a Google map using flexible address information or latitude and longitude coordinates.

See this [video](http://youtu.be/13gK6jiZ1RI) for a quick overview.

##  Parameters

* `$center` - Where the map should be centered. This can either be lat,lng coordinates, or an address (what you might type into a Google Maps search).
* `$headTpl` - Name of chunk injected into the page <head> containing the JS call to Google Maps API [default=gmap-example]
* `$outTpl` - Name of chunk containing the map canvas div (correlates with $id) [default=gmap-canvas]
* `$height` - height of the map (specify 'px' or '%'). Defaults to gmarker.default_height System Setting
* `$width` - width of the map (specify 'px' or '%'). Defaults to gmarker.default_width System Setting
* `$zoom` - A zoom factor for the map. [default=15]
* `$id` - CSS dom id of the div where the map will be drawn. [default=map-canvas]
* `$class` - the CSS class of the outputted div (identified by &outTpl). Default is empty.
* `$type` - Determines the type of map used (see https://developers.google.com/maps/documentation/javascript/maptypes) [default=ROADMAP] [options=["ROADMAP","SATELLITE","HYBRID","TERRAIN"]]

## Examples


````
[[Gmap? &center=`New York, NY, USA` 
    &height=`300px` 
    &width=`610px`
    &zoom=`12`
]]
````

![Sample Map: New York City](http://maps.googleapis.com/maps/api/staticmap?center=40.712784,-74.005941&size=610x300&sensor=true&zoom=12)

For more flexible layouts, you can specify percentages for the height or width.