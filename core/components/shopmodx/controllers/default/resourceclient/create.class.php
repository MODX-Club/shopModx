<?php
require_once dirname(dirname(__FILE__)).'/resource/create.class.php';
class ShopxResourceClientCreateManagerController extends ShopxResourceCreateManagerController{
    public function _loadCustomCssJs() {
        $this->loadCoreJS();
        
        $assetsUrl = $this->getAssetsUrl();
        $jsUrl = $assetsUrl.'js/';
        
        $this->modx->regClientStartupScript($jsUrl.'widgets/combo/legalforms.combo.js');
        
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
            }]
        })
    });
</script>
JS;
        $this->modx->regClientStartupScript($JS, true);
        
        return true;
    }  
}

return 'ShopxResourceClientCreateManagerController';