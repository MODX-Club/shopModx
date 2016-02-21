<?php

/*
    Удаляем товар из корзины
*/

require_once dirname(dirname(__FILE__)) . '/object.class.php';

class modShopmodxOrdersProductsRemoveProcessor extends modShopmodxOrdersObjectProcessor{
    
    public function initialize() {
        
        $this->setProperties(array(
            "new_object"   => false,        // Флаг, что это новый объект
            "save_object"   => true,       // Флаг, что объект надо сохранять
        ));
        
        return parent::initialize();
    }
    
    
    
    
    public function beforeSave(){
        
        $removed = $this->removeProduct();
        
        if($removed !== true){
            return $removed;
        }
        
        # return "Debug";
        
        return parent::beforeSave();
    }
    
    
    public function success($msg = '',$object = null) {
        
        $msg = 'Товар успешно удален';
        
        return parent::success($msg, $object);
    }
    
}

return 'modShopmodxOrdersProductsRemoveProcessor';
