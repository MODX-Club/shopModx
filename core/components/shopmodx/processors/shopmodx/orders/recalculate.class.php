<?php

require_once dirname(__FILE__) . '/object.class.php';

class modShopmodxOrdersRecalculateProcessor extends modShopmodxOrdersObjectProcessor{
    
    
    public function initialize() {
        
        # $this->setDefaultProperties(array(
        #     "new_object"   => true,        // Флаг, что это новый объект
        #     "save_object"   => false,       // Флаг, что объект надо сохранять
        #     'order_id'    => $this->modx->shopmodx->getActiveOrderID(),
        # ));
        
        $this->setProperties(array(
            "new_object"   => false,        // Флаг, что это новый объект
            "save_object"   => true,       // Флаг, что объект надо сохранять
            # 'order_id'    => $this->modx->shopmodx->getActiveOrderID(),
        ));
        
        # if($order_id = (int)$this->getProperty('order_id')){
        # #     
        #     $this->setProperties(array(
        #         "save_object"   => true,       // Флаг, что объект надо сохранять
        #     ));
        # }
        
        # print_r($this->properties);
        
#         $this->initializeObject();
#         
        # print_r($this->object->toArray());
        # if (empty($this->object)){
        #     return $this->modx->lexicon($this->objectType.'_err_nfs');
        # }
        
        # return 'sfsdf';
# 
#         if ($this->getProperty('save_object') && $this->checkSavePermission && $this->object instanceof modAccessibleObject && !$this->object->checkPolicy('save')) {
#             return $this->modx->lexicon('access_denied');
#         }

        return parent::initialize();
    }
    
    
    public function beforeSave(){
        
        $position_id = (int)$this->getProperty('position_id');
        $quantity = (int)$this->getProperty('quantity');
        
        if(!$position_id){
            return "Не указан ID товароной позиции";
        }

        if(!isset($quantity)){
            return "Не указано количество товаров в заказе";
        }

        // $this->modx->log(1, print_r($this->object->toArray(), 1), "FILE");

        $OrderProducts = $this->object->OrderProducts;
        
        foreach($OrderProducts as & $OrderProduct){

            if($OrderProduct->id != $position_id){
                continue;
            }
            
            $OrderProduct->quantity = $quantity;

        }
        
        unset($OrderProduct);
        
        return parent::beforeSave();
    }
    
    
    // public function cleanup() {
        
    //     return $this->success('Корзина пересчитана', $this->object->toArray());
    // } 
    
    public function cleanup($msg = '') {
        
        return parent::cleanup('Корзина пересчитана');
    } 
    
}

return 'modShopmodxOrdersRecalculateProcessor';
