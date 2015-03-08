The Gmarker extra includes the following MODX System Settings

## gmarker.apikey

WARNING: Although the API key is not always required, it is strongly recommended.  Without an API key, your access is limited and requests may pegged to your IP address: this can be problematic if you are on a shared server.

To get your Google Maps API key, log into https://code.google.com/apis/console using your Google account, activate the **Google Maps API v3** and the **Static Maps API**, then click on the API Access and copy your key here.  

[More info...](https://developers.google.com/maps/documentation/javascript/tutorial#api_key)


## gmarker.formatting_string

Assemble placeholders corresponding to available the default page variables or Template Variables so that when they are parsed they will contain a valid address that you could conceivably type into a Google Maps search field.  For example, if your location pages contain TVs named "address", "city", and "state", then your formatting string might look like this:

```
[[+address]], [[+city]], [[+state]]
```

If you store an entire address in a single TV named "fulladdress", then your formatting string would look like this:

```
[[+fulladdress]]
```

Built-in page fields are also supported, e.g.

```
[[+pagetitle]],[[+alias]]
```

All placeholders should use the "+" notation used for Chunks (do not use the "*" notation).

Affects: [Geocoding Plugin](Geocoding-Plugin.md)

## gmarker.templates

Geocoding will be conducted when saving any page that uses one of these templates.  Include a comma-separated list of template ids, e.g. 

    1,2,5

WARNING: If empty, no automatic geocoding will take place.  

Make sure that the TVs named by *gmarker.lat_tv* and *gmarker.lng_tv* have been assigned to these templates.

Affects: [Geocoding Plugin](Geocoding-Plugin.md)

## gmarker.components

Used for Geocoding, you can pass additional hard filters to the Google Maps query to force the lookup to return address results restricted to a specific area.

The components that can be filtered include:

* *route* matches long or short name of a route.
* *locality* matches against both locality and sublocality types.
* *administrative_area* matches all the administrative_area levels.
* *postal_code* matches postal_code and postal_code_prefix.
* *country* matches a country name or a two letter http://en.wikipedia.org/wiki/ISO_3166-1[ISO 3166-1] country code.

For example, to restrict Geocoding to results within New Zealand, you might do something like this:

````
country:NZL
````

[More info...](https://developers.google.com/maps/documentation/geocoding/#ComponentFiltering)

Affects: [Geocoding Plugin](Geocoding-Plugin.md)

## gmarker.bounds

Used for Geocoding, you can pass additional soft filters to the Google Maps query so you can weight results that are in a given geographical boundary, limited by a latitude/longitude rectangle.  
The gmarker.bounds parameter defines the latitude/longitude coordinates of the southwest and northeast corners of this bounding box using a pipe (|) character to separate the coordinates, e.g. 

```
34.172684,-118.604794|34.236144,-118.500938
```

[More info...](https://developers.google.com/maps/documentation/geocoding/index#Viewports)

Affects: [Geocoding Plugin](Geocoding-Plugin.md)

## gmarker.lat_tv

This is the name of a Template Variable where Gmarker will store latitude information. E.g.

```
longitude_tv
```

Affects: [Geocoding Plugin](Geocoding-Plugin.md)

## gmarker.lng_tv

This is the name of a Template Variable where Gmarker will store longitude information. E.g.

 latitude_tv

Affects: [Geocoding Plugin](Geocoding-Plugin.md)

## gmarker.zoom

Zoom setting.  Default: 8.

## gmarker.secure

The default here is "Yes": the Google Maps API will be accessed using an HTTPS connection.

If secure is "No", the standard HTTP URLs are used:

```
http://maps.googleapis.com/maps/api/geocode/output
```

If secure is "Yes", the HTTPS URL is used for lookups:

```
https://maps.googleapis.com/maps/api/geocode/output
```

Affects: [Geocoding Plugin](Geocoding-Plugin.md)


## gmarker.default_height

The default height of any map.  The value should specify either `px` or `%` as the unit, e.g. `500px` or `50%`.  This is used if `&height` is not specified in the Snippet call.

Affects: [Gmap Snippet](Gmap-Snippet.md)

## gmarker.default_width

The default width of any map.  The value should specify either `px` or `%` as the unit, e.g. `500px` or `50%`.  This is used if `&width` is not specified in the Snippet call.

Affects: [Gmap Snippet](Gmap-Snippet.md)