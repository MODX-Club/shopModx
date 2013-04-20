<?php

class ShopmodxWebGetlistProcessor extends modObjectGetListProcessor{
    public $classKey = 'modResource';
    public $defaultSortField = '';
    
    
    /**
     * Get the data of the query
     * @return array
     */
    public function getData() {
        $data = array(
            'total' => 0,
            'results' => array(),
        );

        $c = $this->modx->newQuery($this->classKey);
        $c = $this->prepareQueryBeforeCount($c);
        if(!$data['total'] = (int)$this->getCount($c)){
            return $data;
        }
        $c = $this->prepareQueryAfterCount($c);
        
        if(!$sortKey = $this->getProperty('sort')){
            $sortClassKey = $this->getSortClassKey();
            $sortKey = $this->modx->getSelectColumns($sortClassKey,$this->getProperty('sortAlias',$sortClassKey),'',array($this->getProperty('sort')));
        }
        if($sortKey){
            $c->sortby($sortKey,$this->getProperty('dir'));
        }
        
        if(!$c = $this->PrepareUniqObjectsQuery($c)){
            return $data;
        }

        $this->setSelection($c);
        
        $data['results'] = $this->getResults($c);
        return $data;
    }    
    
    public function prepareQueryBeforeCount(xPDOQuery & $c) {
        $c = parent::prepareQueryBeforeCount($c);
        if($where = (array)$this->getProperty('where')){
            $c->where($where);
        }
        return $c;
    }
    
    protected function getCount(xPDOQuery & $c){
        return $this->modx->getCount($this->classKey,$c);
    }

    protected function PrepareUniqObjectsQuery(xPDOQuery & $c){
        $limit = intval($this->getProperty('limit'));
        $start = intval($this->getProperty('start'));
        
        if ($limit > 0) {
            $c->limit($limit,$start);
        }
        return $c;
    } 

    protected function setSelection(xPDOQuery $c){
        $c->select(array(
            "{$this->classKey}.*",
            "{$this->classKey}.id as `resource_id`",    // Make sure resource id will not overwrite
        ));
        return $c;
    }
    
    protected function getResults(xPDOQuery & $c){
        return $this->modx->getCollection($this->classKey,$c);
    }
    
    protected function getMessage(){return '';}

    public function outputArray(array $array, $count = false){
        return array(
            'success' => true,
            'message' => $this->getMessage(),
            'count'   => count($array),
            'total'   => $count,
            'object'  => $array,
        );
    }
}

return 'ShopmodxWebGetlistProcessor';