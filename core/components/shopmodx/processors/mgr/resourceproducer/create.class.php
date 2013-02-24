<?php

require_once dirname(dirname(__FILE__)).'/resource/create.class.php';

class ShopmodxResourceProducerCreateProcessor extends ShopmodxResourceCreateProcessor{
    public $classKey = 'ShopmodxResourceProducer';
    public $objectType = 'shopmodxresourceproducer';
    
}
return 'ShopmodxResourceProducerCreateProcessor';