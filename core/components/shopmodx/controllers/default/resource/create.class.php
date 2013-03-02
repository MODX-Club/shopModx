<?php

/*
 * ShopxResourceCreateManagerController
 * ShopxResourceProductModelCreateManagerController
 * ShopxResourceWarehouseCreateManagerController
 * ShopxResourceLegalFormCreateManagerController
 * ShopxResourceCurrencyCreateManagerController
 */

require_once MODX_MANAGER_PATH. "controllers/default/resource/create.class.php";

class ShopxResourceCreateManagerController extends ResourceCreateManagerController{
    public $assetsUrl;
    
    public function loadCustomCssJs() {
        parent::loadCustomCssJs();
        return $this->_loadCustomCssJs();
    }    
    
    public function _loadCustomCssJs(){return true;}
    
    public function getAssetsUrl(){
        if(!$this->assetsUrl AND !$this->assetsUrl = $this->modx->getOption('shopmodx.manager_url')){
            $this->assetsUrl = $this->modx->getOption('manager_url').'components/shopmodx/';
        }
        return $this->assetsUrl;
    }
    
    public function loadCoreJS(){
        $assetsUrl = $this->getAssetsUrl();
        $jsUrl = $assetsUrl.'js/';
        $this->modx->regClientStartupScript($jsUrl.'core/shopmodx.js');
        return true;
    }
    
}

class ShopxResourceProductModelCreateManagerController  extends ShopxResourceCreateManagerController{}
class ShopxResourceWarehouseCreateManagerController  extends ShopxResourceCreateManagerController{}
class ShopxResourceLegalFormCreateManagerController  extends ShopxResourceCreateManagerController{}
class ShopxResourceCurrencyCreateManagerController  extends ShopxResourceCreateManagerController{}

return 'ShopxResourceCreateManagerController';