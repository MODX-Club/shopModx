<?php

require_once dirname(dirname(__FILE__)) .'/objects/getlist.class.php';

class ShopmodxLegalFormGetListProcessor extends ShopmodxObjectGetListProcessor{
    public $classKey = 'ShopmodxResourceLegalForm';
    public $objectType = 'shopmodxresourcelegalform';
    public $defaultSortField = 'pagetitle';
}

return 'ShopmodxLegalFormGetListProcessor';