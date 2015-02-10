<?php
/**
 * Proof of Concept: Simple Marker with a custom image.
 * Note: this image looks idiotic if it's too big. It should also be a PNG with transparency.
 * See https://developers.google.com/maps/documentation/javascript/tutorial
 */
include '.env.php';
?>
<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
        html, body, #map-canvas { height: 100%; margin: 0; padding: 0;}
    </style>
    <script type="text/javascript"
            src="//maps.googleapis.com/maps/api/js?key=<?php print API_KEY; ?>">
    </script>
    <script type="text/javascript">
        function initialize() {
            var myLatlng = new google.maps.LatLng(-25.363882,131.044922);

            /**
             * There are two required options for every map: center and zoom.
             * @type {{zoom: number, center: google.maps.LatLng}}
             */
            var mapOptions = {
                zoom: 4,
                center: myLatlng
            }
            var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

            var image = 'images/marker.png';
            var myLatLng = new google.maps.LatLng(-33.890542, 151.274856);
            var beachMarker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                icon: image
            });

            // If you did not set the "map" property in your marker options, call setMap() to add the marker to the map:
            // beachMarker.setMap(map);
        }

        function loadScript() {
            var script = document.createElement('script');
            script.type = 'text/javascript';
            script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&' +
            'callback=initialize';
            document.body.appendChild(script);
        }

        window.onload = loadScript;
    </script>
</head>
<body>
<?php
/**
 * You can specify the hieght/width etc of your map div here.
 */
?>
<div id="map-canvas" style="width: 100%; height: 100%"></div>
</body>
</html>