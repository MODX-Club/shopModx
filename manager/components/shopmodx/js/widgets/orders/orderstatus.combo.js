// 
Shopmodx.combo.OrderStatus = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: Shopmodx.config.connectors_url + 'orderstatus.php'
        ,forceSelection: true
        ,fields: ['id','status']
        ,displayField: 'status'
        ,baseParams: {
            action: 'getList'
            ,show_empty_text: config.show_empty_text
        }
    });
    Shopmodx.combo.OrderStatus.superclass.constructor.call(this,config);
};
Ext.extend(Shopmodx.combo.OrderStatus ,MODx.combo.ComboBox);
Ext.reg('shopmodx-combo-orderstatus', Shopmodx.combo.OrderStatus);