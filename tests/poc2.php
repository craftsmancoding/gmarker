<?php
/**
 * Proof of Concept: Simple Marker with a tooltip
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

            // Simple Tooltip marker
            var marker = new google.maps.Marker({
                position: myLatlng,
                title:"Hello World!",
                //map: map // <-- you can add the marker to the map here in the marker's options
            });

            // To add the marker to the map, call setMap();
            marker.setMap(map);
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