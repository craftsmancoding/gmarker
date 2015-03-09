The Gstatic Snippet draws maps as regular static images without dynamic controls.  These are useful for mobile applications or any time when your pages cannot be burdened with the extra assets required by the dynamic maps.  

[More info](https://developers.google.com/maps/documentation/staticmaps/)

````
https://maps.googleapis.com/maps/api/staticmap?center=Brooklyn+Bridge,New+York,NY&zoom=13&size=600x300&maptype=roadmap
&markers=color:blue%7Clabel:S%7C40.702147,-74.015794&markers=color:green%7Clabel:G%7C40.711614,-74.012318
&markers=color:red%7Clabel:C%7C40.718217,-73.998284
````

##  Parameters


* center (required if markers not present) defines the center of the map, equidistant from all edges of the map. This parameter takes a location as either a comma-separated {latitude,longitude} pair (e.g. "40.714728,-73.998672") or a string address (e.g. "city hall, new york, ny") identifying a unique location on the face of the earth. For more information, see Locations below.
* zoom (required if markers not present) defines the zoom level of the map, which determines the magnification level of the map. This parameter takes a numerical value corresponding to the zoom level of the region desired. For more information, see zoom levels below.


size (required) defines the rectangular dimensions of the map image. This parameter takes a string of the form {horizontal_value}x{vertical_value}. For example, 500x400 defines a map 500 pixels wide by 400 pixels high. Maps smaller than 180 pixels in width will display a reduced-size Google logo. This parameter is affected by the scale parameter, described below; the final output size is the product of the size and scale values.
scale (optional) affects the number of pixels that are returned. scale=2 returns twice as many pixels as scale=1 while retaining the same coverage area and level of detail (i.e. the contents of the map don't change). This is useful when developing for high-resolution displays, or when generating a map for printing. The default value is 1. Accepted values are 2 and 4 (4 is only available to Google Maps API for Work customers.) See Scale Values for more information.
format (optional) defines the format of the resulting image. By default, the Static Maps API creates PNG images. There are several possible formats including GIF, JPEG and PNG types. Which format you use depends on how you intend to present the image. JPEG typically provides greater compression, while GIF and PNG provide greater detail. For more information, see Image Formats.
maptype (optional) defines the type of map to construct. There are several possible maptype values, including roadmap, satellite, hybrid, and terrain. For more information, see Static Maps API Maptypes below.
language (optional) defines the language to use for display of labels on map tiles. Note that this parameter is only supported for some country tiles; if the specific language requested is not supported for the tile set, then the default language for that tileset will be used.
region (optional) defines the appropriate borders to display, based on geo-political sensitivities. Accepts a region code specified as a two-character ccTLD ('top-level domain') value.

Feature Parameters

markers (optional) define one or more markers to attach to the image at specified locations. This parameter takes a single marker definition with parameters separated by the pipe character (|). Multiple markers may be placed within the same markers parameter as long as they exhibit the same style; you may add additional markers of differing styles by adding additional markers parameters. Note that if you supply markers for a map, you do not need to specify the (normally required) center and zoom parameters. For more information, see Static Map Markers below.
path (optional) defines a single path of two or more connected points to overlay on the image at specified locations. This parameter takes a string of point definitions separated by the pipe character (|). You may supply additional paths by adding additional path parameters. Note that if you supply a path for a map, you do not need to specify the (normally required) center and zoom parameters. For more information, see Static Map Paths below.
visible (optional) specifies one or more locations that should remain visible on the map, though no markers or other indicators will be displayed. Use this parameter to ensure that certain features or map locations are shown on the static map.
style (optional) defines a custom style to alter the presentation of a specific feature (road, park, etc.) of the map. This parameter takes feature and element arguments identifying the features to select and a set of style operations to apply to that selection. You may supply multiple styles by adding additional style parameters. For more information, see Styled Maps below.


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