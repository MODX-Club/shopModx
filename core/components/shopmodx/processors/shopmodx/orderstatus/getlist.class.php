<?php


require_once dirname(__FILE__) . '/getdata.class.php';

class ShopOrderStatusGetlistProcessor extends modShopmodxOrderstatusGetdataProcessor{
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            "grid"  => true,
            "format"  => 'json',
        ));
        
        return parent::initialize();
    }
}

return 'ShopOrderStatusGetlistProcessor';