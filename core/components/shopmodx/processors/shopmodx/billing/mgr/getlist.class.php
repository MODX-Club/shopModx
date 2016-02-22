<?php

abstract class modShopmodxBillingMgrGetlistProcessor extends modObjectGetlistProcessor{
    
    public function initialize(){
        $this->setDefaultProperties(array(
            'json'  => false,
        ));
        return parent::initialize();
    }
    
    public function outputArray(array $array, $count = false){
        
        if($this->getProperty('json', false)){
            return parent::outputArray($array, $count);
        }
        
        // else
        return array(
            'success'   => true,
            'message'   => '',
            'total'     => $count,
            'object'    => $array,
        );
    } 
    
    
    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);
        
        $c->select(array(
            "`{$this->classKey}`.*",  
        ));
         
        return $c;
    }
}

return 'modShopmodxBillingMgrGetlistProcessor';