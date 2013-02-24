
Shopmodx = function(config){
    config = config || {};
    Ext.applyIf(config, {
        connectors_url: (MODx.config['shopmodx.manager_url'] || MODx.config.manager_url + 'components/shopmodx/') + 'connectors/'
    });
    this.config = config;
    this.startup(config);
}

Ext.extend(Shopmodx, MODx.Component, {
    config: {}
    ,util:{},window:{},panel:{},tree:{},form:{},grid:{},combo:{},toolbar:{},page:{},msg:{}
    ,expandHelp: true
    ,defaultState: []

    ,startup: function(config) {
        Shopmodx.superclass.constructor.call(this,config);
    }
});

Shopmodx = new Shopmodx();