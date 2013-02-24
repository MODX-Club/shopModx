<?php

/*
 * class Category
 */

$this->loadClass('ShopmodxResource');
if($this->context->key == 'mgr'){
    require_once dirname(dirname(dirname(__FILE__))).'/processors/mgr/resourcevendor/create.class.php';
    require_once dirname(dirname(dirname(__FILE__))).'/processors/mgr/resourcevendor/update.class.php';
}

class ShopmodxResourceVendor extends ShopmodxResource{
    public $showInContextMenu = true;
    public $allowChildrenResources = true;
    
    public $relatedObjectClass = 'ShopmodxVendor';
    
    public function getContextMenuText() {
        return array(
            'text_create' => $this->xpdo->lexicon('shopmodx.resource_vendor_create'),
            'text_create_here' => $this->xpdo->lexicon('shopmodx.resource_vendor_create_here'),
        ); 
    }

    public function getResourceTypeName() {
        return $this->xpdo->lexicon('shopmodx.resource_vendor');
    }   
    
    public static function getControllerPath(xPDO &$modx) {
        return self::_getControllerPath($modx, 'resourcevendor');
    }
}
