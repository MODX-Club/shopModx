<?php

require_once MODX_CORE_PATH.'model/modx/modprocessor.class.php';
require_once MODX_CORE_PATH.'model/modx/processors/resource/create.class.php';

class ShopmodxResourceCreateProcessor extends modResourceCreateProcessor{
    public $classKey = 'ShopmodxResource';
    public $objectType = 'shopmodxresource';
    
    public function beforeSet() {
        //  set relative object data
        $ro_data = array(
            'sm_name' => $this->getProperty('pagetitle'),
        );
        $this->setProperties($ro_data);
        return parent::beforeSet();
    }
    
    public function beforeSave() {
        $cansave = $this->getRelatedObject();
        if($cansave != true){
            return $cansave;
        }
        
        return parent::beforeSave();
    }
    
    public function getRelatedObject(){
        if(!$this->object->addObject($this->getProperties())){
            return "Can not add related object";
        }
        return true;
    }
}

return 'ShopmodxResourceCreateProcessor';
