<?php

require_once MODX_CORE_PATH . 'components/modxsite/processors/site/web/object.class.php';

abstract class modShopmodxPaymentsObjectProcessor extends modSiteWebObjectProcessor{
    
    
    public $classKey = 'ShopmodxPayment';
    
    
    public function initialize() {
        
        # $this->unsetProperty('paysystem_id');
        # $this->unsetProperty('paysys_invoice_id');
        # $this->unsetProperty('owner');
        $this->unsetProperty('createdby');
        $this->unsetProperty('createdon');
        $this->unsetProperty('editedby');
        $this->unsetProperty('editedon');
        
        $this->setDefaultProperties(array(
            "new_object"   => true,        // Флаг, что это новый объект
            "save_object"   => false,       // Флаг, что объект надо сохранять
        ));
        
        $this->setProperties(array(
            "allow_partial_payment"    => $this->modx->getOption('shopmodx.allow_partial_payment', null, false),    // Разрешить частичную оплату
        ));
        
        return parent::initialize();
    }
    
    
    protected function initializeObject(){
        $initialized = parent::initializeObject();
        
        if($initialized !== true){
            return $initialized;
        }
        
        $ok = $this->hasObjectPermission();
        
        if($ok !== true){
            return $ok;
        }
        
        return true;
    }
    
    
    protected function hasObjectPermission(){
        
        /*
            Проверяем права на объект
            Доступ к объекту может быть обеспечен в нескольких случаях:
            - Если объект новый
            - Если текущий пользователь - контрактор данного объекта
            - Если у объекта нет контрактора и его id совпадает с id заказа в сессии
            - Если у пользователя есть глобальные права видеть чужие заказы
        */
        
        $allow = false;
        $user = & $this->modx->user;
        $object = & $this->object;
        
        // Проверяем новый ли объект заказа
        if($object->isNew()){
            $allow = true;
        }
        // Иначе проверяем пользователей
        else{
            if($user->id){
                if($object->owner == $user->id){
                    $allow = true;
                }
                else if($this->modx->hasPermission('shopmodx.edit_payments')){
                    $allow = true;
                }
            }
        }
        
        return $allow;
    }
    
    
    protected function prepareObject(& $object){
        parent::prepareObject($object);
        
        
        
        return true;
    }
     
    
    public function beforeSave(){
        
        if($this->object->isNew()){
            $this->object->fromArray(array(
                "createdon"  => time(),
                "createdby"  => $this->modx->user->id ? $this->modx->user->id : NULL,
            ));
        }else{
            $this->object->fromArray(array(
                "editedon"  => time(),
                "editedby"  => $this->modx->user->id,
            ));
        }
        
        
        # print_r($this->object->toArray());
        # return 'Debug';
        
        return parent::beforeSave();
    }
    
    public function success($msg = '',$object = null) {
        
        if(is_object($object)){
            $this->prepareObject($object);
        }
        
        return parent::success($msg, $object);
    }
    
    public function cleanup(){
        
        
        return parent::cleanup();
    }
}

return 'modShopmodxPaymentsObjectProcessor';
