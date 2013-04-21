<?php
/*
 * return products data array
 */

require_once dirname(__FILE__).'/getlist.class.php';

class ShopmodxWebGetDataProcessor extends ShopmodxWebGetlistProcessor{
    
    public function iterate(array $data) {
        $list = array();
        $list = $this->beforeIteration($list);
        $this->currentIndex = 0;
        foreach ($data['results'] as $row) {
            $object_id = $row['object_id'];
            if(empty($list[$object_id])){
                $list[$object_id] = $row;
                $list[$object_id]['tvs'] = array();
            }
            if(!empty($row['tv_name'])){
                $list[$object_id]['tvs'][$row['tv_name']] = array(
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