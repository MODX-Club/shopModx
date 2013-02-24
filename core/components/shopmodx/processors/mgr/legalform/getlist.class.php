<?php

class ShopmodxLegalFormGetListProcessor extends modObjectGetListProcessor{
    public $objectType = 'objectshopmodxlegalform';
    public $classKey = 'ShopmodxLegalForm';
    public $defaultSortField = 'sm_name';
    
    /*function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->prepare();
        print $c->toSQL();
        
        exit;
        parent::prepareQueryBeforeCount($c);
    }*/
}

return 'ShopmodxLegalFormGetListProcessor';