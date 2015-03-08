# Gmarker

Gmarker v2 is an integration of the [Google Maps API (v3)](https://developers.google.com/maps/) for MODX Revolution which lets you display several types maps on your site.  This includes the simple embedded maps, static maps, and the more fully featured dynamic maps, as well as utilities to geocodes address information stored in your MODX pages.

![Sample Map](http://maps.googleapis.com/maps/api/staticmap?center=52.370216,4.895168&size=610x300&sensor=true&zoom=12)

You can style the results using [SnazzyMaps](https://snazzymaps.com) to create beautiful maps that perfectly match the look of your site!

## Components

* [Gembed Snippet](Gembed-Snippet.md) : Embed simple maps using an iframe.
* [Gmap Snippet](Gmap-Snippet.md) : Draw dynamic maps with markers, traffic, or other extras.
* [Gstatic Snippet](Gstatic-Snippet) : Draw static maps on your site.
* [Geocoding Plugin](Geocoding-Plugin.md) : Performs lat/lng lookups on address info in your pages.

## Getting Started

See the page dedicated to https://github.com/craftsmancoding/gmarker/wiki/Getting-Started[Getting Started].

For more information on the relevant Google APIs see 

 * [https://developers.google.com/maps/documentation/](https://developers.google.com/maps/documentation/)
 * [https://developers.google.com/maps/documentation/geocoding/](https://developers.google.com/maps/documentation/geocoding/)

## Features

### GeoCoding Addresses

If your MODX pages contain location information (i.e. address, city, state, zip), then you can configure the [Geocoding Plugin](Geocoding-Plugin.md) to lookup the latitude and longitude coordinates and store them inside Template Variables (i.e. as custom fields). The [Gmap Snippet](Gmap-Snippet.md) can utilize these coordinates to draw markers or perform "store locater" or other searches.  See the *Tutorial Pages* for examples.

### Easily Draw Maps

The easiest way to include a map on your site is to embed it using the [Gembed Snippet](Gembed-Snippet.md).

````
[[Gembed? &address=`415 Market St, San Francisco, CA`]]
````

### Dynamic Maps

If you need more features on your maps, you can leverage the power of the [Gmap Snippet](Gmap-Snippet.md) to include markers, info windows, traffic layers, bicycling paths, and KML or other data layers.

Using Ajax or manual JSON values, the [Gmap Snippet](Gmap-Snippet.md) can draw markers on your maps can power location searches.

## Static Maps

For speed and simplicity, you can use the [Gstatic Snippet](Gmap-Static.md) to draw static map images.