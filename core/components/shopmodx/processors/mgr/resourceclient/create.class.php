<?php

require_once dirname(dirname(__FILE__)).'/resource/create.class.php';

class ShopmodxResourceClientCreateProcessor extends ShopmodxResourceCreateProcessor{
    public $objectType = 'shopmodxresourceclient';
    public $relatedObjectRequired = true;
    
    public function beforeSet() {
        if(!$this->getProperty('sm_legal_form')){
            $error = "Не указана правовая форма.";
            if(!isset($this->properties['sm_legal_form'])){
                $error .= " Пожалуйста, используйте полную форму создания документа.";
            }
            return $error;
        }
        
        if(!$this->getProperty('longtitle')){
            $this->addFieldError('longtitle',$message = 'Укажите расширенный заголовок');
        }
        
        if($this->hasErrors()){
            return "Пожалуйста, исправьте ошибки в форме.";
        } 
        
        //  set related object data
        $ro_data = array(
            'sm_name' => $this->getProperty('pagetitle'),
            'sm_fullname' => $this->getProperty('longtitle'),
        );
        
        $this->setProperties($ro_data);
        return parent::beforeSet();
    } 
}
return 'ShopmodxResourceClientCreateProcessor';