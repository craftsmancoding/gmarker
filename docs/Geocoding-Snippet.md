The Geocoding Snippet is a geocoding/reverse-geocoding utility script used to lookup latitude/longitude from a given address or vice-versa. This script can be called either as a Snippet or a Hook (e.g. a Formit or Register hook). See https://github.com/craftsmancoding/gmarker/wiki/Glocation-Hook[Glocation Hook] for how to use this as a hook.

When used as a Snippet, the lookup will set a series of placeholders (e.g. [[+location.lat]]).  When used as a hook, form fields representing address information will be parsed and 2 additional form parameters will be added for the latitude and the longitude.

The results for any address lookup are returned from cache whenever possible to help reduce the load on the Google Geocoding API and its limit of 2,500 geolocation requests per day.  

Each time an address is looked up, the JSON result from Google is cached so that even when multiple pages query the same address, the Google Geocoding API is only queried the first time the address is encountered.

See [https://developers.google.com/maps/documentation/geocoding/](Google's Geocoding documentation) for more information about their service.

## Snippet Parameters

* *address* _usually required_ string.  The address to look up.  
* *latlng* _(optional)_ string. Provide the Snippet with latitude/longitude coordinates (e.g. "40.714224,-73.961452") if you need to conduct reverse geocoding.  This argument will override the &address parameter.
* *prefix* _(optional)_ an optional prefix to use on your placeholders, e.g. to distinguish between multiple instances of this Snippet on a single page.  Default: _none_.
* *refresh* _(optional)_ boolean, default: 0.  When set to 1 (true), the local cache of geocoded results will be ignored and the Google Geocoding API _will be queried_.  It's recommended that you leave this set to 0 so as to avoid maxing out the API usage limits (the latitude and longitude coordinates for any address should never change, after all).
* *sensor* _(optional, default=0)_ whether or not the device being used has a location sensor. Default: false.
* *components* _(optional)_ Causes address results to be restricted to a specific area. Default value is inherited from the *gmarker.components* https://github.com/craftsmancoding/gmarker/wiki/System-Settings[System Setting]. See https://developers.google.com/maps/documentation/geocoding/#ComponentFiltering[Component Filtering].
* *bounds* _(optional)_ optionally you can add soft filters to bias results. See https://developers.google.com/maps/documentation/geocoding/#Viewports[Viewport Biasing]
* *language* _(optional)_ Defaults to the *manager_language* System Setting
* *region* _(optional)_ The region code, specified as a ccTLD ("top-level domain") two-character value.  See https://developers.google.com/maps/documentation/geocoding/#RegionCodes[Region Biasing]

## Placeholders

The Geocoding Snippet will set the following placeholders.  Put these placeholders anywhere on your page containing the Snippet call.

* *[[+formatted_address]]* : The nicely formatted address, e.g. "1600 Amphitheatre Pkwy, Mountain View, CA 94043, USA"
* *[[+location.lat]]* : The pinpoint latitude, e.g. "37.42291810"
* *[[+location.lng]]* : The pinpoint longitude, e.g. "-122.08542120"
* *[[+northeast.lat]]* : The latitude of the northeast boundary, e.g. "37.42426708029149"
* *[[+northeast.lng]]* : The longitude of the northeast boundary, e.g. "-122.0840722197085"
* *[[+southwest.lat]]* : The latitude of the southwest boundary, e.g. "37.42156911970850"
* *[[+southwest.lng]]* : The longitude of the southwest boundary, e.g. "-122.0867701802915"
* *[[+location_type]]* e.g. "ROOFTOP"
* *[[+json]]* : raw JSON result.
* *[[+status]]* e.g. "OK" or "ZERO_RESULTS"

## Snippet Usage Example

### Example 1

This Snippet should always be called uncached.  If the address has been looked up previously, then the results in the geocoding cache will be used so the Geocoding API will not be queried.

```
[[!Geolocation? &address=`1600 Amphitheatre Parkway,Mountain View,CA`]]
```

Then somewhere on your page:

 The address you looked up: [[+formatted_address]]
 The Latitude of this address is: [[+location.lat]]
 The Longitude of this address is: [[+location.lng]]

### Example 2

When you use 2 or more instances of the Snippet on a single page, you'll want to utilize the *&prefix* parameter to keep your placeholders organized.

```
[[!Glocation? 
    &address=`1600 Amphitheatre Parkway,Mountain View,CA` 
    &prefix=`1.`
]]
[[!Glocation? 
    &address=`565 N Clinton Drive, Milwaukee, WI` 
    &prefix=`2.`
]]
```

Then somewhere on your page you would display the information:

```
 Address 1:
 The first address you looked up: [[+1.formatted_address]]
 The Latitude of this address is: [[+1.location.lat]]
 The Longitude of this address is: [[+1.location.lng]]
 
 Address 2:
 The second address you looked up: [[+2.formatted_address]]
 The Latitude of this address is: [[+2.location.lat]]
 The Longitude of this address is: [[+2.location.lng]]
```