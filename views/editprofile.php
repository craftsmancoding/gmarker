<h1>
    <img src="<?php print $this->config['assets_url'] .'images/'.$this->getPlaceholder('provider'); ?>.png" width="64" height="64" alt="Logo"/>
    <?php print $this->getPlaceholder('name'); ?>
</h1>

<form id="edit_provider_settings">
<?php
// Repopulating on error
\Formbuilder\Form::setValues($this->getPlaceholders());
?>

<?php
print \Formbuilder\Form::hidden('name', $this->getPlaceholder('name'));
?>

    <label for="cnt"><?php print $this->modx->lexicon('cnt'); ?></label>
<?php print \Formbuilder\Form::text('cnt', 1, array('id'=>'cnt')); ?>
<p><?php print $this->modx->lexicon('cnt.desc'); ?></p>


<label for="src"><?php print $this->modx->lexicon('src'); ?></label>
<?php print \Formbuilder\Form::dropdown('src', array(
    'db'=>$this->modx->lexicon('db'),
    'files'=>$this->modx->lexicon('files'),
    'both'=>$this->modx->lexicon('both'),
), 'both', array('id'=>'src')); ?>
<p><?php print $this->modx->lexicon('src.desc'); ?></p>

<strong><?php print $this->modx->lexicon('tested'); ?></strong>:

    <span id="test_results">
        <?php print $this->getPlaceholder('test_results'); ?>
    </span>

    <span class="modxbackup-btn" id="test_profile" onclick="javascript:testProfile('<?php print $this->getPlaceholder('name'); ?>');"><?php print $this->modx->lexicon('test_now'); ?></span>

<hr/>
<h3><?php print $this->modx->lexicon($this->getPlaceholder('provider')); ?></h3>

    <?php include 'providers/'.$this->getPlaceholder('provider').'.php'; ?>
<?php
//$prefix = 'provider_';
//if ($defs = $this->getPlaceholder('defs')):
//    foreach ($defs as $d)
//    {
//        print call_user_func_array(array('\\Formbuilder\\Form', $d['type']), array($prefix.$d['name'], $d['default'], $d['args']));
//    }
//endif;
?>

</form>