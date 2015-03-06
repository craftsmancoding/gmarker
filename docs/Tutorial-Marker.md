# Map Markers

![Sample Map](https://maps.googleapis.com/maps/api/staticmap?center=36.774985,-76.197939&zoom=12&size=600x400&markers=color:red%7Clabel:X%7C36.744985,-76.167939)

Markers are added to your maps via JavaScript. You can use the included `gmap-marker` Chunk for the `&headTpl` argument.  The JavaScript in that Chunk will cause a marker to be drawn on your maps.

````
[[!Gmap? 
    &center=`888 S Michigan Avenue, Chicago, IL 60605` 
    &headTpl=`gmap-marker`
]]
````

Inspect the JavaScript in the `gmap-marker`: the `marker` variable specifies which map variable it should be drawn on.

````
<script type="text/javascript">
    function gmapInitialize() {
        console.log('Initializing Google Maps centered at Lat: %s Lng: %s', [[+lat]], [[+lng]]);
        var myLatlng = new google.maps.LatLng([[+lat]], [[+lng]]);
        var mapOptions = {
            zoom: [[+zoom]],
            center: myLatlng
        };

        var map = new google.maps.Map(document.getElementById('[[+id]]'), mapOptions);

        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            title: ''
        });
    }

    function gmaploadScript() {
        console.log('Window loaded. Running gmaploadScript()');
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = '//maps.googleapis.com/maps/api/js?key=[[++gmarker.apikey]]&v=3.exp&' +
        'callback=gmapInitialize';
        document.body.appendChild(script);
    }

    window.onload = gmaploadScript;
</script>
````

By studying the above example, you should notice that only the basic things are set by the `Gmap` Snippet (latitude, longitude, zoom, and css id).  The marker itself is handled entirely by JavaScript.  This is intentional: PHP Snippets get really messy when they attempt to generate complex JavaScript.

If you want to customize your maps with markers, you should get familiar with the JavaScript examples in Gmarker's Chunks.  Once you understand how to customize a single marker, you will better understand how to customize multiple markers.

## Custom Image 1

You can use a custom image for your markers by setting the `icon` key.  Update the sample code so that the `marker` variable uses something like the following:

````
var marker = new google.maps.Marker({
    position: myLatlng,
    map: map,
    title: 'My Example',
    icon: '[[++assets_url]]images/flag.png'
});
````

If you are hosting your images locally, make _sure_ you use the `[[++assets_url]]` placeholder: this will ensure that the URL can be customized (e.g. to leverage a CDN).  Many designers and developers forget that the location of your MODX web assets is a configurable setting.  Its URL should _never_ be hardcoded.

## Custom Image 2

The `icon` attribute can be more than a simple image path.  You can invoke vector shapes by supplying a full array of options, including the useful `SymbolPath` object.

````
var marker = new google.maps.Marker({
	position: myLatlng,
	title: 'Example with Vecors',
	icon: {
		path: google.maps.SymbolPath.CIRCLE,
		scale: 7,
		strokeColor: 'orange',
		fillColor: 'orange',
		fillOpacity: 1
	}
});
````

See [Google's Docs](https://developers.google.com/maps/documentation/javascript/reference#SymbolPath) for more info on using Symbols for your icons.

## Other Attributes

You can do other things with your markers, including make them draggable.  Just add the following key/value to your `marker` options:

````
draggable:true
````

Animate your marker by adding the `animation` attribute:


````
animation: google.maps.Animation.DROP
````

## See Also

* [Google Maps Simple Marker](https://developers.google.com/maps/documentation/javascript/examples/marker-simple)
* [Custom Marker Icons](https://developers.google.com/maps/documentation/javascript/markers#icons)
* [Symbol Icons](https://developers.google.com/maps/documentation/javascript/reference#SymbolPath)
* [Google Maps - Markers](https://developers.google.com/maps/documentation/javascript/markers)