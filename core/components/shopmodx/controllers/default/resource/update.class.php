<?php

require_once MODX_MANAGER_PATH. "controllers/default/resource/update.class.php";

class ShopxResourceUpdateManagerController extends ResourceUpdateManagerController{
    public function loadCustomCssJs() {
        parent::loadCustomCssJs();
        return $this->_loadCustomCssJs();
    }    
    
    public function _loadCustomCssJs(){
                $JS = <<<JS
        <script type="text/javascript">   
            Ext.onReady(function(){
                var tabs = Ext.getCmp('modx-resource-tabs');
                tabs. add({
                    title: 'Дополнительные данные'
                })
            });
        </script>
JS;
        $this->modx->regClientStartupScript($JS, true);
        return;
    }
    
    public function getAssetsUrl(){
        if(!$url = $this->modx->getOption('shopmodx.manager_url')){
            $url = $this->modx->getOption('manager_url').'components/shopmodx/';
        }
        return $url;
    }
}

return 'ShopxResourceUpdateManagerController';