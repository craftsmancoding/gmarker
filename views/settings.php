<script type="text/javascript">

    Ext.onReady(function(){
        listProfiles();
    });

    function listProfiles()
    {
        console.log("listProfiles -- Loading external page: .<?php print $this->page('listprofiles'); ?>");
        Ext.get('backup_profiles_container').load('<?php print $this->page('listprofiles'); ?>');
    }

    /**
     * Test the given profile
     */
    function testProfile(name)
    {
        console.log('testProfile: '+name);
        Ext.Ajax.request({
            url: <?php print json_encode($this->page('testprofile')); ?>,
            params: {
                name: name
            },
            success: function(response, opts){
                //console.log('success', response, opts);
                var obj = Ext.decode(response.responseText);
                //console.log(response);
                //console.log(obj.data);
                Ext.fly('test_results').update(obj.data);
            },
            failure: function(response, opts) {
                //console.log('fail');
                Ext.fly('test_results').update(obj.data);
            },
        });
    }

    /**
     * All this mess just to do a modal window in ExtJS :(
     * We have to create a FormPanel and then add it to a Window.
     */
    function showCreateProfileModal()
    {
        console.log('showCreateProfileModal');

        // e.g. http://dev.sencha.com/deploy/ext-3.4.0/examples/form/states.js
        // TODO: pull from translated JSON?
        var providerStore = new Ext.data.ArrayStore({
            data   : [
                ['local',<?php print json_encode($this->modx->lexicon('local')); ?>],
                ['dropbox',<?php print json_encode($this->modx->lexicon('dropbox')); ?>],
                ['s3',<?php print json_encode($this->modx->lexicon('s3')); ?>],
                ['ftp',<?php print json_encode($this->modx->lexicon('ftp')); ?>],
                ['sftp',<?php print json_encode($this->modx->lexicon('sftp')); ?>]
            ],
            fields : ['id','name']
        });

        // Deprecated v3 constructor, but it works
        // http://docs.sencha.com/gxt-guides/3/ui/widgets/interaction/validation/FieldValidation.html
        // Gotta set the hiddenName in order to pass the value instead of the displayed label.
        // How many lines of code does it take ExtJS to reinvent the dropdown?
        var formPanel = new Ext.FormPanel({
            url         : <?php print json_encode($this->page('createprofile')); ?>,
            xtype       : 'form',
            autoScroll  : true,
            id          : 'create_profile_form',
            defaultType : 'field',
            frame       : false,
            items       : [
                {
                    xtype: 'textfield',
                    fieldLabel : <?php print json_encode($this->modx->lexicon('profile_name')); ?>,
                    name: 'profile_name',
                    allowBlank: false,
                    vtype: 'alphanum'
                },
                {
                    xtype        : 'combo',
                    fieldLabel   : <?php print json_encode($this->modx->lexicon('provider')); ?>,
                    //name         : 'provider',
                    hiddenName : 'provider',  // W/o this, the label is passed
                    store        : providerStore,
                    displayField : 'name',
                    valueField   : 'id',
                    editable     : false, // a normal dropdown
                    triggerAction : 'all', // don't hide the options
                    typeAhead    : false,
                    emptyText    : <?php print json_encode($this->modx->lexicon('select_provider')); ?>,
                    mode         : 'local',
                    allowBlank   : false,
                    forceSelection : true
                }
            ]
        });

        var win = new Ext.Window({
            id: 'createprofile'
            , title : <?php print json_encode($this->modx->lexicon('create_profile')); ?>
            , width : 400
            , height: 200
            , layout: "fit"
            , items  : formPanel
            , buttons:[
                {
                    text    : <?php print json_encode($this->modx->lexicon('create_profile')); ?>,
                    handler: function(btn){
                        var form = formPanel.getForm();
                        if (form.isValid()) {
                            console.log('Valid!');
                            form.submit({
                                success: function (form, action) {
                                    listProfiles();
                                    win.hide();
                                },
                                failure: function (form, action) {
                                    Ext.Msg.alert('Failed', action.result ? action.result.data : 'No response');
                                    console.log('fail');
                                }
                            });
                        }
                        else
                        {
                            console.error('Form is not valid!');
                        }
                    }
                },
                {
                    text    : <?php print json_encode($this->modx->lexicon('cancel')); ?>,
                    handler: function(){
                        win.hide();
                    }
                }
            ]
            , buttonAlign: 'center'

        });
        win.show();
    }

    /**
     * Edit the given profile in the pop-up modal
     * @param name
     */
    function editProfile(name)
    {
        console.log('editProfile '+name);

        var win = new Ext.Window({
            id: 'editprofile'
            , title : <?php print json_encode($this->modx->lexicon('edit_profile')); ?>
            //, autoHeight: true
            , height: 400
            , width: 400
            , layout: "fit"
            , autoLoad: {
                url : "<?php print $this->page('editprofile'); ?>&name="+name
                , scripts : true
            }
            , buttons:[
                {
                    text    : <?php print json_encode($this->modx->lexicon('update')); ?>,
                    handler: function(btn){
                        // See http://stackoverflow.com/questions/5112462/extjs-convert-html-form-to-extjs
                        console.log('Update profile: '+name);
                        var modal = new Ext.form.BasicForm('edit_provider_settings');
                        //console.log(modal.getValues());
                        Ext.Ajax.request({
                            url: <?php print json_encode($this->page('saveprofile')); ?>,
                            success: function(){
                                console.log('Success updating profile');
                                listProfiles();
                                win.close();
                            },
                            failure: function() {
                                console.error('failed to update profile');
                                // TODO: show error
                            },
                            params: modal.getValues()
                        });
                    }
                },
                {
                    text    : <?php print json_encode($this->modx->lexicon('delete_profile')); ?>,
                    handler: function(btn){
                        console.log('Delete profile '+name);
                        Ext.MessageBox.show({
                            title : <?php print json_encode($this->modx->lexicon('warning')); ?>,
                            msg : <?php print json_encode($this->modx->lexicon('delete_profile_verify')); ?>,
                            width : 300,
                            buttons : Ext.MessageBox.YESNO,
                            fn : function(btn,txt){
                                console.log(btn);
                                if (btn == 'yes')
                                {
                                    console.log('Initializing AJAX profile delete');
                                    Ext.Ajax.request({
                                        url: <?php print json_encode($this->page('deleteprofile')); ?>,
                                        params: {
                                            name: name
                                        },
                                        callback: function(opt,success,response) {
                                            var obj = Ext.decode(response.responseText);
                                            console.log('Callback:', obj.data);
                                            if(obj.success)
                                            {
                                                console.log('Successfully deleted profile: '+name);
                                                listProfiles();
                                            }
                                            else
                                            {
                                                console.error('Could not delete profile: '+name);
                                                Ext.MessageBox.show({
                                                    title: <?php print json_encode($this->modx->lexicon('error')); ?>,
                                                    msg: obj.data,
                                                    width: 300,
                                                    buttons: Ext.MessageBox.OK,
                                                    icon: Ext.MessageBox.WARNING
                                                });
                                            }
                                        }
                                    });
                                    //listProfiles(); // update
                                }
                                win.hide();
                            },
                            icon : Ext.MessageBox.WARNING
                        });
                    }
                },
                {
                    text    : <?php print json_encode($this->modx->lexicon('cancel')); ?>,
                    handler: function(){
                        win.hide();
                    }
                }
            ]
            , buttonAlign: 'center'

        });
        win.show();
    }
</script>

<?php include 'header.php'; ?>

    <div class="modxbackup_canvas_inner">
        <h2 class="modxbackup_cmp_heading" id="modxbackup_pagetitle"><?php print $this->modx->lexicon('settings.pagetitle') ?></h2>
    </div>

    <div class="x-panel-body panel-desc x-panel-body-noheader x-panel-body-noborder">

        <strong><?php print $this->modx->lexicon('setting_modxbackup.license_key') ?></strong> : <code><?php print $this->modx->getOption('modxbackup.license_key');?></code> <span class="modxbackup_success"><?php print $this->modx->lexicon('valid'); ?> &#10004;</span><br/>


        <strong><?php print $this->modx->lexicon('setting_modxbackup.tmp') ?></strong> : <code><?php print $this->modx->getOption('modxbackup.tmp');?></code><br/>
        <p><?php print $this->modx->lexicon('setting_modxbackup.tmp_desc') ?></p>

        <hr/>

        <h3>
            <?php print $this->modx->lexicon('backup_profiles'); ?>
            &nbsp;
            &nbsp;
            &nbsp;
            <span class="modxbackup-btn" onclick="javascript:showCreateProfileModal();"><?php print $this->modx->lexicon('create_profile'); ?></span>
        </h3>

        <div id="backup_profiles_container"></div>






    </div>
<?php include 'footer.php'; ?>