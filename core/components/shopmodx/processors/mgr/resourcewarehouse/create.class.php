<?php

require_once dirname(dirname(__FILE__)).'/resource/create.class.php';

class ShopmodxResourceWarehouseCreateProcessor extends ShopmodxResourceCreateProcessor{
    public $classKey = 'ShopmodxResourceWarehouse';
    public $objectType = 'shopmodxresourcewarehouse';
}
return 'ShopmodxResourceWarehouseCreateProcessor';