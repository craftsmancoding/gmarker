<tr>
    <td>
        <img src="<?php print $this->getPlaceholder('icon'); ?>" width="32" width="32" alt="<?php print $this->getPlaceholder('provider'); ?>" />
    </td>
    <td>
        <?php print $this->getPlaceholder('name'); ?>
    </td>
    <td>
        <?php print $this->getPlaceholder('cnt'); ?>
    </td>
    <td>
        <?php print $this->modx->lexicon($this->getPlaceholder('src')); ?>
    </td>
    <td>
        <?php print $this->getPlaceholder('tested_at'); ?>
        <?php if ($this->getPlaceholder('test_result') == 'pass'): ?>
            <span class="modxbackup_success"><?php print $this->modx->lexicon('pass'); ?></span>
        <?php elseif ($this->getPlaceholder('test_result') == 'fail'): ?>
            <span class="modxbackup_danger"><?php print $this->modx->lexicon('fail'); ?></span>
        <?php endif; ?>
    </td>
    <td>
        <span class="modxbackup-btn" onclick="javascript:editProfile('<?php print $this->getPlaceholder('name'); ?>');">
            <?php print $this->modx->lexicon('edit'); ?>
        </span>
    </td>
</tr>