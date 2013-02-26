<?php

require_once MODX_CORE_PATH.'model/modx/modprocessor.class.php';
require_once MODX_CORE_PATH.'model/modx/processors/resource/create.class.php';

class ShopmodxResourceCreateProcessor extends modResourceCreateProcessor{
    public $classKey = 'ShopmodxResource';
    public $objectType = 'shopmodxresource';
    
    public function getRelatedObject(){
        if(!$this->object->addObject($this->getProperties())){
            return "Can not add related object";
        }
        return true;
    }
}

return 'ShopmodxResourceCreateProcessor';
