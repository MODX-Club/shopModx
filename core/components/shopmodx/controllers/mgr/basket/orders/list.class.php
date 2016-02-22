<?php

require_once dirname(dirname(__FILE__)). '/index.class.php';

class ShopmodxControllersMgrBasketOrdersListManagerController extends ShopmodxControllersMgrBasketManagerController{
    
    # public static function getInstance(modX &$modx, $className, array $config = array()) {
    #     $className = __CLASS__;
    #     return new $className($modx, $config);
    # }
    # 
    # public static function getInstanceDeprecated(modX &$modx, $className, array $config = array()) {
    #     return self::getInstance($modx, $className, $config);
    # }

    function loadCustomCssJs(){
        parent::loadCustomCssJs();
        
        
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/widgets/element/modx.panel.tv.renders.js');
        
        $assets_url = $this->getOption('assets_url');
        
        # $groupeditor_assets_url = $this->modx->getOption('manager_url') .'components/shopmodxgroupedit/';
        # 
        # $this->addJavascript($groupeditor_assets_url .'js/core/shopmodxgroupedit.js'); 
        
        # $this->config['assets'] = $modx->getOption("{$namespace}.manager_url", null, $modx->getOption('manager_url')."components/{$namespace}/");
        # $this->config['connectors_url'] = $this->config['assets'].'connectors/';
        # $this->config['connector_url'] = $this->config['connectors_url'].'connector.php';
        
        # $this->addHtml('<script type="text/javascript">
        #     shopModxGroupEdit.config = '. $this->modx->toJSON(array_merge($this->config, array(
        #         "connectors_url"    => $groupeditor_assets_url . 'connectors/',
        #         "connector_url"    => $groupeditor_assets_url . 'connectors/connector.php',
        #     ))).';
        # </script>');
        
        # $this->addJavascript($this->modx->getOption('manager_url') .'components/shopmodxgroupedit/js/widgets/grid.js'); 
        
        
        
        $this->modx->regClientStartupScript($assets_url.'js/ext/ux/RowExpander.js'); 
        $this->modx->regClientStartupScript($assets_url.'js/widgets/orders/orders.grid.js'); 
        
        $this->modx->regClientStartupScript('<script type="text/javascript">Ext.onReady(function(){
            MODx.add("shopmodx-grid-ordersgrid")}
        );</script>', true); 
        
        return;
    }
    
    # public function getTemplateFile() {
    #     return 'orders/list/index.tpl';
    # }
}