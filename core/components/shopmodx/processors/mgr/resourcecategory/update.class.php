<?php

require_once dirname(dirname(__FILE__)).'/resource/update.class.php';

class ShopmodxResourceCategoryUpdateProcessor extends ShopmodxResourceUpdateProcessor{
    public $classKey = 'ShopmodxResourceCategory';
    public $objectType = 'shopmodxresourcecategory';
    
}

return 'ShopmodxResourceCategoryUpdateProcessor';