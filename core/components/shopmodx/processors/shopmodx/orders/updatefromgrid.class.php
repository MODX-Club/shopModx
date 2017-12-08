<?php

require_once dirname(__FILE__).'/update.class.php';
require_once dirname(__FILE__).'/getlist.class.php';

class modShopmodxOrdersUpdatefromgridProcessor extends modShopmodxOrdersUpdateProcessor{ 
    public function initialize(){
        
        /*$this->modx->setLogLevel(3);
        $this->modx->setLogTarget('HTML');*/
        
        if($data = $this->modx->fromJSON($this->getProperty('data'))){
            $this->setDefaultProperties($data);
            unset($this->properties['data']);
        }
        
        $this->setProperty('id', (int)$this->getProperty('order_id'));
        return parent::initialize();
    }
    
    public function afterSave(){
        
        if($this->modx->hasPermission('edit_clients_data')){
            // Получаем объект профиля заказчика и отдаем данные на обновление
            if($orderer = $this->object->getOne('Contractor') AND $ordererProfile = $orderer->getOne('Profile')){
                $data = array(
                    'fullname' => $this->getProperty('contractor_fullname', $ordererProfile->fullname),    
                    'email' => $this->getProperty('contractor_email', $ordererProfile->email),
                    'phone' => $this->getProperty('contractor_phone', $ordererProfile->phone),
                );
                $ordererProfile->fromArray($data);
                $ordererProfile->save();
            }
        }
        
        return parent::afterSave();
    }
    
    public function cleanup($msg = '') {
        
        $processor = new modShopmodxOrdersGetlistProcessor($this->modx, array(
            'grid' => 0,
            'order_id'  => $this->getProperty('id'),
        ));
        
        if(!$response = $processor->run() OR $response->isError() OR !$object = $response->getObject()){
            $object = $this->object->toArray();
        }
        
        //return '{"total":"1","results":'.$this->modx->toJSON($object).'}';
        return $this->success('', $object);
    }
}

return 'modShopmodxOrdersUpdatefromgridProcessor';