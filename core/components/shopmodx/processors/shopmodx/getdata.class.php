<?php

require_once MODX_CORE_PATH . '/components/modxsite/processors/site/web/getdata.class.php';

class modShopmodxGetdataProcessor extends modSiteWebGetdataProcessor{
    
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            "grid"  => false,
            "format"  => '',
        ));
        
        return parent::initialize();
    }
    
    
    public function outputArray(array $array, $count = false){
        
        $result = parent::outputArray($array, $count);
        
        if($this->getProperty('grid')){
            
            $result['results'] = array_values($result['object']);
            
            unset($result['object']);
        }
        
        if($this->getProperty('format') == 'json'){
            
            $result = json_encode($result);
        }
        
        return $result;
    }
}

return 'modShopmodxGetdataProcessor';
