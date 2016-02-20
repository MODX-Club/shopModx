<?php

/*
    Добавляем товар к заказу.
    Если заказа еще нет, то создаем его.
    Это сделано для того, чтобы можно было потом увидеть даже не оформленные заказы
    
    product_id - ShopmodxProduct (не документ, а именно товар)
    
    Так же следует учитывать тонкость, что это именно create-процессор, 
    то есть он только создает записи. Ежели у нас связка Заказ-Товар уже существует,
    то будет вызван update-процессор. Смотрите метод getInstance()
    
    UPD: так как свич на уровне getInstance сопровождается рядом трудностей, 
    решено переписать класс, чтобы свич выполнялся на уровне метода process()
*/


class modShopmodxBillingMgrOrdersProductsAddProcessor extends modProcessor{
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            'quantity'          => 1,
            'success_message'   => 'Товар успешно добавлен в корзину',
        ));
        
        return parent::initialize();
    }
    
    public function process(){
        
        /*
            Пытаемся найти объект. Если найден, то обновляем.
            Если нет, то добавляем.
        */
        $this->findExistingObject();
        
        if($this->getProperty('order_product_id')){
            $action = 'update';
        }
        else{
            $action = 'create';
        }
        
        // Выполняем соответствующий процессор
        if(!$response = $this->modx->runProcessor("orders/products/{$action}", 
            $this->getProperties(),
            array(
                "processors_path"  => dirname(dirname(dirname(__FILE__))). '/',
            )
        )){
            $error = "Ошибка выполнения запроса";
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[Billing] - ".__CLASS__." - {$error}");
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($this->getProperties(), true));
            return $this->failure($error);
        }
        
        // else     
        return $this->processResponse($response);
    }
    
    private function findExistingObject(){
        
        /*
            Если не указан явно ID записи Заказ-Товар, то пробуем его найти
        */
        
        if(
            !$this->getProperty('order_product_id')
            AND $object = $this->getObject()
        ){
            
            // Если объект был найден, устанавливаем новые значения
            // Надо так же отметить, что это процессор не должен принимать 
            // на вход параметр id, поэтому смело его устанавливаем (даже
            // если перетираем уже установленный), чтобы в update-процессоре
            // не выполнять лишних поисков
            $this->setProperty('order_product_id', $object->get('id'));
            
            // Плюсуем общее количество данного товара в этом заказе
            $this->setProperty('quantity', $this->getProperty('quantity') + $object->get('quantity')); 
            
        }
        return;
    }
    
    protected function getObject(){
        $object = null;
        if(
            $order_id = $this->getProperty('order_id', null) 
            AND $product_id = $this->getProperty('product_id', null)
        ){
            $object = $this->modx->getObject('ShopmodxOrderProduct', array(
                'order_id'  => $order_id,
                'product_id'=> $product_id,
            ));
        }
        
        return $object;
    }
    
    protected function processResponse($response){
        return $response->getResponse();
    }    
    
}

return 'modShopmodxBillingMgrOrdersProductsAddProcessor';