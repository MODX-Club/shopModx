<?php
require_once dirname(dirname(__FILE__)).'/getlist.class.php';
class modWebResourceproductGetlistProcessor extends ShopmodxWebGetlistProcessor{
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

return 'modWebResourceproductGetlistProcessor';