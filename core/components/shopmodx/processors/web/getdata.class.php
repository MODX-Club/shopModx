<?php
/*
 * return products data array
 */

require_once dirname(__FILE__).'/getlist.class.php';

class ShopmodxWebGetDataProcessor extends ShopmodxWebGetlistProcessor{
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            'includeTVs'  => true,  
        ));
        
        return parent::initialize();
    }
    
    protected function setSelection(xPDOQuery $c) {
        $c = parent::setSelection($c);
    
        if($this->getProperty('includeTVs')){
            $c->leftJoin('modTemplateVarResource', 'TemplateVarResources');
            $c->leftJoin('modTemplateVar', 'tv', "tv.id=TemplateVarResources.tmplvarid");
    
            $c->select(array(
                "tv.id as tv_id",
                'tv.name as tv_name',
                "TemplateVarResources.id as tv_value_id",
                "TemplateVarResources.value as tv_value",
            ));
        }
    
        return $c;
    }
    
    
    public function iterate(array $data) {
        $list = $this->beforeIteration($data['results']);
        $list = $this->afterIteration($list);
        return $list;
    }    
    
    
    protected function getResults(xPDOQuery & $c){
        $list = array();
        $this->currentIndex = 0;
        if($c->prepare() && $c->stmt->execute()){
            while($row = $c->stmt->fetch(PDO::FETCH_ASSOC)){
                $object_id = $row['object_id'];
                if(empty($list[$object_id])){
                    $list[$object_id] = $row;
                    $list[$object_id]['tvs'] = array();
                    $this->currentIndex++;
                }
                if(!empty($row['tv_name'])){
                    $list[$object_id]['tvs'][$row['tv_name']] = array(
                        'tv_id'    => $row['tv_id'],
                        'value_id'    => $row['tv_value_id'],
                        'value'    => $row['tv_value'],
                    );
                }
            }
        }
        return $list;
    }
}

return 'ShopmodxWebGetDataProcessor';