<?php

require_once dirname(dirname(__FILE__)).'/resource/update.class.php';

class ShopmodxResourceProductUpdateProcessor extends ShopmodxResourceUpdateProcessor{
    public $classKey = 'ShopmodxResourceProduct';
    public $objectType = 'shopmodxresourceproduct';
}
return 'ShopmodxResourceProductUpdateProcessor';