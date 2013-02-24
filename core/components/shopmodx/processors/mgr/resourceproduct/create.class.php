<?php

require_once dirname(dirname(__FILE__)).'/resource/create.class.php';

class ShopmodxResourceProductCreateProcessor extends ShopmodxResourceCreateProcessor{
    public $classKey = 'ShopmodxResourceProduct';
    public $objectType = 'shopmodxresourceproduct';
}
return 'ShopmodxResourceProductCreateProcessor';