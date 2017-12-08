<?php

/*
Создаем новый заказ с указанием товаров
*/

# require_once MODX_CORE_PATH . 'components/billing/processors/mgr/orders/update.class.php';
# require_once dirname(dirname(__FILE__)) . '/billing/mgr/orders/update.class.php';
require_once dirname(__FILE__) . '/object.class.php';

class modShopmodxOrdersUpdateProcessor extends modShopmodxOrdersObjectProcessor{

    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            "new_object"   => false,        // Флаг, что это новый объект
            "save_object"   => true,        // Флаг, что объект надо сохранять
        ));
        
        return parent::initialize();
    }

}

return 'modShopmodxOrdersUpdateProcessor';