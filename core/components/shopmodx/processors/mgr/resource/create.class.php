<?php

require_once MODX_CORE_PATH.'model/modx/modprocessor.class.php';
require_once MODX_CORE_PATH.'model/modx/processors/resource/create.class.php';

class ShopmodxResourceCreateProcessor extends modResourceCreateProcessor{
    public $objectType = 'shopmodxresource';
    public $relatedObjectRequired = false;
    
    
    public function beforeSave() {
        $cansave = $this->getRelatedObject();
        if($cansave != true){
            return $cansave;
        }
        
        return parent::beforeSave();
    }
    
    public function getRelatedObject(){
        if(!$this->relatedObjectRequired){
            return true;
        }
        if(!$this->object->addObject($this->getProperties())){
            return "Can not add related object";
        }
        return true;
    }
}

return 'ShopmodxResourceCreateProcessor';
