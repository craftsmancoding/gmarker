<?php include 'header.php'; ?>

    <div class="gmarker_canvas_inner">
        <h2 class="gmarker_cmp_heading" id="gmarker_pagetitle"><?php print $this->modx->lexicon('license.pagetitle') ?></h2>
    </div>

  
        <?php if ($this->getPlaceholder('error_msg')): ?>
            <div class="gmarker_danger">
                <h3><?php print $this->modx->lexicon('error'); ?></h3>
                <p><?php print $this->getPlaceholder('error_msg'); ?></p>

            </div>
        <?php else: ?>
            <div class="gmarker_success">
                <h3><?php print $this->modx->lexicon('success'); ?></h3>
                Your license is valid.
            </div>
        <?php endif; ?>

<?php include 'footer.php'; ?>