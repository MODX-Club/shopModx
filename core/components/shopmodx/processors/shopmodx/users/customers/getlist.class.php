<?php

require_once dirname(dirname(__FILE__)) . '/getlist.class.php';

class modShopmodxUsersCustomersGetlistProcessor extends modShopmodxUsersGetlistProcessor{
    
    public function prepareQueryAfterCount(xPDOQuery $c) {
        $c = parent::prepareQueryAfterCount($c);
        $alias = $c->getAlias();
        $c->select(array(
            "IF(Profile.fullname != '', Profile.fullname, {$alias}.username)",
        ));
        return $c;
    }
    
    
    public function beforeIteration(array $list) {
        
        if(
            $this->getProperty('show_empty_text')
            AND !$this->getProperty('start')
            # AND $list
        ){
            $list[] = array(
                "id"    => 0,
                "username"  => "Все",
            );
        }
        return $list;
    }
    
}
return 'modShopmodxUsersCustomersGetlistProcessor';