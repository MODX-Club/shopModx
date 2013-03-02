<?php
require_once dirname(dirname(__FILE__)).'/resource/create.class.php';
class ShopxResourceProductCreateManagerController extends ShopxResourceCreateManagerController{
    public function _loadCustomCssJs() {
        $this->loadCoreJS();
        
        $assetsUrl = $this->getAssetsUrl();
        $jsUrl = $assetsUrl.'js/';
        
        $currency = $this->modx->getOption('shopmodx.default_currency',  null);
        
        $JS = <<<JS
<script type="text/javascript">   
    Ext.onReady(function(){
        var tabs = Ext.getCmp('modx-resource-tabs');
        tabs. add({
            title: 'Данные товара'
            ,layout: 'form' 
            ,bodyCssClass: 'main-wrapper' 
            ,labelAlign: 'top'
            ,items:[{
                xtype: 'numberfield'
                ,fieldLabel: "Цена"
                ,name: 'sm_price'
                ,value: '{$price}'
                ,"allowBlank": true
                ,"allowDecimals": true
                ,"allowNegative": false
                ,"decimalPrecision": 2
                ,"decimalSeparator":"."
                //,"maxValue":""
                //,"minValue":""
            },{
                xtype: 'shopmodx-combo-currencies'
                ,value: '{$currency}'
            }]
        })
    });
</script>
JS;
        $this->modx->regClientStartupScript($JS, true);
        
        return true;
    }
    
    public function getDefaultTemplate(){
        if($template = $this->modx->getOption('shopmodx.product_default_template', null)){
            $this->scriptProperties['template'] = $template;
        }
        return parent::getDefaultTemplate();
    }
}

return 'ShopxResourceProductCreateManagerController';