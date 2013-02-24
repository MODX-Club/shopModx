<?php

/*
 * class Category
 */
$this->loadClass('ShopmodxResource');

if($this->context->key == 'mgr'){
    require_once dirname(dirname(dirname(__FILE__))).'/processors/mgr/resourceproducer/create.class.php';
    require_once dirname(dirname(dirname(__FILE__))).'/processors/mgr/resourceproducer/update.class.php';
}

class ShopmodxResourceProducer extends ShopmodxResource{
    public $showInContextMenu = true;
    public $allowChildrenResources = true;
    
    public $relatedObjectClass = 'ShopmodxProducer';
    
    public function getContextMenuText() {
        return array(
            'text_create' => $this->xpdo->lexicon('shopmodx.resource_producer_create'),
            'text_create_here' => $this->xpdo->lexicon('shopmodx.resource_producer_create_here'),
        ); 
    }

    public function getResourceTypeName() {
        return $this->xpdo->lexicon('shopmodx.resource_producer');
    }
    
    
    public static function getControllerPath(xPDO &$modx) {
        return self::_getControllerPath($modx, 'resourceproducer');
    }
}
