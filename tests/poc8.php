
<!DOCTYPE html>
<html>
<head>
    <title>site design group ltd. - Map Test</title>
    <base href="http://sdg.app:8000/" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <!--
    @name gmap-ajax-infowindows
    @description Queries a remote URL for marker data and info windows. Used by the Gmap Snippet.

    You must pass a valid URL containing JSON data as the &src parameter!
    -->
    <script type="text/javascript">
        var map;

        function gmapInitialize() {
            var myLatlng = new google.maps.LatLng(41.87074300, -87.62471100);
            var mapOptions = {
                zoom: 8,
                center: myLatlng,
                styles: []
            };

            map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

            fillMap();
        }

        function fillMap()
        {
            var filters = {
                project_type: $('#project_type').val(),
                project_year: $('#project_year').val(),
                project_status: $('#project_status').val(),
                project_scale: $('#project_scale').val()
            };

            console.log(filters);

            // Get this via Ajax
            //$.get( "index.php?id=78", function( data ) {
            var jqxhr = $.ajax({
                url:"index.php?id=78",
                type:'GET',
                data:filters
            }).done(function(data) {


                console.log('Data loaded from src page');

                // Reuse a single object throughout
                var infowindow = new google.maps.InfoWindow();
                var markers = [];
                for (var i =0; i < data.results.length; i++)
                {
                    //console.log(data.results[i].title +' Latitude: '+ data.results[i].lat + ' Longitude: '+data.results[i].lng);

                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(data.results[i].lat,data.results[i].lng),
                        title: data.results[i].title,
                        icon: {
                            path: google.maps.SymbolPath.CIRCLE,
                            scale: 7,
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

        function gmaploadScript() {
            var script = document.createElement('script');
            script.type = 'text/javascript';
            script.src = '//maps.googleapis.com/maps/api/js?key=&v=3.exp&' +
            'callback=gmapInitialize';
            document.body.appendChild(script);
        }

        window.onload = gmaploadScript;
    </script>

</head>
<body>

<!--
@name gmap-canvas
@description Output wrapper used by the Gmarker Snippets.
-->
<div id="map-canvas" class="" style="width:100%; height:300px;"></div>

<hr/>
<style>
    .active_filter {
        color:black;
        font-weight: bold;
    }
    .inactive_filter {
        color: grey;
    }
</style>
<script>
    /**
     * @param filter (string) name of the filter being changed
     * @param val (string) new value to filter on
     * @param this (obj) the control that was clicked.
     */
    function selectFilter(filter,val,caller)
    {
        //console.log($(this)[0].value); // trying to avoid having to pass in the val redundantly
        $('#'+filter).val(val);
        // We can't use removeClass/addClass because they all get gummed up
        $(caller).parent().children().attr("class", "inactive_filter");
        $(caller).attr('class','active_filter');
        // Redo Map
        fillMap();
    }
</script>
<h3>Project Type</h3>

<!--
Universities and higher education||Streetscapes||Parks and playgrounds||Senior Housing||Master planning||Multi-family residential||Civic and institutional||Healthcare||Transit||Green roofs and roof decks||Urban design||Pre-K-12 education||Affordable housing||Commercial, retail, and mixed-use
-->
<ul>
    <li onclick="javascript:selectFilter('project_type','Universities and higher education', this);" class="inactive_filter">Universities and higher education</li>
    <li onclick="javascript:selectFilter('project_type','Streetscapes', this);" class="inactive_filter">Streetscapes</li>
</ul>

<h3>Year</h3>
<ul>
    <li onclick="javascript:selectFilter('project_year','2011', this);" class="inactive_filter">2011</li>
    <li onclick="javascript:selectFilter('project_year','2012', this);" class="inactive_filter">2012</li>
    <li onclick="javascript:selectFilter('project_year','2013', this);" class="inactive_filter">2013</li>
</ul>

<h3>Status</h3>

<ul>
    <li onclick="javascript:selectFilter('project_status','Built', this);" class="inactive_filter">Built</li>
    <li onclick="javascript:selectFilter('project_status','Unbuilt', this);" class="inactive_filter">Unbuilt</li>
    <li onclick="javascript:selectFilter('project_status','Design', this);" class="inactive_filter">Design</li>
    <li onclick="javascript:selectFilter('project_status','Construction', this);" class="inactive_filter">Construction</li>

</ul>


<h3>Scale</h3>
<!-- <2000 SF ||2000 SF – 10,000 SF ||10,000 SF – 1 acre ||1 acre – 5 acres||>5 acres -->
<ul>
    <li onclick="javascript:selectFilter('project_scale','<2000 SF', this);" class="inactive_filter">&lt;2000 SF</li>
    <li onclick="javascript:selectFilter('project_scale','2000 SF – 10,000 SF', this);" class="inactive_filter">2000 SF – 10,000 SF</li>
    <li onclick="javascript:selectFilter('project_scale','10,000 SF – 1 acre', this);" class="inactive_filter">10,000 SF – 1 acre</li>
    <li onclick="javascript:selectFilter('project_scale','1 acre – 5 acres', this);" class="inactive_filter">1 acre – 5 acres</li>
    <li onclick="javascript:selectFilter('project_scale','>5 acres', this);" class="inactive_filter">&gt;5 acres</li>
</ul>

<form id="map_filters">
    <input type="text" id="project_type" name="project_type" value=""/>
    <input type="text" id="project_year" name="project_year" value=""/>
    <input type="text" id="project_status" name="project_status" value=""/>
    <input type="text" id="project_scale" name="project_scale" value=""/>
</form>
</body>
</html>