<?php

/*
    Абстрактный класс на проведение оплаты.
    Его нельзя вызывать напрямую, чтобы исключить случаи инжекта оплаты. 
    Этот класс должен расширяться другим классом конкретной платежной системы,
    чтобы использовать методы проверки платежа самой платежной системы
*/

require_once dirname(__FILE__) . '/object.class.php';


class modShopmodxPaymentsCreateProcessor extends modShopmodxPaymentsObjectProcessor{
    # public $classKey = 'Payment';
    
    # protected $BillingProcessorsPath;
    
    # public function checkPermissions() {
    #     
    #     // Проверяем подпись платежной системы
    #     $ok = $this->checkSignature();
    #     if($ok !== true){
    #         $this->error($ok);
    #         return false;
    #     }
    #     
    #     return parent::checkPermissions();
    # }
    
    
    /*
        Обязательно надо прописывать метод, в котором будет выполняться проверка 
        подписи с сервера платежной системы
    */
    protected function checkSignature(){
        
        return "Неверная подпись";
    }
    
    
    public function initialize(){
         
        # $this->BillingProcessorsPath = MODX_CORE_PATH . 'components/billing/processors/';
        
        
        $this->setProperties(array(
            "new_object"   => true,        // Флаг, что это новый объект
            "save_object"   => true,       // Флаг, что объект надо сохранять
        ));
        
        $this->setDefaultProperties(array(
            'currency_id'  => $this->modx->getOption('shopmodx.default_currency'),
        ));
        
        return parent::initialize();
    }
    
    public function beforeSave(){
        global $site_id;
        
        $object = $this->object;
        
        // Проверяем подпись платежной системы
        $ok = $this->checkSignature();
        
        if($ok !== true){
            return $ok;
        }
        
        
        
        
        if(!$object->paysystem_id){
            $error = "Не был получен ID платежной системы";
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, $error, '', __METHOD__, __FILE__, __LINE__);
            return $this->failure($error);
        }
        
        if(!$Paysystem = $object->Paysystem){
            $error = "Не был получен объект платежной системы";
            return $error;
        }
        
        // else
        # if(
        #     !$currency_id = (int)$this->getProperty('currency_id')
        #     OR !$currency = $this->modx->getObject('modResource', $currency_id)
        #     OR ! $currency instanceof ShopmodxResourceCurrency
        # ){
        #     $error = "Не был получен объект валюты";
        #     $this->error($error);
        #     return $this->failure($this->failure($error));
        # }
        
        // Проверяем, если указан счет платежной системы, то надо убедиться, что 
        // он еще не числится в биллинге
        if($paysys_invoice_id = $object->paysys_invoice_id){
            
            
            $this->log("Пытаемся оплатить счет процессинга {$paysys_invoice_id}");
            
            if($this->modx->getCount($this->classKey, array(
                'paysys_invoice_id' => $object->paysys_invoice_id,
                'paysystem_id'      => $object->paysystem_id,
            ))){
                $error = "Данный счет уже создан в системе.";
                return $error;
            }
        }
        
        // Проверяем счет
        if($object->order_id){
            
            // Пытаемся получить объект заказа
            if(!$Order = $object->Order){
                return "Не был получен объект заказа";
            }
            
            // Пытаемся получить комплексные данные заказа
            $response = $this->modx->runProcessor('shopmodx/orders/object',
            array(
                "order_id"  => $Order->id,
                "site_id"   => $site_id,
            ), array(
                'processors_path' => dirname(dirname(dirname(__FILE__))) . '/',
            ));
            
            if($response->isError()){
                $error = $response->getMessage();
                return $error ? $error : "Не были получены данные заказа";
            }
            
            // else
            $data = $response->getObject();
            $Order->fromArray($data);
            
            # print_r($Order->toArray());
            # return;
            # print_r($r);
            # 
            # if(!$r['object']){
            #     $error = 'Order not exists';
            #     return $error;
            # } 
            
            // Проверяем сумму
            if(
                !empty($Order->sum)
                AND !$this->getProperty('allow_partial_payment')
                AND $object->sum < $Order->sum
            ){
                $error = 'Incorrect sum';
                return $error;
            }
            
            // Устанавливаем статус оплаченного
            $object->Order->fromArray(array(
                "number_history" => $object->Order->number_history + 1,
                "status_id"     => 8,
                "editedon"      => time(),
                "editedby"      => $object->Order->contractor,
            ));
        }
        
        # return 'false';
        # 
        # $this->object->addOne($currency);
        # $this->object->addOne($paysystem);
        
        return parent::beforeSave();
    }
    
    
    # protected function __process(){
    #     return parent::process();
    # } 
    
    
    # public function beforeSet(){
    #     
    #     $this->setProperties(array(
    #         "createdby" => $this->modx->user->id ? $this->modx->user->id : null,
    #         "date"      => time(),
    #     ));
    #     
    #     return parent::beforeSet();
    # }
    
    
    # protected function getResponseSuccess($message)
    # {
    #     return $message;
    # }
    # 
    # protected function failure($message)
    # {
    #     return $message;
    # }
    
    protected function log($msg, $level = null){
        if($level === null){
            $level = xPDO::LOG_LEVEL_INFO;
        }
        $this->modx->log($level, "[Basket - ".__CLASS__."] {$msg}");
        $this->modx->log($level, print_r($this->getProperties(), true));
        return $msg;
    }
    # 
    protected function error($msg){
        $this->log(xPDO::LOG_LEVEL_ERROR, $msg);
        return $msg;
    }
    # 
    # /*
    #     Логируем все ошибки процессора, на всякий случай
    # */
    # public function failure($msg = '',$object = null) {
    #     $this->error($msg);
    #     if(!empty($this->object) && is_object($this->object)){
    #         $this->error(print_r($this->object->toArray(), true));
    #     }
    #     return parent::failure($msg,$object);
    # }
    
    # public function cleanup() {
    #     /*
    #         // Если оплата прошла успешно, то обновляем статус заказа
    #     */
    #     if($order_id = $this->object->get('order_id')){
    #         $this->modx->runProcessor('mgr/orders/status/pay', array(
    #             'order_id'  => $order_id,
    #         ), array(
    #             'processors_path' => $this->BillingProcessorsPath,    
    #         ));
    #         // На всякий случай сбрасываем счетчик ошибок, если вдруг в вызываемом
    #         // процессоре были ошибки
    #         $this->modx->error->reset();
    #     }
    #     
    #     return $this->success($this->getSuccessMessage(), $this->object);
    # }
    # 
    # protected function getSuccessMessage(){
    #     return '';
    # }
}

return 'modShopmodxPaymentsCreateProcessor';