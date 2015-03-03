<?php include 'header.php'; ?>

    <div class="gmarker_canvas_inner">
        <h2 class="gmarker_cmp_heading" id="gmarker_pagetitle"><?php print $this->modx->lexicon('settings.pagetitle') ?></h2>
    </div>

    <div class="x-panel-body panel-desc x-panel-body-noheader x-panel-body-noborder">

        <script type="text/javascript">
            function gmapInitialize() {

                var myLatlng = new google.maps.LatLng(<?php print $this->getPlaceholder('lat'); ?>, <?php print $this->getPlaceholder('lng'); ?>);
                var mapOptions = {
                    zoom: <?php print $this->getPlaceholder('zoom'); ?>,
                    center: myLatlng
                };

                var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

                var marker = new google.maps.Marker({
                    position: myLatlng,
                    map: map,
                    title: 'Great Success!'
                });

            }

            function gmaploadScript() {
                console.log('Window loaded. Running gmaploadScript()');
                var script = document.createElement('script');
                script.type = 'text/javascript';
                script.src = '//maps.googleapis.com/maps/api/js?key=<?php print $this->getPlaceholder('apikey'); ?>&v=3.exp&' +
                'callback=gmapInitialize';
                document.body.appendChild(script);
            }

            window.onload = gmaploadScript;
        </script>

        <a href="<?php print self::page('index'); ?>" class="gmarker-btn"><?php print $this->modx->lexicon('back'); ?></a>
        <strong><?php print $this->modx->lexicon('status'); ?></strong>:
        <?php if ($this->getPlaceholder('status') != 'OK'): ?>
            <span class="gmarker_danger"><?php print $this->getPlaceholder('status'); ?> &#10007;</span>
        <?php else: ?>
            <span class="gmarker_success"><?php print $this->getPlaceholder('status'); ?> &#10004;</span>
        <?php endif; ?>
        <hr/>

        <h3><?php print $this->getPlaceholder('formatted_address'); ?></h3>
        <div id="map-canvas" style="width:80%; height:300px;"></div>

        <hr/>
        <label for="raw"><?php $this->modx->lexicon('raw_json_response'); ?>:</label><br/>
        <textarea id="raw" rows="20" cols="80"><?php print $this->getPlaceholder('raw'); ?></textarea>




    </div>
<?php include 'footer.php'; ?>