<?php include 'header.php'; ?>

    <div class="gmarker_canvas_inner">
        <h2 class="gmarker_cmp_heading" id="gmarker_pagetitle"><?php print $this->modx->lexicon('settings.pagetitle') ?></h2>
    </div>

    <div class="x-panel-body panel-desc x-panel-body-noheader x-panel-body-noborder">
        Welcome to Gmarker System Settings
    </div>


<div class="gmarker_canvas_inner">
<table class="classy-gmarker-mgr">
    <thead>
        <tr>
            <th style="width:40px;">&nbsp</th>
            <th>Setting</th>
            <th>Vaue</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><span class="gmarker-valid">&#10004;</span></td>
            <td><?php print $this->modx->lexicon('setting_gmarker.license_key') ?></td>
            <td><code><?php print $this->modx->getOption('gmarker.license_key');?></code></td>
        </tr>
        <?php
            $settings = $this->getPlaceholder('settings');
            //print_r($settings);
            foreach ($settings as $s => $data):
        ?>
         <tr>
            <td>
             <?php if ($data['status'] == 'error'): ?>
                <span class="gmarker-invalid">&#10007;</span>
            <?php else: ?>
                <span class="gmarker-valid">&#10004;</span>
            <?php endif; ?>

            </td>
            <td><?php print $this->modx->lexicon('setting_'.$s); ?></td>
            <td>
            <?php if ($data['status'] == 'error'): ?>
                <div class="gmarker_danger gmarker-inline-msg gmarker-inline-msg-danger"><?php print $this->modx->lexicon($data['msg']); ?></div>
            <?php else : ?>
                <?php print $this->modx->getOption($s); ?>
            <?php endif; ?>
            
            </td>
        </tr>
         <?php endforeach; ?>
    </tbody>

</table>

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


<form action="<?php print self::page('apitest'); ?>" method="post">
    <label for="address"><?php print $this->modx->lexicon('address'); ?></label><br>
    <input type="text" id="address" name="address" placeholder="123 Main St." value="" size="70"/>
    <input type="submit" class="gmarker-btn  gmarker-primary" value="<?php print $this->modx->lexicon('submit'); ?>" />
    <p class="gmarker-description"><?php print $this->modx->lexicon('api_test'); ?></p>
</form>



</div>



  
<?php include 'footer.php'; ?>