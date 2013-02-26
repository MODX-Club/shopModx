Shopmodx.combo.LegalForms = function(config){
    config = config || {};
    Ext.applyIf(config,{
        fieldLabel: 'Юр/физ. лицо'
        ,name: 'sm_legal_form'
        ,hiddenName: 'sm_legal_form'
        ,width: 200
        ,url: Shopmodx.connectors_url + 'resourcelegalform.php'
        ,displayField: 'pagetitle'
        ,fields: ['id','pagetitle']
        ,allowBlank: false
        ,panel: Ext.getCmp('modx-panel-resource')
        ,baseParams: {
            action: 'getComboList'
        }
    });
    Shopmodx.combo.LegalForms.superclass.constructor.call(this,config);
    this.config = config;
    this.startup(config);
    return this;
}

Ext.extend(Shopmodx.combo.LegalForms,MODx.combo.ComboBox, {
    config: {}
    ,startup: function(config) {
        Shopmodx.combo.LegalForms.superclass.constructor.call(this,config);
        
        this.on('select', this.OnSelect, this);
    }
    
    ,OnSelect: function(){
        if(!this.panel){return;}
        this.panel.fireEvent('fieldChange');
    }
});
Ext.reg('shopmodx-combo-legalforms',Shopmodx.combo.LegalForms);