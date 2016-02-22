<?php

class modShopmodxOrdersOrderdataRemoveProcessor extends modObjectremoveProcessor{
    
    public $classKey = 'ShopmodxOrderProduct';
    public $permission = 'remove';
    
    
    public function initialize(){
        
        if(!$id = (int)$this->getProperty('id')){
            
            return 'Не был получен id элемента';
        }
        
        // else
        $this->setProperty('id', $id);
        
        return parent::initialize();
    }
    
    
    public function beforeRemove() { 
        
        if($this->object->id != (int)$this->getProperty('id')){
            return "Не совпадает ID товара";
        }
        
        return parent::beforeRemove();
    }
    
}

return 'modShopmodxOrdersOrderdataRemoveProcessor';


