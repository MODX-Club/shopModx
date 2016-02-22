/*
    Таб-панель для группового редактора
*/
shopModxGroupEdit.tabs.GroupEdit = function(config){
    config = config || {};
    
    Ext.applyIf(config, {
        bodyStyle: 'padding: 10px'
        ,border: true
        ,items: [
            {
                xtype: 'shopmodxgroupedit-grid-groupedit'
            }    
        ]
    });
    
    shopModxGroupEdit.tabs.GroupEdit .superclass.constructor.call(this, config);
};

Ext.extend(shopModxGroupEdit.tabs.GroupEdit, MODx.Tabs, {});
Ext.reg('shopmodxgroupedit-tabs-groupedit', shopModxGroupEdit.tabs.GroupEdit);