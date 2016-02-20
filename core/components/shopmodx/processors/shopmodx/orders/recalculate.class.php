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

        if(!$this->getProperty('quantity')){
            return "Не указано количество товаров в заказе";
        }

        return parent::initialize();
    }
    
    
    public function beforeSave(){
        
        $quantity = (array)$this->getProperty('quantity');
        
        $OrderProducts = $this->object->OrderProducts;
        
        foreach($OrderProducts as & $OrderProduct){
            $OrderProduct->quantity = isset($quantity[$OrderProduct->id]) ? $quantity[$OrderProduct->id] : 0;
            # print_r($OrderProduct->toArray());
        }
        
        unset($OrderProduct);
        
        # print count($OrderProducts);
        
        return parent::beforeSave();
    }
    
}

return 'modShopmodxOrdersRecalculateProcessor';
