<p>Click here to <a href="<?php print $this->getPlaceholder('dropbox_auth_url'); ?>" target="_new">Authorize Dropbox</a></p>
<label for="provider_token"><?php print $this->modx->lexicon('dropbox_authcode'); ?></label>
<?php print \Formbuilder\Form::text('provider_authcode', '', array('id'=>'provider_authcode')); ?>
<p><?php print $this->modx->lexicon('dropbox_authcode_desc'); ?></p>

<?php if($this->getPlaceholder('accessToken')): ?>
    User ID: <?php print $this->getPlaceholder('dropboxUserId'); ?>
    Access Token: <em>***************</em>
<?php endif; ?>


