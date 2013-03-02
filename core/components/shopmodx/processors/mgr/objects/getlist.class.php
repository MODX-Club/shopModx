<?php

abstract class ShopmodxObjectGetListProcessor extends modObjectGetListProcessor{
    function prepareQueryBeforeCount(xPDOQuery $c) {
        $this->addDerivativeCriteria($c);
        return parent::prepareQueryBeforeCount($c);
    }
    
    protected function addDerivativeCriteria(xPDOQuery $c){
        $this->modx->addDerivativeCriteria($this->classKey, $c);
        return $c;
    }
}