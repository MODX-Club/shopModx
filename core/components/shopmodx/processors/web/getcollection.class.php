<?php
require_once dirname(__FILE__).'/getlist.class.php';

class ShopmodxWebGetCollectionProcessor extends ShopmodxWebGetlistProcessor{

    public function iterate(array $data) {
        $list = array();
        $list = $this->beforeIteration($list);
        $this->currentIndex = 0;
        /** @var xPDOObject|modAccessibleObject $object */
        foreach ($data['results'] as $object) {
            if ($this->checkListPermission && $object instanceof modAccessibleObject && !$object->checkPolicy('list')) continue;
            $_object = $this->prepareRow($object);
            if (!empty($_object) && is_object($_object)) {
                $list[$_object->get('id')] = $_object;
                $this->currentIndex++;
            }
        }
        $list = $this->afterIteration($list);
        return $list;
    }    
    
    public function prepareRow(xPDOObject $object) {
        return $object;
    }
}

return 'ShopmodxWebGetCollectionProcessor';