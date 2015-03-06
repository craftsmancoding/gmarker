# Gmarker

Gmarker v2 is an integration of the [Google Maps API (v3)](https://developers.google.com/maps/) for MODX Revolution which lets you easily display maps with markers on your site.  It geocodes address information and caches the results.

![Sample Map](http://maps.googleapis.com/maps/api/staticmap?center=52.370216,4.895168&size=610x300&sensor=true&zoom=12)

You can style the results using [SnazzyMaps](https://snazzymaps.com) to create beautiful maps that perfectly match the look of your site!

## Components

 * [Geocoding Plugin](Geocoding-Plugin.md)
 * [Gmap Snippet](Gmap-Snippet.md)

## Getting Started

See the page dedicated to https://github.com/craftsmancoding/gmarker/wiki/Getting-Started[Getting Started].

For more information on the relevant Google APIs see 

 * [https://developers.google.com/maps/documentation/](https://developers.google.com/maps/documentation/)
 * [https://developers.google.com/maps/documentation/geocoding/](https://developers.google.com/maps/documentation/geocoding/)

## Features

### GeoCoding Addresses

If your MODX pages contain location information (i.e. address, city, state, zip), then you can use the Gmaps MODX Extra to lookup the latitude and longitude of that location and store it inside Template Variables (i.e. custom fields) on each page.  The Gmaps Extra implements the Google Maps Api (https://developers.google.com/maps/documentation/geocoding/) to geocode address information into latitude/longitude information.

### Easily Draw Maps

The Gmaps Extra includes the [Gmap Snippet](Gmap-Snippet.md), a powerful Snippet that includes many options for displaying exactly the map you want.

### Map Markers

Using Ajax or manual JSON values, the [Gmap Snippet](Gmap-Snippet.md) can draw markers on your maps.