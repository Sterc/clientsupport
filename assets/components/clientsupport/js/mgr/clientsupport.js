var ClientSupport = function(config) {
    config = config || {};
ClientSupport.superclass.constructor.call(this,config);
};
Ext.extend(ClientSupport,Ext.Component,{
    page:{},
    window:{},
    grid:{},
    tree:{},
    panel:{},
    combo:{},
    config: {},
    supportWindow: function() {
        var supportWindow = MODx.load({
            xtype: 'clientsupport-window-support'
            ,listeners: {
                'success': {fn:function() { 
                    Ext.MessageBox.alert(_('success'), _('clientsupport.message_sent'));
                },scope:this}
            }
        });
        supportWindow.show();
    }
});
Ext.reg('clientsupport',ClientSupport);

ClientSupport = new ClientSupport();
ClientSupport.window.Support = function(config) {
    var ticket_system_logo = '';
    if (ClientSupport.config.ticket_system) {
        ticket_system_logo = '<img src="'+ClientSupport.config.assetsUrl+'img/logo_'+ClientSupport.config.ticket_system+'.jpg" />';
    }
    config = config || {};
    Ext.applyIf(config,{
        title: _('clientsupport.help')
        ,closeAction: 'close'
        ,width: 650
        ,modal: true
        ,cls: 'clientsupport'
        ,url: ClientSupport.config.connectorUrl
        ,action: 'mgr/support/send'
        ,labelAlign: 'left'
        ,labelWidth: 160
        ,cancelBtnText: _('clientsupport.form.cancel')
        ,saveBtnText: _('clientsupport.form.submit')
        ,fields: [{
            html: '<h2>'+_('clientsupport.header')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            html: '<div class="clientsupport-desc"><p>'+_('clientsupport.help.description')+'</p>'+ticket_system_logo+'</div>'
            ,border: true
            ,style: {
                padding: '5px 0 10px 0'
            }
        },{
            xtype: 'textfield'
            ,fieldLabel: _('clientsupport.name')
            ,name: 'name'
            ,anchor: '100%'
            ,height: 'auto'
            ,allowBlank: false
            ,value: ClientSupport.config.user_name
        },{
            xtype: 'textfield'
            ,vtype: 'email'
            ,fieldLabel: _('clientsupport.email')
            ,name: 'email'
            ,anchor: '100%'
            ,height: 'auto'
            ,allowBlank: false
            ,value: ClientSupport.config.user_email
        },{
            xtype: 'textfield'
            ,fieldLabel: _('clientsupport.problem')
            ,name: 'problem'
            ,anchor: '100%'
            ,height: 'auto'
            ,allowBlank: false
        },{
            xtype: 'label'
            ,text: _('clientsupport.problem.label')
            ,cls: 'desc-under'
            ,style: {
                paddingLeft: '165px'
            }
        },{
            xtype: 'statictextfield'
            ,fieldLabel: _('clientsupport.url')
            ,name: 'url'
            ,value: window.location.href
            ,anchor: '100%'
            ,height: 'auto'
            ,allowBlank: false
        },{
            xtype: 'textarea'
            ,fieldLabel: _('clientsupport.problem.message')
            ,name: 'message'
            ,anchor: '100%'
            ,height: 120
        },{
            xtype: 'label'
            ,text: _('clientsupport.notice')
            ,cls: 'desc-under'
            ,style: {
                padding: '5px 0 10px 0'
            }
        }]
    });
    ClientSupport.window.Support.superclass.constructor.call(this,config);
};
Ext.extend(ClientSupport.window.Support,MODx.Window);
Ext.reg('clientsupport-window-support',ClientSupport.window.Support);

ClientSupport.combo.ticket_systems = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'ticket_system'
        ,hiddenName: 'ticket_system'
        ,forceSelection: false
        ,typeAhead: false
        ,editable: false
        ,allowBlank: true
        ,listWidth: 250
        ,pageSize: 20
        ,store: new Ext.data.SimpleStore({
            data: [
                ["", "None"],
                ["zendesk", "Zendesk"],
                ["freshdesk", "Freshdesk"],
                ["jira", "JIRA"]
            ],
            id: 0,
            fields: ["value", "text"]
        })
        ,valueField: 'value'
        ,displayField: 'text'
        ,mode: "local"
    });
    ClientSupport.combo.ticket_systems.superclass.constructor.call(this,config);
};
Ext.extend(ClientSupport.combo.ticket_systems,MODx.combo.ComboBox);
Ext.reg('clientsupport-combo-ticket-systems',ClientSupport.combo.ticket_systems);