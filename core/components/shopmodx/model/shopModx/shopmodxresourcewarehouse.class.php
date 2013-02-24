<?php
$this->loadClass('ShopmodxResource');

if($this->context->key == 'mgr'){
    require_once dirname(dirname(dirname(__FILE__))).'/processors/mgr/resourcewarehouse/create.class.php';
    require_once dirname(dirname(dirname(__FILE__))).'/processors/mgr/resourcewarehouse/update.class.php';
}

class ShopmodxResourceWarehouse extends ShopmodxResource{
    public $showInContextMenu = true;
    public $allowChildrenResources = true;
    
    public $relatedObjectClass = 'ShopmodxWarehouse';
    
    public function getContextMenuText() {
        return array(
            'text_create' => $this->xpdo->lexicon('shopmodx.resource_warehouse_create'),
            'text_create_here' => $this->xpdo->lexicon('shopmodx.resource_warehouse_create_here'),
        ); 
    }

    public function getResourceTypeName() {
        return $this->xpdo->lexicon('shopmodx.resource_warehouse');
    } 
    
    public static function getControllerPath(xPDO &$modx) {
        return self::_getControllerPath($modx, 'resourcewarehouse');
    }
}