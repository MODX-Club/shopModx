<?php

/*
    Меняем статус заказа
*/

require_once dirname(dirname(__FILE__)). '/update.class.php';

class modShopmodxBillingMgrOrdersStatusUpdateProcessor extends modShopmodxBillingMgrOrdersUpdateProcessor{
    
    public function initialize(){
        
        if(!$new_status = $this->getProperty('new_status')){
            return 'Не был указан новый статус';
        }
        
        return parent::initialize();
    }
    
    public function beforeSet(){
        $new_status = $this->getProperty('new_status');
        
        if($new_status == $this->object->get('status_id')){
            return 'У заказа уже установлен этот статус';
        }
        
        // else
        // Устанавливаем новый статус
        $this->setProperty('status_id', $new_status);
        
        return parent::beforeSet();
    }
}

return 'modShopmodxBillingMgrOrdersStatusUpdateProcessor';