<?php

/*
    Обновляем связку Заказ-Товар
*/

class modShopmodxBillingMgrOrdersProductsUpdateProcessor extends modObjectUpdateProcessor{
    
    public $classKey = 'ShopmodxOrderProduct';
    
    public function initialize(){
        
        // Поиск может быть как по id, так и по связке Заказ-Товар
        // Дело в том, что Заказ-Товар и есть по сути первичный ключ, но ввиду
        // Некоторых тонкостей работы xPDO, был сохранен id в качестве первичного ключа
        
        
        // Если не указан ID, но указаны order_id и product_id,
        // То выполняем поиск этого объекта по этим данным
        if(!$order_product_id = (int)$this->getProperty('order_product_id')){
            if(
                $order_id = (int)$this->getProperty('order_id') 
                AND $product_id = (int)$this->getProperty('product_id')
                AND $object = $this->modx->getObject($this->classKey, array(
                    'order_id'  => $order_id,
                    'product_id'=> $product_id,
                ))
            ){
                // Если объект был найден, устанавливаем значение ID
                $order_product_id = $object->get('id');
            }
        }
        
        // Проверяем наличие актуального ключа
        if(!$order_product_id){
            return 'Не был получен ID записи Заказ-Товар';
        }
        
        //else
        $this->setProperty('id', $order_product_id);
        
        
        return parent::initialize();
    }
    

    public function cleanup() {
        $response = parent::cleanup();
        $response['message'] = $this->getProperty('success_message', 'Заказ успешно обновлен');
        return $response;
    }  
    
}

return 'modShopmodxBillingMgrOrdersProductsUpdateProcessor';