<?php

/*
    Процессор, определяющий по запрошенному действию какой процессор выполнять
*/

class modShopmodxPublicActionProcessor extends modProcessor{
    
    protected static $actualClassName;
    
    public static function getInstance(modX &$modx,$className,$properties = array()) {
        
        
        $request_body = file_get_contents('php://input');

        if($request_body AND $data = json_decode($request_body, 1)){
            $properties = array_merge($properties, $data);
        }
        
        foreach($properties as $field => & $value){

            if(!is_scalar($value)){
                continue;
            }

            $v = (string)$value;

            if($v === "null"){
                $value = null;
            }
            else if($v === "true"){
                $value = true;
            }
            else if($v === "false"){
                $value = false;
            }
            else if($v === "NaN"){
                unset($properties[$field]);
            }
            else if($v === "undefined"){
                unset($properties[$field]);
            }
        }

        // Удаляем параметр корзины
        unset($properties['order_id']);
        
        $action = !empty($properties['basket_action']) ? $properties['basket_action'] : (!empty($properties['pub_action']) ? $properties['pub_action'] : '');
        
        # print '<pre>';
        # print_r($properties);
        # 
        # print $action;
        # 
        # exit;

        // $modx->log(1, print_r($properties,1), "FILE");
        
        // Здесь мы имеем возможность переопределить реальный класс процессора
        if($action AND !self::$actualClassName){
             
            switch($action){
                
                case 'products_add': 
                case 'products/add': 
                case 'orders/add_product': 
                    require_once dirname(dirname(__FILE__)) . '/orders/add_product.class.php';                    
                    self::$actualClassName =  'modShopmodxOrdersAddproductProcessor';
                    break;
                
                case 'products_getdata':
                    require_once dirname(dirname(__FILE__)) . '/orders/products/getdata.class.php';                    
                    self::$actualClassName =  'modBasketWebOrdersProductsGetdataProcessor';
                    break;
                
                // Это чисто для Ajax-а. Состояние корзины
                // case 'getdata':
                // case 'orders/getdata':
                //     require_once dirname(dirname(__FILE__)) . '/orders/object.class.php';                    
                //     self::$actualClassName =  'modShopmodxOrdersObjectProcessor';
                //     break;

                case 'getdata':
                case 'orders/getdata':
                    require_once dirname(dirname(__FILE__)) . '/orders/getdata.class.php';                    
                    self::$actualClassName =  'modShopmodxOrdersGetdataProcessor';
                    break;

                // Текущий заказ пользователя
                case 'order/own/getdata':
                    require_once dirname(dirname(__FILE__)) . '/orders/own/object.class.php';                    
                    self::$actualClassName =  'modShopmodxOrdersOwnObjectProcessor';
                    break;

                case 'orders/products/getdata':
                    require_once dirname(dirname(__FILE__)) . '/orders/products/getdata.class.php';                    
                    self::$actualClassName =  'modShopmodxOrdersProductsGetdataProcessor';
                    break;
                
                case 'products_remove':
                case 'products/remove':
                    require_once dirname(dirname(__FILE__)) . '/orders/products/remove.class.php';                    
                    self::$actualClassName =  'modShopmodxOrdersProductsRemoveProcessor';
                    break;
                    
                case 'order/submit':
                    require_once dirname(dirname(__FILE__)) . '/orders/submit.class.php';                    
                    self::$actualClassName =  'modShopmodxOrdersSubmitProcessor';
                    break;
                    
                case 'recalculate':
                    require_once dirname(dirname(__FILE__)) . '/orders/recalculate.class.php';                    
                    self::$actualClassName =  'modShopmodxOrdersRecalculateProcessor';
                    break;
                    
                case 'empty_basket':
                    require_once dirname(dirname(__FILE__)) . '/orders/empty.class.php';                    
                    self::$actualClassName =  'modShopmodxOrdersEmptyProcessor';
                    break;
                    
                
                default:;
            }
            
            
            /*
                Если переопределяете в дочернем процессоре,
                не забудьте скопировать и это
            */
            /*if($actualClassName){
                $className = $actualClassName;
                unset($properties['basket_action']);
            }*/
        }
        
        if(self::$actualClassName){
            $className = self::$actualClassName;
        }

        return parent::getInstance($modx,$className,$properties);
    }    
     
    
    public function process(){
        $error = 'Действие не существует или не может быть выполнено';
        $this->modx->log(xPDO::LOG_LEVEL_ERROR, __CLASS__ . " - {$error}");
        $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($this->getProperties(), true));
        return $this->failure($error);
    }
    
}

return 'modShopmodxPublicActionProcessor';
