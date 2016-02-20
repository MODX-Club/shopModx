<?php

require_once dirname(dirname(__FILE__)) . '/index.class.php';

class BasketControllersMgrOrdersPrintManagerController extends ControllersMgrManagerController{
    
    /** @var bool Set to false to prevent loading of the header HTML. */
    public $loadHeader = false;
    /** @var bool Set to false to prevent loading of the footer HTML. */
    public $loadFooter = false;
    
    public static function getInstance(modX &$modx, $className, array $config = array()) {
        $className = __CLASS__;
        return new $className($modx, $config);
    }
    
    public function process(array $scriptProperties = array()) {
        
        if(
            empty($scriptProperties['order_id'])
            OR !$order_id = (int)$scriptProperties['order_id']
        ){
            
            return $this->failure('Не был указан ID заказа');
        }
        
        $this->modx->error->reset();
        
        $namespace = 'basket';        // Неймспейс комопонента
        
        
        $response = $this->modx->runProcessor('mgr/shop/order/getlist',
        array(  
            "order_id"  => $order_id, 
            "limit"     => 1,
            "json"  => false,
            
        ), array(
            'processors_path' => $this->modx->getObject('modNamespace', $namespace)->getCorePath().'processors/',
        ));
        
        if($response->isError()){
            if(!$message = $response->getMessage()){
                $message = "Ошибка выполнения запроса";
            }
            return $this->failure($message);
        }
        
        if(!$object = $response->getObject()){
            return $this->failure("Не был получен объект заказа");
        }
        
        $order = current($object);
        
        
        $response = $this->modx->runProcessor('mgr/shop/orderdata/getlist',
        array(  
            'grid'  => false, 
            "order_id"  => $order_id, 
        ), array(
            'processors_path' => $this->modx->getObject('modNamespace', $namespace)->getCorePath().'processors/',
        ));
        
        if($response->isError()){
            if(!$message = $response->getMessage()){
                $message = "Ошибка выполнения запроса";
            }
            return $this->failure($message);
        }
        
        if(!$order_products = $response->getObject()){
            return $this->failure("Не был получен объект заказа");
        }

        # print '<pre>';
        # print_r($object);
        
        return array_merge((array)parent::process(), array(
            "order" => $order,
            "order_products"    => $order_products,
        ));
    }
    
    public function getTemplateFile(){
        return 'orders/print.tpl'; 
    }
    
}
