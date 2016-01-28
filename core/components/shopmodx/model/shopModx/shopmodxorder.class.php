<?php
class ShopmodxOrder extends xPDOSimpleObject {
    
    /*public function save($cacheFlag= null){
        
        if($this->isNew() && !$this->get('createdby') && $this->xpdo->user->id){
            $this->set('createdby', $this->xpdo->user->id);
        }
        
        return parent::save($cacheFlag);
    }*/
    
    public function & getOne($alias, $criteria= null, $cacheFlag= true) {
        $object= null;
        if ($fkdef= $this->getFKDefinition($alias)) {
            $k= $fkdef['local'];
            $fk= $fkdef['foreign'];
            if (isset ($this->_relatedObjects[$alias])) {
                if (is_object($this->_relatedObjects[$alias])) {
                    $object= & $this->_relatedObjects[$alias];
                    return $object;
                }
            }
            if ($criteria === null) {
                $criteria= array ($fk => $this->get($k));
                if (isset($fkdef['criteria']) && isset($fkdef['criteria']['foreign'])) {
                    $criteria= array($fkdef['criteria']['foreign'], $criteria);
                }
            }
            if ($object= $this->xpdo->getObject($fkdef['class'], $criteria, $cacheFlag)) {
                $this->_relatedObjects[$alias]= $object;
            }
        } else {
            $this->xpdo->log(xPDO::LOG_LEVEL_WARN, "Could not getOne: foreign key definition for alias {$alias} not found.");
        }
        return $object;
    }
}