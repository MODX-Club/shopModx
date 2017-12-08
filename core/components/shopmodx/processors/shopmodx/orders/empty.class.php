<?php

/*
    Очистка корзины
*/

require_once dirname(__FILE__) . '/update.class.php';

class modShopmodxOrdersEmptyProcessor extends modShopmodxOrdersUpdateProcessor{
    
    
    public function initialize(){
        
        $this->setProperties(array(
            "status_id"     => 7,           // Устанавливаем новый статус - Отменен
        ));
        
        return parent::initialize();
    }
    
    
    public function cleanup($msg = '') {
        
        // Сбрассываем сессию
        unset($_SESSION['order_id']);
        
        return $this->success('Корзина успешно очищена', $this->object->toArray());
    }  
}

return 'modShopmodxOrdersEmptyProcessor';

