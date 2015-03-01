<table id="backup_profiles">
    <thead>
    <tr>
        <th><?php print $this->modx->lexicon('provider'); ?></th>
        <th><?php print $this->modx->lexicon('profile_name'); ?></th>
        <th><?php print $this->modx->lexicon('cnt'); ?></th>
        <th><?php print $this->modx->lexicon('src'); ?></th>
        <th><?php print $this->modx->lexicon('tested'); ?></th>
        <th>&nbsp;</th>
    </tr>
    </thead>
    <tbody>
        <?php print $this->getPlaceholder('rows'); ?>
    </tbody>
</table>