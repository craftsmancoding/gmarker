<?php
/**
 * Proof of Concept: Info Window for a marker
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

    <script type="text/javascript">
        function initialize() {
            var myLatlng = new google.maps.LatLng(-25.363882,131.044922);
            var mapOptions = {
                zoom: 4,
                center: myLatlng
            }

            map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

            // Add 5 markers to the map at random locations.
            var southWest = new google.maps.LatLng(-31.203405,125.244141);
            var northEast = new google.maps.LatLng(-25.363882,131.044922);
            var bounds = new google.maps.LatLngBounds(southWest,northEast);
            map.fitBounds(bounds);
            var lngSpan = northEast.lng() - southWest.lng();
            var latSpan = northEast.lat() - southWest.lat();
            for (var i = 0; i < 5; i++) {
                var location = new google.maps.LatLng(southWest.lat() + latSpan * Math.random(),
                    southWest.lng() + lngSpan * Math.random());
                var marker = new google.maps.Marker({
                    position: location,
                    map: map
                });
                var j = i + 1;
                marker.setTitle(j.toString());
                attachSecretMessage(marker, i);
            }
        }

        // The five markers show a secret message when clicked
        // but that message is not within the marker's instance data.
        function attachSecretMessage(marker, number) {
            var message = ["This","is","the","secret","message"];
            var infowindow = new google.maps.InfoWindow(
                { content: message[number],
                    size: new google.maps.Size(50,50)
                });
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(map,marker);
            });
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