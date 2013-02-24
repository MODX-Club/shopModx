<?php

/*
 * class Category
 */
$this->loadClass('ShopmodxResource');
if($this->context->key == 'mgr'){
    require_once dirname(dirname(dirname(__FILE__))).'/processors/mgr/resourceproduct/create.class.php';
    require_once dirname(dirname(dirname(__FILE__))).'/processors/mgr/resourceproduct/update.class.php';
}

class ShopmodxResourceProduct extends ShopmodxResource{
    public $showInContextMenu = true;
    public $allowChildrenResources = true;
    
    public $relatedObjectClass = 'ShopmodxProduct';
    
    public function getContextMenuText() {
        return array(
            'text_create' => $this->xpdo->lexicon('shopmodx.resource_product_create'),
            'text_create_here' => $this->xpdo->lexicon('shopmodx.resource_product_create_here'),
        ); 
    }

    public function getResourceTypeName() {
        return $this->xpdo->lexicon('shopmodx.resource_product');
    }
    
    public static function getControllerPath(xPDO &$modx) {
        return self::_getControllerPath($modx, 'resourceproduct');
    }
}
