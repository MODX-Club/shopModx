<?php

require_once dirname(dirname(__FILE__)).'/resource/update.class.php';

class ShopmodxResourceVendorUpdateProcessor extends ShopmodxResourceUpdateProcessor{
    public $classKey = 'ShopmodxResourceVendor';
    public $objectType = 'shopmodxresourcevendor';
}
return 'ShopmodxResourceVendorUpdateProcessor';