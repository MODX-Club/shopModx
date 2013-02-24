<?php

require_once dirname(dirname(__FILE__)).'/resource/create.class.php';

class ShopmodxResourceLegalFormCreateProcessor extends ShopmodxResourceCreateProcessor{
    public $classKey = 'ShopmodxResourceLegalForm';
    public $objectType = 'shopmodxresourcewarehouse';
    
    public function beforeSet() {
        if(!$this->getProperty('pagetitle')){
            $this->addFieldError('pagetitle', 'Не заполнено название');
            return false;
        }
        return parent::beforeSet();
    }
}
return 'ShopmodxResourceLegalFormCreateProcessor';