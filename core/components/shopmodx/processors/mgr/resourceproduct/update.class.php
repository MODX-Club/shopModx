<?php

require_once dirname(dirname(__FILE__)).'/resource/update.class.php';

class ShopmodxResourceProductUpdateProcessor extends ShopmodxResourceUpdateProcessor{
    public $objectType = 'shopmodxresourceproduct';
    public $relatedObjectRequired = true;
}
return 'ShopmodxResourceProductUpdateProcessor';