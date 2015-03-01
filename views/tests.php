<?php include 'header.php'; ?>

    <div class="gmarker_canvas_inner">
        <h2 class="gmarker_cmp_heading" id="gmarker_pagetitle"><?php print $this->modx->lexicon('gmarker.tests.pagetitle') ?></h2>
    </div>

    <div class="x-panel-body panel-desc x-panel-body-noheader x-panel-body-noborder">
        <?php
        if (!empty($this->getPlaceholder('errors'))):
        ?>
            <div class="gmarker_danger">
                <h3><?php print $this->modx->lexicon('errors'); ?></h3>
                <p><?php print $this->modx->lexicon('error_desc'); ?></p>
                <hr/>
                <ul>
                <?php
                $errors = $this->getPlaceholder('errors',array());
                foreach ($errors as $e):
                ?>
                    <li><?php print $this->modx->lexicon($e.'.error'); ?></li>
                <?php
                endforeach;
                ?>
                </ul>
            </div>
        <?php
        else:
        ?>
            <div class="gmarker_success">
                <h3><?php print $this->modx->lexicon('success'); ?></h3>
                <p><?php print $this->modx->lexicon('success_desc'); ?></p>
                <hr/>
                <ul>
                    <?php
                    $success = $this->getPlaceholder('success',array());
                    foreach ($success as $s):
                        ?>
                        <li><?php print $this->modx->lexicon($s.'.success'); ?></li>
                    <?php
                    endforeach;
                    ?>
                </ul>
            </div>
        <?php
        endif;
        ?>

    </div>
<?php include 'footer.php'; ?>