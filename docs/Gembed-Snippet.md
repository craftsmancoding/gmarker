The Gembed Snippet is the easiest way to embed a Google Map on your site.  It uses a simple iframe, so no extra JavaScript is necessary.


##  Parameters

* `$mode` - place, directions, search, view, or streetview. [default=place]


### Place Mode

Place mode displays a map pin at a particular place or address, such as a landmark, business, geographic feature, or town.

* `$q` - defines the place to highlight on the map. 
* attribution_source e.g. Google+Maps+Embed+API
* attribution_web_url e.g. http://www.butchartgardens.com/
* attribution_ios_deep_link_id e.g. comgooglemaps://?daddr=Butchart+Gardens+Victoria+BC



### Directions Mode

Directions mode displays the path between two or more specified points on the map, as well as the distance and travel time.

* origin defines the starting point from which to display directions. The value can be either a place name or address. The string should be URL-escaped, so an address such as "City Hall, New York, NY" should be converted to City+Hall,New+York,NY. (The Google Maps Embed API supports both + and %20 when escaping spaces.)
* destination defines the end point of the directions.
* waypoints - specifies one or more intermediary places to route directions through between the origin and destination. Multiple waypoints can be specified by using the pipe character (|) to separate places (e.g. Berlin,Germany|Paris,France). You can specify up to 20 waypoints.
* mode - defines the method of travel. Options are driving, walking (which prefers pedestrian paths and sidewalks, where available), bicycling (which routes via bike paths and preferred streets where available), transit, or flying. If no mode is specified, the Embed API will show one or more of the most relevant modes for the specified route.
* avoid - tells Google Maps to avoid tolls, ferries and/or highways. Separate multiple values with the pipe character (e.g. avoid=tolls|highways). Note that this doesn't preclude routes that include the restricted feature(s); it simply biases the result to more favorable routes.
* units specifies either metric or imperial units when displaying distances in the results. If units are not specified, the origin country of the query determines the units to use.

Search Mode

* q (required)


### View Mode

View mode returns a map with no markers or directions.

* q is required
* center - defines the center of the map window, and accepts a latitude and a longitude as comma-separated values (-33.8569,151.2152).
* zoom sets the initial zoom level of the map. Accepted values range from 0 (the whole world) to 21 (individual buildings). The upper limit can vary depending on the map data available at the selected location.
* maptype can be either roadmap (the default) or satellite, and defines the type of map tiles to load.
* language defines the language to use for UI elements and for the display of labels on map tiles. Note that this parameter is only supported for some country tiles; if the specific language requested is not supported for the tile set, then the default language for that tileset will be used. By default, visitors will see a map in their own language.
* region defines the appropriate borders and labels to display, based on geo-political sensitivities. Accepts a region code specified as a two-character ccTLD (top-level domain) value.

### Street View Mode

The Google Maps Embed API lets you display Street View images on your site or blog as interactive panoramas. Google Street View provides panoramic views from designated locations throughout its coverage area. User contributed Photospheres, and Street View special collections are also available.

Each Street View panorama provides a full 360-degree view from a single location. Images contain 360 degrees of horizontal view (a full wrap-around) and 180 degrees of vertical view (from straight up to straight down). The streetview mode provides a viewer that renders the resulting panorama as a sphere with a camera at its center. You can manipulate the camera to control the zoom and the orientation of the camera.

Required:

* location accepts a latitude and a longitude as comma-separated values (46.414382,10.013988). The API will display the panorama photographed closest to this location. Because Street View imagery is periodically refreshed, and photographs may be taken from slightly different positions each time, it's possible that your location may snap to a different panorama when imagery is updated.

* pano is a specific panorama ID. If you specify a pano you may also specify a location. The location will be only be used if the API cannot find the panorama ID.

Optional:

* heading indicates the compass heading of the camera in degrees clockwise from North. Accepted values are from -180° to 360&deg.

* pitch specifies the angle, up or down, of the camera. The pitch is specified in degrees from -90° to 90°. Positive values will angle the camera up, while negative values will angle the camera down. The default pitch of 0° is set based on on the position of the camera when the image was captured. Because of this, a pitch of 0° is often, but not always, horizontal. For example, an image taken on a hill will likely exhibit a default pitch that is not horizontal.

* fov determines the horizontal field of view of the image. The field of view is expressed in degrees, with a range of 10° - 100°. It defaults to 90°. When dealing with a fixed-size viewport the field of view is can be considered the zoom level, with smaller numbers indicating a higher level of zoom.

* language defines the language to use for UI elements and labels. By default, visitors will see UI elements in their own language.

* region defines the appropriate borders and labels to display, based on geo-political sensitivities. Accepts a region code specified as a two-character ccTLD (top-level domain) value.

* placeholder - placeholder where to place the output.  If set, output will be written to this placeholder instead of returned with the Snippet output.
* `$outTpl` - Name of chunk containing the map canvas div (correlates with $id) [default=gembed-iframe]


All other parameters will be passed to the `outTpl` Chunk.

* `$center` - Where the map should be centered. This can either be lat,lng coordinates, or an address (what you might type into a Google Maps search).
* `$zoom` - A zoom factor for the map. (Defaults to `gmarker.zoom` System Setting)
* `$type` - Determines the type of map used (see https://developers.google.com/maps/documentation/javascript/maptypes) [default=ROADMAP] [options=["ROADMAP","SATELLITE","HYBRID","TERRAIN"]]
* `$language` - defines the language to use for UI elements and for the display of labels on map tiles.  Defaults to the `gmarker.language` System Setting 
* `$region` - defines the appropriate borders and labels to display, based on geo-political sensitivities. Accepts a region code specified as a two-character ccTLD (top-level domain) value.  Defaults to the System Setting.

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