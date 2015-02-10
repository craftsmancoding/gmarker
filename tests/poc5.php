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
            var myLatlng = new google.maps.LatLng(-33.9, 151.2);
            var mapOptions = {
                zoom: 10,
                center: myLatlng,
                styles: [{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#d3d3d3"}]},{"featureType":"transit","stylers":[{"color":"#808080"},{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"visibility":"on"},{"color":"#b3b3b3"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"weight":1.8}]},{"featureType":"road.local","elementType":"geometry.stroke","stylers":[{"color":"#d7d7d7"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#ebebeb"}]},{"featureType":"administrative","elementType":"geometry","stylers":[{"color":"#a7a7a7"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#efefef"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#696969"}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"visibility":"on"},{"color":"#737373"}]},{"featureType":"poi","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#d6d6d6"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"color":"#dadada"}]}]
            };

            var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

            // Get this via Ajax
            var data = [
                {title:'Bondi Beach', lat:-33.890542, lng:151.274856, info:'<strong>Bondi Beach</strong> is Beautiful!'},
                {title:'Coogee Beach', lat:-33.923036, lng:151.259052, info:'<strong>Coogee Beach</strong> is Coo-tastic!'},
                {title:'Cronulla Beach', lat:-34.028249, lng:151.157507, info:'<strong>Cronulla Beach</strong> is Contageous!'},
                {title:'Manly Beach', lat:-33.80010128657071, lng:151.28747820854187, info:'<strong>Manly Beach</strong> is Super manly!'},
                {title:'Maroubra Beach', lat:-33.950198, lng:151.259302, info:'<strong>Maroubra Beach</strong> is Mesmorizing!'}
            ];
            //var markers = [];
            // Reuse a single object throughout
            var infowindow = new google.maps.InfoWindow();

            for (var i =0; i < data.length; i++)
            {
                console.log(data[i].title +' Latitude: '+ data[i].lat + ' Longitude: '+data[i].lng);
//                marker.setMap(map);
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(data[i].lat,data[i].lng),
                    map: map,
                    title: data[i].title,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 10,
                        strokeColor: 'orange',
                        fillColor: 'orange',
                        fillOpacity: 1
                    }
                });


                // This doesn't work in-line for some reason -- the data var comes through as undefined (?)
//                google.maps.event.addListener(marker, 'click', function() {
//                    console.log('Clicked on marker: '+i)
//                    infowindow.close(); // close existing
//                    infowindow.setContent(data[i].title);
//                    infowindow.open(map,marker);
//                });
                // So instead we spin it off in its own function
                // See https://developers.google.com/maps/documentation/javascript/events
                attachInfo(marker, data[i].info, i);
            }

            /**
             * We use a separate function here to help isolate functionality and to help scope variables
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

//            google.maps.event.addListener(marker, 'click', function() {
//                var infowindow = new google.maps.InfoWindow({
//                    content: contentString
//                });
//                infowindow.open(map,marker);
//            });
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