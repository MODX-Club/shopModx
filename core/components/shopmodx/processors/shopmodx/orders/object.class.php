<?php

require_once MODX_CORE_PATH . 'components/modxsite/processors/site/web/object.class.php';

class modShopmodxOrdersObjectProcessor extends modSiteWebObjectProcessor{
    
    public $classKey = 'ShopmodxOrder';
    
    public function initialize() {
        
        $this->setDefaultProperties(array(
            "new_object"   => true,        // Флаг, что это новый объект
            "save_object"   => false,       // Флаг, что объект надо сохранять
            'order_id'    => $this->modx->shopmodx->getActiveOrderID(),
        ));
        
        if($order_id = (int)$this->getProperty('order_id')){
            
            $this->setProperties(array(
                "new_object"   => false,        // Флаг, что это новый объект
                # "save_object"   => true,       // Флаг, что объект надо сохранять
                'id'    => $order_id,
            ));
        }
        
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
    
    
    protected function prepareObject(& $object){
        parent::prepareObject($object);
        
        $OrderProducts = $object->OrderProducts;
        
        $positions = 0;
        $quantity = 0;
        $sum = 0;
        $products_ids = array();        // Массив ID-шников товаров
        
        $OrderProductsData = array();
        
        foreach($OrderProducts as & $OrderProduct){
            $positions++;
            $quantity += $OrderProduct->quantity;
            $sum += $OrderProduct->quantity * $OrderProduct->price;
            $OrderProduct->set('_Product', $OrderProduct->Product->toArray());
            $products_ids[] = $OrderProduct->product_id;
            $OrderProductsData[$OrderProduct->id] = $OrderProduct;
        }
        
        $object->set('_OrderProducts', $OrderProductsData);
        
        $object->fromArray(array(
            "positions" => $positions,
            "quantity" => $quantity,
            "sum" => $sum,
            "products_ids" => $products_ids,
        ));
        
        return true;
    }
    
    
    # protected function initializeObject(){
    #     
    #     if($this->getProperty('new_object')){
    #         $this->object = $this->newObject($this->classKey);
    #     }
    #     else{
    #         $primaryKey = $this->getProperty($this->primaryKeyField,false);
    #         if (empty($primaryKey)){
    #             return $this->modx->lexicon($this->objectType.'_err_ns');
    #         }
    #         $this->object = $this->getObject($this->classKey,$primaryKey);
    #     }
    #     
    #     return $this->object;
    # }
    
    
    public function beforeSave(){
        
        if($this->object->isNew()){
            $this->object->fromArray(array(
                "createdon"  => time(),
                "createdby"  => $this->modx->user->id,
            ));
        }else{
            $this->object->fromArray(array(
                "editedon"  => time(),
                "editedby"  => $this->modx->user->id,
            ));
        }
        
        return parent::beforeSave();
    }
    
    public function success($msg = '',$object = null) {
        
        if(is_object($object)){
            $this->prepareObject($object);
        }
        
        return parent::success($msg, $object);
    }
    
    public function cleanup(){
        
        $this->modx->shopmodx->setActiveOrderID($this->object->id);
        
        return parent::cleanup();
    }
}

return 'modShopmodxOrdersObjectProcessor';
