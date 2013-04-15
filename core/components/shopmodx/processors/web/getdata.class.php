<?php
/*
 * return products data array
 */

require_once dirname(__FILE__).'/getlist.class.php';

class ShopmodxWebGetDataProcessor extends ShopmodxWebGetlistProcessor{
    
    public function getData() {
        $data = array(
            'total' => 0,
            'results' => array(),
        );

        $c = $this->modx->newQuery($this->classKey);
        $c = $this->prepareQueryBeforeCount($c);
        $data['total'] = $this->modx->getCount($this->classKey,$c);
        
        $sortClassKey = $this->getSortClassKey();
        $sortKey = $this->modx->getSelectColumns($sortClassKey,$this->getProperty('sortAlias',$sortClassKey),'',array($this->getProperty('sort')));
        if (empty($sortKey) && $sortKey = $this->getProperty('sort')){
            $c->sortby($sortKey,$this->getProperty('dir'));
        }
        
        // get resources IDs
        $this->getResourcesIDs($c);
        
        $c = $this->prepareQueryAfterCount($c);

        if($c->prepare() && $c->stmt->execute()){
            $data['results'] = $c->stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }    
    
    public function getResourcesIDs(xPDOQuery $c){
        $query = clone $c;
        if (isset($query->query['columns'])) $query->query['columns'] = array();
        $query->select(array ("DISTINCT {$this->classKey}.id"));
        
        $limit = intval($this->getProperty('limit'));
        $start = intval($this->getProperty('start'));
        
        if ($limit > 0) {
            $query->limit($limit,$start);
        }
        
        $query = $this->prepareQueryBeforeGetResourcesIDs($query);
        
        if($query->prepare() && $query->stmt->execute() && $rows = $row = $query->stmt->fetchAll(PDO::FETCH_ASSOC)){
            $IDs = array();
            foreach($rows as $row){
                $IDs[] = $row['id'];
            }
            $c->where(array(
                "{$this->classKey}.id:IN" => $IDs,
            ));
        }
        return $c;
    }
    
    public function prepareQueryBeforeGetResourcesIDs(xPDOQuery $query){
        return $query;
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
    
    public function prepareQueryAfterCount(xPDOQuery $c) {
        $c->leftJoin('modTemplateVarResource', 'TemplateVarResources');
        $c->leftJoin('modTemplateVar', 'tv', "tv.id=TemplateVarResources.tmplvarid");
        $c->select(array(
            "tv.id as tv_id",
            'tv.name as tv_name',
            "TemplateVarResources.id as tv_value_id",
            "TemplateVarResources.value as tv_value",
        ));
        return parent::prepareQueryAfterCount($c);
    }
}

return 'ShopmodxWebGetDataProcessor';