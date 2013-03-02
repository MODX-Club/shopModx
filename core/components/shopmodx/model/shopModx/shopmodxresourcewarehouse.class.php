<?php
$this->loadClass('ShopmodxResource');

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
}