<?php if ($this->getPlaceholder('info_msg')): ?>
    <div class="modxbackup_info"><?php print $this->getPlaceholder('info_msg'); ?></div>
<?php elseif ($this->getPlaceholder('success_msg')): ?>
    <div class="modxbackup_success"><?php print $this->getPlaceholder('success_msg'); ?></div>
<?php elseif ($this->getPlaceholder('error_msg')): ?>
    <div class="modxbackup_danger"><?php print $this->getPlaceholder('error_msg'); ?></div>
<?php endif; ?>