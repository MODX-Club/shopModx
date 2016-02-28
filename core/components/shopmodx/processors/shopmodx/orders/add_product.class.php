<?php

require_once dirname(__FILE__). '/object.class.php';


class modShopmodxOrdersAddproductProcessor extends modShopmodxOrdersObjectProcessor{
    
    
    public function initialize(){
        
        $this->setProperties(array(
            "save_object"   => true,
        ));
        
        $this->setDefaultProperties(array(
            "quantity"      => 1,
        ));
        
        return parent::initialize();
    }
    
    public function beforeSet(){
        
        
        if(!$product_id = (int)$this->getProperty('product_id')){
            
            return "Не был получен ID товара";
        }
        
        $OrderProducts = $this->object->OrderProducts;

        // Пытаемся получить связку в БД
        
        $data = array(
            "order_id"  => $this->object->id,
            "product_id"  => $product_id,
        );
        
        if($OrderProduct = $this->modx->getObject('ShopmodxOrderProduct', $data)){
            $OrderProducts[$OrderProduct->id] = $OrderProduct;
        }
        else{
            $OrderProduct = $this->modx->newObject('ShopmodxOrderProduct', $data);
            $OrderProducts[] = $OrderProduct;
        #     # $OrderProduct = $this->modx->newObject('ShopmodxOrderProduct', $data);
        #     # $OrderProducts[] = $OrderProduct;
        #     # $OrderProduct = $this->modx->newObject('ShopmodxOrderProduct', $data);
        #     # $OrderProducts[] = $OrderProduct;
        }
        
        # $OrderProduct = $this->modx->newObject('ShopmodxOrderProduct', $data);
        
        $result = $this->setOrderProductData($OrderProduct);
        
        if($result !== true){
            return $result;
        }
        
        # print_r($OrderProduct->toArray());
        
#         return $this->success('', $OrderProducts);
#         
#         return;
# 
#         print_r($OrderProduct->toArray());

        # if(!empty($OrderProducts[$product_id])){
        #     print_r('sfsd');
        # }
        
        $this->object->OrderProducts = $OrderProducts;

        # $orderproducts[] = $modx->newObject('ShopmodxOrderProduct', array(
        #     "product_id"    => 4,
        # ));
        # 
        # $object->addMany($orderproducts);
        
        # $this->object->OrderProducts;
        
        # $this->object->set('Products', $this->object->OrderProducts);
        
        # $o = current($this->object->OrderProducts);
        # 
        # print_r($o->toArray());
        
        # die('dfgdfg');
        # return 'Debug';
        
        return parent::beforeSet();
    }
    
    
    protected function setOrderProductData($OrderProduct){
        
        # $properties = $this->getProperties();
        
        # print_r($properties);
        
        # $OrderProduct->fromArray($properties);
        # $OrderProduct->fromArray($this->getProperties());
        
        # Получаем объект товара
        if(!$Product = $OrderProduct->Product){
            
            return "Не был получен товар";
        }
        
        # print_r($Product->toArray());
        
        if(!$ProductData = $Product->ProductData){
            
            return "Не были получены данные товара";
        }
        
        $price = $ProductData->get('price');
        
        # print_r($price);
        # 
        # print_r($OrderProduct->toArray());
        
        $OrderProduct->price = $price;
        
        $OrderProduct->quantity = $OrderProduct->quantity + $this->getProperty('quantity');
        
        # print_r($OrderProduct->toArray());
        
        return true;
    }
    
    public function cleanup() {
        
        $result = parent::cleanup();
        
        if(is_array($result)){
            $result['message'] = "Товар успешно добавлен в корзину";
        }
        
        return $result;
    }
    
#     public function beforeSave(){
#         
#         $orderproducts = array();
# 
#         # $orderproducts[] = $modx->newObject('ShopmodxOrderProduct', array(
#         #     "product_id"    => 4,
#         # ));
#         # 
#         # $object->addMany($orderproducts);
#         
#         # $this->object->OrderProducts;
#         
#         # $this->object->set('Products', $this->object->OrderProducts);
#         
#         # $o = current($this->object->OrderProducts);
#         # 
#         # print_r($o->toArray());
#         
#         return parent::beforeSave();
#     }
    
}


return 'modShopmodxOrdersAddproductProcessor';
