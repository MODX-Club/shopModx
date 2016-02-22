<?php

require_once dirname(dirname(__FILE__)) . '/getdata.class.php';

class modShopmodxOrderstatusGetdataProcessor extends modShopmodxGetdataProcessor{
    
    public $classKey = 'ShopmodxOrderStatus';
    
    
    public function initialize() {
        $this->setDefaultProperties(array(
            'limit' => 0,
            "sort"  => "rank",
            "dir"   => "ASC",
        ));
        return parent::initialize();
    }
    
    
    public function beforeIteration(array $list) {
        
        if($this->getProperty('show_empty_text')){
            $list[] = array(
                "id"    => 0,
                "status"  => "Выберите из списка",
            );
        }
        
        return $list;
    }
    
}

return 'modShopmodxOrderstatusGetdataProcessor';
