<?php

$this->loadClass('modProcessor', '', false, true);

# require_once MODX_CORE_PATH . 'model/modx/modprocessor';

# require_once MODX_CORE_PATH . '/components/modxsite/processors/site/web/object.class.php';

class shopmodxService extends modObjectProcessor{
    
#     public $checkSavePermission = false;
#     
#     
#     public function initialize() {
#         
#         $this->setProperties(array(
#             "new_object"   => true,        // Флаг, что это новый объект
#             "save_object"   => false,       // Флаг, что объект надо сохранять
#         ));
# 
#         return parent::initialize();
#     }

    public function process(){
        
    }
    
    public function getActiveOrderId(){
        $order_id = null;
        
        // Проверяем, авторизован ли пользователь
        if(
            $user_id  = (int)$this->modx->user->get('id')
        ){
            $c = $this->modx->newQuery('ShopmodxOrder', array(
                'contractor'    => $user_id,
            ));
            $c->sortby('id', 'DESC');
            $c->limit(1);
            if(
                $order = $this->modx->getObject('ShopmodxOrder', $c)
                AND $order->status_id == '1'
            ){
                $order_id = $order->id;
            }
        }
        
        if(!$order_id AND !empty($_SESSION['order_id'])){
            $order_id = $_SESSION['order_id'];
        }
        
        return $order_id;
    }
    
    public function setActiveOrderId($order_id){
        
        $_SESSION['order_id'] = $order_id;
        
        return $order_id;
    }
    
    public function flushSession(){
        if(isset($_SESSION['order_id'])){
            unset($_SESSION['order_id']);
        }
        return;
    }
}

