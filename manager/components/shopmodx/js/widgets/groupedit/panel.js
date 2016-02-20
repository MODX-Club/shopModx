/*
    Общая панель-заготовка для панелей с заголовками
*/
Shopmodx.panel.GroupEditPanel = function(config){
    config = config || {};
    
    Ext.applyIf(config, {
        border: false
        ,baseCls: 'modx-formpanel container'
        ,items: []
    });
    
    if(config.paneltitle){
        config.items.splice(0,0,{
            html: '<h2>'+config.paneltitle+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        });
    }
    
    Shopmodx.panel.GroupEditPanel.superclass.constructor.call(this, config);
};

Ext.extend(Shopmodx.panel.GroupEditPanel, MODx.Panel, {});
Ext.reg('Shopmodx-panel-groupeditpanel', Shopmodx.panel.GroupEditPanel);


/*
    Главная панель для группового редактора
*/
Shopmodx.panel.GroupEditMainPanel = function(config){
    config = config || {};
    
    Ext.applyIf(config, {
        paneltitle: 'Групповое редактирование'
        ,items:[
            {
                xtype: 'Shopmodx-tabs-groupedit'
            }
        ]
    });
    
    Shopmodx.panel.GroupEditMainPanel.superclass.constructor.call(this, config);
};

Ext.extend(Shopmodx.panel.GroupEditMainPanel, Shopmodx.panel.GroupEditPanel, {});
Ext.reg('Shopmodx-panel-groupeditmainpanel', Shopmodx.panel.GroupEditMainPanel);