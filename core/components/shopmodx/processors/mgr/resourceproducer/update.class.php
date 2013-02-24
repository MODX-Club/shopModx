<?php

require_once dirname(dirname(__FILE__)).'/resource/update.class.php';

class ShopmodxResourceProducerUpdateProcessor extends ShopmodxResourceUpdateProcessor{
    public $classKey = 'ShopmodxResourceProducer';
    public $objectType = 'shopmodxresourceproducer';
    
}
return 'ShopmodxResourceProducerUpdateProcessor';