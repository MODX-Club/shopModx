<?php

require_once dirname(__FILE__). '/update.class.php';

class modShopmodxBillingMgrOrdersStatusTakeorderProcessor extends modShopmodxBillingMgrOrdersStatusUpdateProcessor{
    
    public function initialize(){
        $this->setProperty('new_status', 3);
        
        $this->setDefaultProperties(array(
            'manager'   => $this->modx->user->id,
        ));
        
        return parent::initialize();
    }
    
    public function beforeSet(){
        // Проверяем наличие менеджера у заказа
        if($this->object->get('manager')){
            return 'Данному заказу уже назначен менеджер.';
        }
        
        // Проверяем статус. Смена статуса возможна только если статус Оформлен или Оплачен
        if(!in_array($this->object->get('status_id'),  array(2, 8))){
            return 'Принять можно только оформленный или оплаченный заказ.';
        }
        
        return parent::beforeSet();
    }
}

return 'modShopmodxBillingMgrOrdersStatusTakeorderProcessor';