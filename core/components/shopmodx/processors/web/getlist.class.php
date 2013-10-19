<?php

class ShopmodxWebGetlistProcessor extends modObjectGetListProcessor{
    public $classKey = 'modResource';
    public $defaultSortField = '';
    public $flushWhere = true;   // Flush query condition and search only by objects IDs
    protected $total = 0;


    public function initialize(){
        
        $this->setDefaultProperties(array(
            'cache'             => false,           // Use cache
            'cache_lifetime'    => 0,               // seconds
            'cache_prefix'      => 'getdata/',      
        ));
        
        return parent::initialize();
    }


    public function process(){
        
        // Use or not caching
        $cacheable = $this->getProperty('cache');
        
        if($cacheable){
            $key = $this->getProperty('cache_prefix') . md5( __CLASS__ . json_encode($this->getProperties()));
            if($cache = $this->modx->cacheManager->get($key)){
                return $this->prepareResponse($cache);
            }
        }
        
        $result = parent::process();
        
        if($cacheable){
            $this->modx->cacheManager->set($key, $result, $this->getProperty('cache_lifetime', 0));
        }
        
        return $result;
    }  

    
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
        
        $query = $this->prepareUniqObjectsQuery($query);
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

    protected function prepareUniqObjectsQuery(xPDOQuery & $query){
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

    public function afterIteration(array $list) {
        $_list = parent::afterIteration($list);
        $list = array();
        
        foreach($_list as & $l){
            $l['id'] = $l['object_id'];
            $list[$l['id']] = $l;
        }
        
        return $list;
    }

    /*
        Here you may add callback when caching anabled
    */
    protected function prepareResponse($response){
        return $response;
    }

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