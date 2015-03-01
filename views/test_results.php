<?php if ($this->getPlaceholder('tested_at')): ?>
    <?php if ($this->getPlaceholder('test_result') == 'pass'): ?>
        <span class="modxbackup_success">
            <strong><?php print $this->modx->lexicon('pass'); ?></strong>
            @
            <?php print $this->getPlaceholder('tested_at'); ?>
        </span>
    <?php else: ?>
        <span class="modxbackup_danger">
            <strong><?php print $this->modx->lexicon('fail'); ?></strong>
            @
            <?php print $this->getPlaceholder('tested_at'); ?>
        </span>
    <?php endif; ?>
<?php else: ?>
    <span class="modxbackup_danger"><?php print $this->modx->lexicon('untested'); ?></span>
<?php endif; ?>
