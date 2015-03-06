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