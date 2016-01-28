<?php
class ShopmodxProductData extends xPDOSimpleObject {

    public static function load(xPDO & $xpdo, $className, $criteria, $cacheFlag= true) {
        
        $className = 'modResource';
        if($object = parent::load($xpdo, $className, $criteria, $cacheFlag)){
            
            if($object instanceof msProduct){
                $className = 'msProductData';
                $object = parent::load($xpdo, $className, $criteria, $cacheFlag);
            }
        }
        
        return $object;
    }
}