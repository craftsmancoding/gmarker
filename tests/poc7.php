<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
        html, body, #map-canvas { height: 100%; margin: 0; padding: 0;}
    </style>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript">
        function initialize() {
            var myLatlng = new google.maps.LatLng(-33.9, 151.2);
            var mapOptions = {
                zoom: 10,
                center: myLatlng,
                styles: [[++gmarker.style]]
            };

            var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

            // Get this via Ajax
            $.get( "[[~78]]", function( data ) {
                //$( ".result" ).html( data );
                //alert( "Load was performed." );
                console.log(data);
                // Reuse a single object throughout
                var infowindow = new google.maps.InfoWindow();
                var markers = [];
                for (var i =0; i < data.results.length; i++)
                {
                    console.log(data.results[i].title +' Latitude: '+ data.results[i].lat + ' Longitude: '+data.results[i].lng);
//                marker.setMap(map);
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(data.results[i].lat,data.results[i].lng),
                        //map: map,
                        title: data.results[i].title,
                        icon: {
                            path: google.maps.SymbolPath.CIRCLE,
                            scale: 10,
                            strokeColor: 'orange',
                            fillColor: 'orange',
                            fillOpacity: 1
                        }
                    });

                    attachInfo(marker, data.results[i].content, i);
                    markers.push(marker);

                }

                drawMarkersOnMap(markers, map);

                /**
                 * We use a separate function here to help isolate functionality and to help scope variables
                 * Declared within the initialize() function so we have access to the infowindow var.
                 * @param marker
                 * @param content
                 * @param i
                 */
                function attachInfo(marker, content, i) {
                    google.maps.event.addListener(marker, 'click', function() {
                        console.log('Clicked on marker: '+i)
                        infowindow.close(); // close existing
                        infowindow.setContent(content);
                        infowindow.open(map,marker);
                    });
                }
            });

        }

        function drawMarkersOnMap(markers, map)
        {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
        }

        function loadScript() {
            var script = document.createElement('script');
            script.type = 'text/javascript';
            script.src = 'https://maps.googleapis.com/maps/api/js?key=[[++gmarker.apikey]]&v=3.exp&' +
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
<div id="map-canvas" style="width: 100%; height: 80%"></div>
<span onclick="javascript:initialize();">Redraw</span>
</body>
</html>