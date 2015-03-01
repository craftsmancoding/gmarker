<?php include 'header.php'; ?>

    <div class="gmarker_canvas_inner">
        <h2 class="gmarker_cmp_heading" id="gmarker_pagetitle"><?php print $this->modx->lexicon('settings.pagetitle') ?></h2>
    </div>

    <div class="x-panel-body panel-desc x-panel-body-noheader x-panel-body-noborder">



<ul>
    <li><strong><?php print $this->modx->lexicon('setting_gmarker.license_key') ?></strong> : <code><?php print $this->modx->getOption('gmarker.license_key');?></code> <span class="gmarker_success"><?php print $this->modx->lexicon('valid'); ?> &#10004;</span></li>

    <li><strong><?php print $this->modx->lexicon('setting_gmarker.formatting_string'); ?></strong>: <?php print $this->modx->getOption('gmarker.formatting_string'); ?>

    </li>

    <li>
        <strong><?php print $this->modx->lexicon('setting_gmarker.templates'); ?></strong>: <?php print $this->modx->getOption('gmarker.templates'); ?>
        <?php if (empty($this->modx->getOption('gmarker.templates'))): ?>
            <span class="gmarker_danger"><?php print $this->modx->lexicon('required'); ?> &#10007;</span>
        <?php else: ?>
            <span class="gmarker_success"><?php print $this->modx->lexicon('valid'); ?> &#10004;</span>
        <?php endif; ?>
    </li>

    <li>
        <strong><?php print $this->modx->lexicon('setting_gmarker.components'); ?></strong>: <?php print $this->modx->getOption('gmarker.components'); ?>
        <?php if (empty($this->modx->getOption('gmarker.components'))): ?>
            <em>- <?php print $this->modx->lexicon('none'); ?> -</em>
        <?php endif; ?>
    </li>

    <li>
        <strong><?php print $this->modx->lexicon('setting_gmarker.bounds'); ?></strong>: <?php print $this->modx->getOption('gmarker.bounds'); ?>
        <?php print $this->getPlaceholder('xxx'); ?>
        <?php if (empty($this->modx->getOption('gmarker.bounds'))): ?>
            <em>- <?php print $this->modx->lexicon('none'); ?> -</em>
        <?php endif; ?>
    </li>

    <li>
        <strong><?php print $this->modx->lexicon('setting_gmarker.lat_tv'); ?></strong>: <?php print $this->modx->getOption('gmarker.lat_tv'); ?>
        <?php if (empty($this->modx->getOption('gmarker.lat_tv'))): ?>
            <span class="gmarker_danger"><?php print $this->modx->lexicon('required'); ?> &#10007;</span>
        <?php else: ?>
            <span class="gmarker_success"><?php print $this->modx->lexicon('valid'); ?> &#10004;</span>
        <?php endif; ?>
    </li>

    <li><strong><?php print $this->modx->lexicon('setting_gmarker.lng_tv'); ?></strong>: <?php print $this->modx->getOption('gmarker.lng_tv'); ?></li>

    <li><strong><?php print $this->modx->lexicon('setting_gmarker.pin_img_tv'); ?></strong>: <?php print $this->modx->getOption('gmarker.pin_img_tv'); ?></li>

    <li><strong><?php print $this->modx->lexicon('setting_gmarker.secure'); ?></strong>: <?php print $this->modx->getOption('gmarker.secure'); ?></li>

    <li><strong><?php print $this->modx->lexicon('setting_gmarker.apikey'); ?></strong>: <?php print $this->modx->getOption('gmarker.apikey'); ?></li>

    <li><strong><?php print $this->modx->lexicon('setting_gmarker.default_height'); ?></strong>: <?php print $this->modx->getOption('gmarker.default_height'); ?></li>

    <li><strong><?php print $this->modx->lexicon('setting_gmarker.default_width'); ?></strong>: <?php print $this->modx->getOption('gmarker.default_width'); ?></li>

    <li><strong><?php print $this->modx->lexicon('setting_gmarker.style'); ?></strong>: <?php print $this->modx->getOption('gmarker.style'); ?></li>

    <li><strong><?php print $this->modx->lexicon('setting_gmarker.language'); ?></strong>: <?php print $this->modx->getOption('gmarker.language'); ?></li>

    <li><strong><?php print $this->modx->lexicon('setting_gmarker.region'); ?></strong>: <?php print $this->modx->getOption('gmarker.region'); ?></li>

</ul>

        <hr/>









    </div>
<?php include 'footer.php'; ?>