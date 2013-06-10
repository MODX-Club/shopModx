<?php
require_once dirname(dirname(__FILE__)).'/getcollection.class.php';

class modWebResourceproductGetcollectionProcessor extends ShopmodxWebGetCollectionProcessor{
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->innerJoin('ShopmodxProduct', 'Product');
        return parent::prepareQueryBeforeCount($c);
    }
    
    protected function setSelection(xPDOQuery $c) {
        $c = parent::setSelection($c);
        $c->select(array(
            "Product.*",
            "Product.id as `product_id`",
        ));
        return $c;
    }
}

return 'modWebResourceproductGetcollectionProcessor';