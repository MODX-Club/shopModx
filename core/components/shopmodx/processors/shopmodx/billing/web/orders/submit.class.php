<?php
/*
    Принимаем заказ. Просто меняем статус существующего
*/
require_once dirname(dirname(dirname(__FILE__))) . '/mgr/orders/status/update.class.php';

class modWebOrdersSubmitProcessor extends modMgrOrdersStatusUpdateProcessor{
    
    public function initialize(){
        
        /*print '<pre>';
        print_R($this->getProperties());
        exit;*/
        $this->setProperties(array(
            "new_status" => 2,      // Оформлен   
        ));
        
        return parent::initialize();
    }
     
    
    
    public function beforeSave(){
        
        // Проверяем наличие пользователя
        if(!$this->object->get('contractor') && !$this->object->getOne('Contractor')){
            $error = "У заказа отсутствует контрагент";
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[- Billing -] [- ". __CLASS__ ." -] {$error}");
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($this->getProperties(), true));
            return $error;
        }
        
        
        return parent::beforeSave();
    }
}

return 'modWebOrdersSubmitProcessor';