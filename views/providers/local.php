<label for="provider_base_path"><?php print $this->modx->lexicon('base_path'); ?></label>
<?php print \Formbuilder\Form::text('provider_base_path', '', array('id'=>'provider_base_path')); ?>
<p><?php print $this->modx->lexicon('local_base_dir_desc'); ?></p>