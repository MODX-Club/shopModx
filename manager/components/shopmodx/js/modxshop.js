MODx.ux.modxShop = function(config){
    config = config || {};
    this.config = config;
    this.startup(config);
}

Ext.extend(MODx.ux.modxShop, MODx.Component, {
    config: {}
    ,util:{},window:{},panel:{},tree:{},form:{},grid:{},combo:{},toolbar:{},page:{},msg:{}
    ,expandHelp: true
    ,defaultState: []

    ,startup: function(config) {
        MODx.ux.modxShop.superclass.constructor.call(this,config);
    }
});