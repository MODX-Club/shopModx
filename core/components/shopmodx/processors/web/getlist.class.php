<?php

class ShopmodxWebGetlistProcessor extends modObjectGetListProcessor{
    public $classKey = 'modResource';
    public $defaultSortField = '';
    public $flushWhere = true;   // Flush query condition and search only by objects IDs
    protected $total = 0;


    
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
        if(!$c = $this->getCount($c)){
            return $data;
        }
        $c = $this->prepareQueryAfterCount($c);

        $this->setSelection($c);
        
        $data['total']   = $this->total;
        $data['results'] = $this->getResults($c);
        return $data;
    }
    
    protected function getCount(xPDOQuery & $c){
        if(!$sortKey = $this->getProperty('sort')){
            $sortClassKey = $this->getSortClassKey();
            $sortKey = $this->modx->getSelectColumns($sortClassKey,$this->getProperty('sortAlias',$sortClassKey),'',array($this->getProperty('sort')));
        }
        
        $query = clone $c;
        $query = $this->prepareCountQuery($query);
        if(!$this->total = $this->countTotal($this->classKey,$query)){
            return false;
        }
        
        if($sortKey){
            $c->sortby($sortKey,$this->getProperty('dir'));
            $query->sortby($sortKey,$this->getProperty('dir'));
        }
        
        $limit = intval($this->getProperty('limit'));
        $start = intval($this->getProperty('start'));
        
        if ($limit > 0) {
            $query->limit($limit,$start);
        }
        
        $query = $this->PrepareUniqObjectsQuery($query);
        if($query->prepare() && $query->stmt->execute() && $rows = $row = $query->stmt->fetchAll(PDO::FETCH_ASSOC)){
            $IDs = array();
            foreach($rows as $row){
                $IDs[] = $row['id'];
            }
            // print $query->toSQL();
            // print_r($IDs);
            // exit;
            if ($this->flushWhere && isset($c->query['where'])) $c->query['where'] = array();
            $c->where(array(
                "{$this->classKey}.id:IN" => $IDs,
            ));
        }
        else{
            return false;
        }     
        
        return $c;
    }

    protected function prepareCountQuery(xPDOQuery & $query){
        if($where = (array)$this->getProperty('where')){
            $query->where($where);
        }
        return $query;
    }

    
    /*
     * Count total results
     */
    protected function countTotal($className, xPDOQuery & $query){
        return $this->modx->getCount($this->classKey,$query);
    }

    protected function PrepareUniqObjectsQuery(xPDOQuery & $query){
        if (isset($query->query['columns'])) $query->query['columns'] = array();
        $query->select(array ("DISTINCT {$this->classKey}.id"));
        
        return $query;
    } 

    protected function setSelection(xPDOQuery $c){
        $c->select(array(
            "{$this->classKey}.*",
            "{$this->classKey}.id as `object_id`",    // Make sure resource id will not overwrite
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