<?php

require_once dirname(dirname(__FILE__)).'/resource/update.class.php';

class ShopmodxResourceWarehouseUpdateProcessor extends ShopmodxResourceUpdateProcessor{
    public $classKey = 'ShopmodxResourceWarehouse';
    public $objectType = 'shopmodxresourcewarehouse';
}
return 'ShopmodxResourceWarehouseUpdateProcessor';