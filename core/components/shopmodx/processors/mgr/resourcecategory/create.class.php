<?php

require_once dirname(dirname(__FILE__)).'/resource/create.class.php';

class ShopmodxResourceCategoryCreateProcessor extends ShopmodxResourceCreateProcessor{
    public $classKey = 'ShopmodxResourceCategory';
    public $objectType = 'shopmodxresourcecategory';
    
}

return 'ShopmodxResourceCategoryCreateProcessor';