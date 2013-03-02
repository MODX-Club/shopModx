<?php
require_once dirname(dirname(__FILE__)).'/resource/update.class.php';
class ShopxResourceClientUpdateManagerController extends ShopxResourceUpdateManagerController{
    public function _loadCustomCssJs() {
        $this->loadCoreJS();
        
        $assetsUrl = $this->getAssetsUrl();
        $jsUrl = $assetsUrl.'js/';
        
        $this->modx->regClientStartupScript($jsUrl.'widgets/combo/legalforms.combo.js');
        return true;
    }      
    
    public function getResource() {
        $loaded = parent::getResource();
        if($loaded !== true){
            return $loaded;
        }
        if(!$this->resource->getObject()){
            return 'Can not get related object';
        }
        return true;
    }




    function prepareResource() {
        $legalform = $this->resource->getObject()->get('sm_legal_form');
                
        $JS = <<<JS
<script type="text/javascript">   
    Ext.onReady(function(){
        var tabs = Ext.getCmp('modx-resource-tabs');
        tabs. add({
            title: 'Данные клиента'
            ,layout: 'form' 
            ,bodyCssClass: 'main-wrapper' 
            ,labelAlign: 'top'
            ,items:[{
                xtype: 'shopmodx-combo-legalforms'
                ,value: '{$legalform}'
            }]
        })
    });
</script>
JS;
        $this->modx->regClientStartupScript($JS, true);
        
    }
}

return 'ShopxResourceClientUpdateManagerController';