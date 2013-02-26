<?php

require_once dirname(dirname(__FILE__)).'/resource/create.class.php';

class ShopmodxResourceLegalFormCreateProcessor extends ShopmodxResourceCreateProcessor{
    public $classKey = 'ShopmodxResourceLegalForm';
    public $objectType = 'shopmodxresourcewarehouse';
}
return 'ShopmodxResourceLegalFormCreateProcessor';