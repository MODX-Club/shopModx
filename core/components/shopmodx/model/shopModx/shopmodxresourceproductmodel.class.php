<?php
$this->loadClass('ShopmodxResource');

class ShopmodxResourceProductModel extends ShopmodxResource{
    public $showInContextMenu = true;
    public $allowChildrenResources = true;
    
    public $relatedObjectClass = 'ShopmodxProductModel';
    
    public function getContextMenuText() {
        return array(
            'text_create' => $this->xpdo->lexicon('shopmodx.resource_productmodel_create'),
            'text_create_here' => $this->xpdo->lexicon('shopmodx.resource_productmodel_create_here'),
        ); 
    }

    public function getResourceTypeName() {
        return $this->xpdo->lexicon('shopmodx.resource_productmodel');
    }
}