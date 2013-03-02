<?php

require_once dirname(dirname(__FILE__)) .'/objects/getlist.class.php';

class ShopmodxCurrencyGetListProcessor extends ShopmodxObjectGetListProcessor{
    public $classKey = 'ShopmodxResourceCurrency';
    public $objectType = 'shopmodxresourcecurrency';
    public $defaultSortField = 'pagetitle';
}

return 'ShopmodxCurrencyGetListProcessor';