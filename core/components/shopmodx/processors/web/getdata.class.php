<?php
/*
 * return products data array
 */

require_once dirname(__FILE__).'/getlist.class.php';

class ShopmodxWebGetDataProcessor extends ShopmodxWebGetlistProcessor{
    public $flushWhere = true;   // Flush query condition and search only by objects IDs

    protected function PrepareUniqObjectsQuery(xPDOQuery & $c){
        $query = clone $c;
        if (isset($query->query['columns'])) $query->query['columns'] = array();
        
        $query->select(array ("DISTINCT {$this->classKey}.id"));
        
        $limit = intval($this->getProperty('limit'));
        $start = intval($this->getProperty('start'));
        
        if ($limit > 0) {
            $query->limit($limit,$start);
        }
        
        if($query->prepare() && $query->stmt->execute() && $rows = $row = $query->stmt->fetchAll(PDO::FETCH_ASSOC)){
            $IDs = array();
            foreach($rows as $row){
                $IDs[] = $row['id'];
            }
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
    
    public function iterate(array $data) {
        $list = array();
        $list = $this->beforeIteration($list);
        $this->currentIndex = 0;
        foreach ($data['results'] as $row) {
            $resource_id = $row['resource_id'];
            if(empty($list[$resource_id])){
                $list[$resource_id] = $row;
                $list[$resource_id]['tvs'] = array();
            }
            if(!empty($row['tv_name'])){
                $list[$resource_id]['tvs'][$row['tv_name']] = array(
                    'tv_id'    => $row['tv_id'],
                    'value_id'    => $row['tv_value_id'],
                    'value'    => $row['tv_value'],
                );
            }
        }
        $list = $this->afterIteration($list);
        return $list;
    }    
    
    protected function setSelection(xPDOQuery $c) {
        $c = parent::setSelection($c);
        
        $c->leftJoin('modTemplateVarResource', 'TemplateVarResources');
        $c->leftJoin('modTemplateVar', 'tv', "tv.id=TemplateVarResources.tmplvarid");
        
        $c->select(array(
            "tv.id as tv_id",
            'tv.name as tv_name',
            "TemplateVarResources.id as tv_value_id",
            "TemplateVarResources.value as tv_value",
        ));
        
        return $c;
    }

    protected function getResults(xPDOQuery & $c){
        $data = array();
        if($c->prepare() && $c->stmt->execute()){
            $data = $c->stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }  
}

return 'ShopmodxWebGetDataProcessor';