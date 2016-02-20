<?php

/*
    Процессор, определяющий по запрошенному действию какой процессор выполнять
*/

class modShopmodxPublicActionProcessor extends modProcessor{
    
    protected static $actualClassName;
    
    public static function getInstance(modX &$modx,$className,$properties = array()) {
        
        // Здесь мы имеем возможность переопределить реальный класс процессора
        if(!empty($properties['basket_action']) && !self::$actualClassName){
             
            switch($properties['basket_action']){
                
                case 'products_add': 
                case 'products/add': 
                    require_once dirname(dirname(__FILE__)) . '/orders/products/add.class.php';                    
                    self::$actualClassName =  'modBasketWebOrdersProductsAddProcessor';
                    break;
                
                case 'products_getdata':
                    require_once dirname(dirname(__FILE__)) . '/orders/products/getdata.class.php';                    
                    self::$actualClassName =  'modBasketWebOrdersProductsGetdataProcessor';
                    break;
                
                // Это чисто для Ajax-а. Состояние корзины
                case 'getdata':
                    require_once dirname(dirname(__FILE__)) . '/ajax/orders/getdata.class.php';                    
                    self::$actualClassName =  'modBasketWebAjaxOrdersGetdataProcessor';
                    break;
                
                case 'products_remove':
                case 'products/remove':
                    require_once dirname(dirname(__FILE__)) . '/orders/products/remove.class.php';                    
                    self::$actualClassName =  'modBasketWebOrdersProductsRemoveProcessor';
                    break;
                    
                case 'recalculate':
                    require_once dirname(dirname(__FILE__)) . '/orders/recalculate.class.php';                    
                    self::$actualClassName =  'modShopmodxOrdersRecalculateProcessor';
                    break;
                    
                case 'empty_basket':
                    require_once dirname(dirname(__FILE__)) . '/orders/empty.class.php';                    
                    self::$actualClassName =  'modBasketWebOrdersEmptyProcessor';
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
