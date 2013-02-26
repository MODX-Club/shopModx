<?php
class ShopmodxSimpleObject extends xPDOSimpleObject {
    function set($k, $v = null, $vType = '') {
        switch ($k){
            // Protect changing class_key
            case 'class_key':
                $v = $this->get($k);
                break;
        }
        return parent::set($k, $v, $vType);
    }
}