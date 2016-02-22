
Shopmodx = function(config){
    config = config || {};
    Ext.applyIf(config, {
        connectors_url: (MODx.config['shopmodx.manager_url'] || MODx.config.manager_url + 'components/shopmodx/') + 'connectors/'
    });
    this.config = config;
    Shopmodx.superclass.constructor.call(this,config);
}

Ext.extend(Shopmodx, MODx.Component, {
    config: {}
    ,util:{}
    ,window:{}
    ,panel:{}
    ,tree:{}
    ,form:{}
    ,grid:{}
    ,combo:{}
    ,toolbar:{}
    ,page:{}
    ,msg:{}
    ,sudo:false
    ,policies:{}
    ,expandHelp: true
    ,defaultState: []
    
    ,hasPermission: function(police){
        return this.sudo || this.policies[police] || false;
    }
});

Shopmodx = new Shopmodx();