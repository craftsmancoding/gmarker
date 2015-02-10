<?php
/**
 * Proof of Concept
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

    <script type="text/javascript">
        function initialize() {
            /**
             * There are two required options for every map: center and zoom.
             * @type {{zoom: number, center: google.maps.LatLng}}
             */
            var mapOptions = {
                zoom: 8,
                center: new google.maps.LatLng(-34.397, 150.644)
                // Also acceptable is:
                // center: {lat: -34.397, lng: 150.644}
            };

            var map = new google.maps.Map(document.getElementById('map-canvas'),
                mapOptions);
        }

        function loadScript() {
            var script = document.createElement('script');
            script.type = 'text/javascript';
            script.src = 'https://maps.googleapis.com/maps/api/js?key=<?php print API_KEY; ?>&v=3.exp&' +
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