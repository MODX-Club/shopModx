<?php

require_once dirname(dirname(__FILE__)) .'/objects/getlist.class.php';

class ShopmodxCurrencyGetListProcessor extends ShopmodxObjectGetListProcessor{
    public $classKey = 'ShopmodxResourceCurrency';
    public $objectType = 'shopmodxresourcecurrency';
    public $defaultSortField = 'pagetitle';
    
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->where(array(
            'published' => 1,
            'deleted'   => 0,
            'hidemenu'  => 0,
        ));
        return parent::prepareQueryBeforeCount($c);
    }
}

return 'ShopmodxCurrencyGetListProcessor';