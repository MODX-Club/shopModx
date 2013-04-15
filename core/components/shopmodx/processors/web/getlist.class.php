<?php

class ShopmodxWebGetlistProcessor extends modObjectGetListProcessor{
    public $classKey = 'modResource';
    public $defaultSortField = 'id';
    
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $where = array();
        if($_where = (array)$this->getProperty('where')){
            $where = array_merge($where, $_where);
        }
        if($where){
            $c->where($where);
        }
        return parent::prepareQueryBeforeCount($c);
    }
    
    public function prepareQueryAfterCount(xPDOQuery $c) {
        $this->getColumns($c);
        return parent::prepareQueryAfterCount($c);
    }

    public function getColumns(xPDOQuery $c){
        $c->select(array(
            "{$this->classKey}.*",
            "{$this->classKey}.id as `resource_id`",    // Make sure resource id will not overwrite
        ));
        return $c;
    }

    public function outputArray(array $array, $count = false){
        return $this->success((int)$count, $array);
    }
}

return 'ShopmodxWebGetlistProcessor';