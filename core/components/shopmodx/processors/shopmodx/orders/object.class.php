<?php

require_once MODX_CORE_PATH . 'components/modxsite/processors/site/web/object.class.php';


class modShopmodxOrdersObjectProcessor extends modSiteWebObjectProcessor{
    
    public $classKey = 'ShopmodxOrder';
    
    public function initialize() {
        
        // print_r($this->properties);

        $this->unsetProperty('id');
        
        $order_id = $this->modx->shopmodx->getActiveOrderID();
        
        $this->setDefaultProperties(array(
            "new_object"   => true,        // Флаг, что это новый объект
            "save_object"   => false,       // Флаг, что объект надо сохранять
            'order_id'    => $order_id,
            'show_canceled'  => false,      // Показывать ли отмененные
        ));
        
        if($order_id = (int)$this->getProperty('order_id')){
            
            $this->setProperties(array(
                "new_object"   => false,        // Флаг, что это новый объект
                # "save_object"   => true,       // Флаг, что объект надо сохранять
                'id'    => $order_id,
            ));
        }

        $this->unsetProperty("contractor");
        $this->unsetProperty("createdby");
        $this->unsetProperty("createdon");
        // $this->unsetProperty("discount");
        $this->unsetProperty("editedby");
        $this->unsetProperty("editedon");
        $this->unsetProperty("manager");
        $this->unsetProperty("number_history");
        
        // print_r($this->properties);
        
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
        
        if($this->modx->context->key != 'mgr' AND empty($this->modx->smarty)){
            $this->modx->invokeEvent('OnHandleRequest');
        }
        
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
    
    
    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);
        
        $alias = $c->getAlias();

        $c->select(array(
            "{$alias}.*",
        ));

        $where = array(
            "status_id:!=" => 7,
        );
        
        $c->where($where);
        
        return $c;
    }
    
    
    protected function hasObjectPermission(){
        global $site_id;
        
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
                if($object->contractor == $user->id || $object->createdby == $user->id){
                    $allow = true;
                }
                else if($this->modx->hasPermission('shopmodx.edit_orders')){
                    $allow = true;
                }
            }
            else{
                if(isset($_SESSION['order_id']) AND $_SESSION['order_id'] == $this->object->id){
                    $allow = true;
                }
            }
            
            if(!$allow AND $site_id == $this->getProperty('site_id')){
                $allow = true;
            }
        }
        
        return $allow;
    }
    
    
    protected function prepareObject(& $object){
        parent::prepareObject($object);
        
        $OrderProducts = $object->OrderProducts;
        
        $positions = 0;
        $quantity = 0;
        $sum = 0;
        $original_sum = 0;
        $products_ids = array();        // Массив ID-шников товаров
        
        $OrderProductsData = array();
        
        $show_canceled = $this->getProperty('show_canceled');
        
        foreach($OrderProducts as & $OrderProduct){
            
            /*
                Если не считаем отмененные товары, то пропускаем их из счета
            */
            if(!$OrderProduct->quantity AND !$show_canceled){
                unset(
                    $OrderProducts[$OrderProduct->id],
                    $object->_relatedObjects['OrderProducts'][$OrderProduct->id]
                );
                continue;
            }
            
            $positions++;
            $quantity += $OrderProduct->quantity;
            $sum += $OrderProduct->quantity * $OrderProduct->price;
            $products_ids[] = $OrderProduct->product_id;
            
            if($Product = $OrderProduct->Product){
                // print_r($OrderProduct->Product->toArray());
                $image = $OrderProduct->Product->getTVValue(7);

                $OrderProduct->fromArray(array(
                    "price_old" => $OrderProduct->Product->price_old,
                ));

                $OrderProduct->set('_Product', array_merge(
                    $OrderProduct->Product->toArray(),
                    array(
                        "image" => $image ? "{$image}" : "assets/images/products/No-Photo.jpg",
                    )
                ));
            }
            
            $OrderProductsData[$OrderProduct->id] = $OrderProduct->toArray();
        }
        
        $original_sum = $sum;
        
        if($object->discount){
            $sum = round($sum * ((100 - $object->discount) / 100), 2);
        }
        
        $object->set('_OrderProducts', array_values((array)$OrderProductsData));
        
        $object->fromArray(array(
            "positions" => $positions,
            "total" => $quantity,
            "quantity" => $quantity,
            "sum" => $sum,
            "discount" => $object->discount,
            "original_sum" => $original_sum,
            "products_ids" => $products_ids,
        ));
        
        return true;
    }
    
    
    
    /*
        Удаление товара из корзины
    */
    public function removeProduct(){
        
        if(!$product_key = (int)$this->getProperty('product_key')){
            return "Не был указан удаляемый товар";
        }
        
        $OrderProducts = $this->object->OrderProducts;
        
        # foreach($OrderProducts as $OrderProduct){
        #     print "<br />" . $OrderProduct->id;
        # }
        # 
        # return 'sad';
        
        if(empty($OrderProducts[$product_key])){
            return "Не был получен товар";
        }
        
        $OrderProducts[$product_key]->quantity = 0;
        
        $this->object->OrderProducts = $OrderProducts;
        
        # return 'true';
        return true;
    }
    
    
    public function beforeSet(){
        
        /*
            Проверяем, можно ли очищать корзину.
            Можно только если статус - новый
        */
        
        # print_r($this->object->toArray());
        # 
        # return 'Debug';
        
        // Проверка на изменение статусов
        switch($this->getProperty('status_id')){
            
            case 7:
                
                if($this->object->get('status_id') != '1'){
                    $error = "Данную корзину нельзя очистить";
                    
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, __CLASS__ . " - {$error}");
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($this->getProperties(), true));
                    
                    return $error;
                }
                break;
            
            default:;
        }
        
        
        return parent::beforeSet();
    }
    
    
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
            
            if($this->object->isDirty('status_id')){
                $this->object->number_history++;
            }
        }
        
        if(
            $OnPrepareObject = $this->modx->invokeEvent("OnShopModxOrderBeforeSave",array(
                'object' => & $this->object,
            ))
            AND is_array($OnPrepareObject)
        ){
            
            # var_dump($OnPrepareObject);
            foreach($OnPrepareObject as $response){
                $prepared = $this->processEventResponse($response);
                if (!empty($prepared)) {
                    return $prepared;
                }
            }
        }
        
        
        return parent::beforeSave();
    }


    public function afterSave() { 

        $this->object = $this->modx->getObject($this->classKey, $this->object->id);

        return parent::afterSave(); 
    }
    
    public function success($msg = '',$object = null) {
        
        if(is_object($object)){
            $this->prepareObject($object);
        }
        
        return parent::success($msg, $object);
    }
    
    // public function cleanup($msg = ''){
        
    //  // Устанавливает ID в сессию, поэтому нельзя его вызывать кроме как в создании заказа
    //     $this->modx->shopmodx->setActiveOrderID($this->object->id);
        
    //     return $this->success($msg, $this->object);
    // }
}


return 'modShopmodxOrdersObjectProcessor';
