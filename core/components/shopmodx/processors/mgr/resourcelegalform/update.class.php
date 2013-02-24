<?php

require_once dirname(dirname(__FILE__)).'/resource/update.class.php';

class ShopmodxResourceLegalFormUpdateProcessor extends ShopmodxResourceUpdateProcessor{
    public $classKey = 'ShopmodxResourceLegalForm';
    public $objectType = 'shopmodxresourcelegalform';
}
return 'ShopmodxResourceLegalFormUpdateProcessor';