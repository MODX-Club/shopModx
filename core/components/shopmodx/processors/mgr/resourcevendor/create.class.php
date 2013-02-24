<?php

require_once dirname(dirname(__FILE__)).'/resource/create.class.php';

class ShopmodxResourceVendorCreateProcessor extends ShopmodxResourceCreateProcessor{
    public $classKey = 'ShopmodxResourceVendor';
    public $objectType = 'shopmodxresourcevendor';
}
return 'ShopmodxResourceVendorCreateProcessor';