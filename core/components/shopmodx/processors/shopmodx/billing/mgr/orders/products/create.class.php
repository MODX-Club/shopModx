<?php

/*
    Создаем запись Заказ-Товар
*/

class modShopmodxBillingMgrOrdersProductsCreateProcessor extends modObjectCreateProcessor{
    
    public $classKey = 'ShopmodxOrderProduct';
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            'quantity'          => 1,
        ));
        
        if(!(int)$this->getProperty('product_id')){
            return 'Не был указан ID товара';
        } 
        
        return parent::initialize();
    }
    
    public function beforeSet(){
        
        // Проверяем наличие товара
        # if(!$product = $this->modx->getObject('ShopmodxProductData', (int)$this->getProperty('resource_id'))){
        #     $error = 'Не был получен товар';
        #     $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[Billing] - {$error}");
        #     $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($this->getProperties(), true));
        #     return $error;
        # }
        
        if(
            !$product = $this->modx->getObject('modResource', (int)$this->getProperty('product_id'))
            OR !$product->ProductData
        ){
            $error = 'Не был получен товар';
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[Billing] - {$error}");
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($this->getProperties(), true));
            return $error;
        }
        
        
        # print_r($product->toArray());
        # 
        # return;
        
        // Если указан ID заказа, то пытаемся получить данный заказ.
        if($order_id = $this->getProperty('order_id')){
            
            // Если он не был получен, то возвращаем ошибку.
            if(!$this->modx->getObject('ShopmodxOrder', $order_id)){
                $error = "Не был получен заказ";
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[Billing] - {$error}");
                return $error;
            }
        }
        else{
            if(!$response = $this->modx->runProcessor('orders/create', array(
                
            ),array(
                "processors_path"  => dirname(dirname(dirname(__FILE__))). '/',
            ))){
                $error = "Не был создан новый заказ";
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[Billing] - {$error}");
                return $error;
            }
            
            // else
            if($response->isError() OR !$object = $response->getObject() OR !$order_id = $object['id']){
                $error = "Не был создан новый заказ";
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[Billing] - {$error}");
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($response->getResponse(), 1));
                return $error;
            }
            
            // else
            // Устанавливаем значение ID заказа
            $this->setProperty('order_id', $order_id);
        }
        
        # return 'Debug';
        
        // Устанавливаем значение цены и валюты
        // В дальнейшем в этом месте можно будет вклиниться с переопределением цен
        $this->setPrices($product);
        
        return parent::beforeSet();
    }

    //переопределив эту функцию, можно изменить алгоритм определения цены (например, для товаров с опциями)
    protected function setPrices(modResource & $product){
        
        # print_r($product->ProductData->toArray());
        # 
        # return;
        
        $data = array(
            'price' => $product->ProductData->price,    
            'currency_id' => $product->ProductData->currency_id,    
        );
        
        // Получаем данные товара с учетом курсов валют
        if(
            $response = $this->modx->runProcessor('web/catalog/products/getdata',
            array(
                "where" => array(
                    "id"    => $product->id,    
                ),
                "current"       => 1,
                "showhidden"    => 1,
                "showunpublished"    => 1,
            ), array(
            'processors_path' => MODX_CORE_PATH . 'components/modxsite/processors/',
            ))
            AND !$response->isError()
            AND $object = $response->getObject()
        ){
            $data = array(
                'price' => $object['price'],    
                'currency_id' => isset($object['currency_id']) ? $object['currency_id'] : null,    
            );
        }
        $this->modx->error->reset(); 
        
        $this->object->fromArray($data);
        
        return true;
    }
    
    public function cleanup() {
        $response = parent::cleanup();
        $response['message'] = $this->getProperty('success_message', 'Товар успешно добавлен');
        return $response;
    }    
}

return 'modShopmodxBillingMgrOrdersProductsCreateProcessor';
