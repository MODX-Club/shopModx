<?php

/*
	Получаем текущий заказ пользователя
*/

require_once __DIR__ . '/../object.class.php';

class modShopmodxOrdersOwnObjectProcessor extends modShopmodxOrdersObjectProcessor{


    public function initialize(){
        
        $this->setProperties(array(
            "new_object"   => false,        // Флаг, что это новый объект
            "save_object"   => false,        // Флаг, что объект надо сохранять
        ));
        
        return parent::initialize();
    }

}

return 'modShopmodxOrdersOwnObjectProcessor';