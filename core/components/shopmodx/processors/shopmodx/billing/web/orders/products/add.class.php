<?php

/*
    Добавляем товар к заказу
*/

require_once dirname(dirname(dirname(dirname(__FILE__)))). '/mgr/orders/products/add.class.php';

class modWebOrdersProductsAddProcessor extends modMgrOrdersProductsAddProcessor{
    
    public function initialize(){
        
        $this->unsetProperty('price');
        $this->unsetProperty('currency_id');
        
        return parent::initialize();
    }
    
}

return 'modWebOrdersProductsAddProcessor';
