<?php include 'header.php'; ?>

    <div class="gmarker_canvas_inner">
        <h2 class="gmarker_cmp_heading" id="gmarker_pagetitle"><?php print $this->modx->lexicon('settings.pagetitle') ?></h2>
    </div>

    <div class="x-panel-body panel-desc x-panel-body-noheader x-panel-body-noborder">

        <?php
        //print_r($this->getPlaceholder('gmarker.templates'));
        ?>


<ul>
    <li>
        <strong><?php print $this->modx->lexicon('setting_gmarker.license_key') ?></strong> : <code><?php print $this->modx->getOption('gmarker.license_key');?></code> <span class="gmarker_success"><?php print $this->modx->lexicon('valid'); ?> &#10004;</span>
    </li>
    <?php
    $settings = $this->getPlaceholder('settings');
    //print_r($settings);
    foreach ($settings as $s => $data):
    ?>
        <li>
            <strong><?php print $this->modx->lexicon('setting_'.$s); ?></strong>: <?php print $this->modx->getOption($s); ?>
            <?php if ($data['status'] == 'error'): ?>
                <span class="gmarker_danger"><?php print $this->modx->lexicon($data['msg']); ?> &#10007;</span>
            <?php else: ?>
                <span class="gmarker_success"><?php print $this->modx->lexicon($data['msg']); ?> &#10004;</span>
            <?php endif; ?>
        </li>
    <?php
    endforeach;
    ?>
</ul>
<hr/>
<h2><?php print $this->modx->lexicon('advanced_settings'); ?></h2>
<ul>
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

    <li><strong><?php print $this->modx->lexicon('setting_gmarker.secure'); ?></strong>: <?php print $this->modx->getOption('gmarker.secure'); ?></li>


    <li><strong><?php print $this->modx->lexicon('setting_gmarker.language'); ?></strong>: <?php print $this->modx->getOption('gmarker.language'); ?></li>

    <li><strong><?php print $this->modx->lexicon('setting_gmarker.region'); ?></strong>: <?php print $this->modx->getOption('gmarker.region'); ?></li>

</ul>

<hr/>
<h2>API Test</h2>

<p><?php print $this->modx->lexicon('api_test'); ?></p>
<form action="<?php print self::page('apitest'); ?>" method="post">
    <label for="address"><?php print $this->modx->lexicon('address'); ?></label>
    <input type="text" id="address" name="address" placeholder="123 Main St." value="" size="70"/>
    <input type="submit" value="<?php print $this->modx->lexicon('submit'); ?>" />
</form>








    </div>
<?php include 'footer.php'; ?>