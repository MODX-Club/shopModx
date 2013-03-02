Shopmodx.combo.Currencies = function(config){
    config = config || {};
    Ext.applyIf(config,{
        fieldLabel: 'Валюта'
        ,name: 'sm_currency'
        ,hiddenName: 'sm_currency'
        ,width: 200
        ,url: Shopmodx.connectors_url + 'resourcecurrency.php'
        ,displayField: 'pagetitle'
        ,fields: ['id','pagetitle']
        ,allowBlank: false
        ,panel: Ext.getCmp('modx-panel-resource')
        ,baseParams: {
            action: 'getComboList'
        }
    });
    Shopmodx.combo.Currencies.superclass.constructor.call(this,config);
    this.config = config;
    this.startup(config);
    return this;
}

Ext.extend(Shopmodx.combo.Currencies,MODx.combo.ComboBox, {
    config: {}
    ,startup: function(config) {
        Shopmodx.combo.Currencies.superclass.constructor.call(this,config);
        
        this.on('select', this.OnSelect, this);
    }
    
    ,OnSelect: function(){
        if(!this.panel){return;}
        this.panel.fireEvent('fieldChange');
    }
});
Ext.reg('shopmodx-combo-currencies',Shopmodx.combo.Currencies);