<?php
require_once dirname(dirname(__FILE__)).'/resource/update.class.php';
class ShopxResourceProductUpdateManagerController extends ShopxResourceUpdateManagerController{
    public function _loadCustomCssJs___() {
        $this->loadCoreJS();
        
        $assetsUrl = $this->getAssetsUrl();
        $jsUrl = $assetsUrl.'js/';
        
        $this->modx->regClientStartupScript($jsUrl.'widgets/combo/currencies.combo.js');
        return true;
    }      
    
    public function getResource() {
        $loaded = parent::getResource();
        if($loaded !== true){
            return $loaded;
        }
        if(!$this->resource->getObject()){
            // return 'Can not get related object';
            $this->resource->addObject();
        }
        return true;
    }




    function prepareResource() {
        $product = $this->resource->getObject();
        $currency = $product->get('sm_currency');
        $price = $product->get('sm_price');
                
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
    }
}

return 'ShopxResourceProductUpdateManagerController';