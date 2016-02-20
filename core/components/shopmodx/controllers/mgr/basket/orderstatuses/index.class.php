<?php

require_once dirname(dirname(__FILE__)). '/index.class.php';

class ShopmodxControllersMgrBasketOrderstatusesManagerController extends ShopmodxControllersMgrBasketManagerController{
    
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
        $assets_url = $this->getOption('assets_url');
        # $this->modx->regClientStartupScript($assets_url.'js/ext/ux/RowExpander.js'); 
        $this->modx->regClientStartupScript($assets_url.'js/widgets/orders/orderstatuses.grid.js'); 
        
        $this->modx->regClientStartupScript('<script type="text/javascript">Ext.onReady(function(){MODx.add("shopmodx-grid-orderstatusesgrid")});</script>', true); 
        
        return;
    }
    
    # public function getTemplateFile() {
    #     return 'orders/list/index.tpl';
    # }
}