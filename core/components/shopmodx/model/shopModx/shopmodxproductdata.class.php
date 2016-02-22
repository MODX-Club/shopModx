<?php
class ShopmodxProductData extends xPDOSimpleObject {

    public static function load(xPDO & $xpdo, $className, $criteria, $cacheFlag= true) {
        
        $className = 'modResource';
        if($object = parent::load($xpdo, $className, $criteria, $cacheFlag)){
            
            
            // minishop
            if($object instanceof msProduct){
                $className = 'msProductData';
                $object = parent::load($xpdo, $className, $criteria, $cacheFlag);
            }
            
            // shopmodx
            else if($object instanceof ShopmodxResourceProduct){
                if($object = parent::load($xpdo, $className, $criteria, $cacheFlag)){
                    
                    $className = 'ShopmodxProduct';
                    $criteria = array(
                        "resource_id"   => $object->id,
                    );
                    
                    if($object = parent::load($xpdo, $className, $criteria, $cacheFlag)){
                        $object->set('price', $object->sm_price);
                        $object->set('article', $object->sm_article);
                        $object->set('currency', $object->sm_currency);
                    }
                }
            }
        }
        
        return $object;
    }
}