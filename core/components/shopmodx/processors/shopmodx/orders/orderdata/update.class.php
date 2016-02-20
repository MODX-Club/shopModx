<?php

# require_once MODX_CORE_PATH . 'components/billing/processors/mgr/orders/products/update.class.php';
require_once dirname(dirname(dirname(__FILE__))) . '/billing/mgr/orders/products/update.class.php';

class modShopmodxOrdersOrderdataUpdateProcessor extends modShopmodxBillingMgrOrdersProductsUpdateProcessor{
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            "order_product_id"  => $this->getProperty('id'),
        ));
        
        
        return parent::initialize();
    }
    
}


return 'modShopmodxOrdersOrderdataUpdateProcessor';
