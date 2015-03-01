<?php include 'header.php'; ?>

    <div class="modxbackup_canvas_inner">
        <h2 class="modxbackup_cmp_heading" id="modxbackup_pagetitle"><?php print $this->modx->lexicon('license.pagetitle') ?></h2>
    </div>

    <div class="x-panel-body panel-desc x-panel-body-noheader x-panel-body-noborder">
        <?php if ($this->getPlaceholder('error_msg')): ?>
            <div class="modxbackup_danger">
                <h3><?php print $this->modx->lexicon('errors'); ?></h3>
                <p><?php print $this->getPlaceholder('error_msg'); ?></p>

            </div>
        <?php else: ?>
            <div class="modxbackup_success">
                <h3><?php print $this->modx->lexicon('success'); ?></h3>
                Good stuff here.
            </div>
        <?php endif; ?>

    </div>
<?php include 'footer.php'; ?>