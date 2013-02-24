<?php

/*
 * class Category
 */

$this->loadClass('ShopmodxResource');
if($this->context->key == 'mgr'){
    require_once dirname(dirname(dirname(__FILE__))).'/processors/mgr/resourcecategory/create.class.php';
    require_once dirname(dirname(dirname(__FILE__))).'/processors/mgr/resourcecategory/update.class.php';
}

class ShopmodxResourceCategory extends ShopmodxResource{
    public $showInContextMenu = true;
    public $allowChildrenResources = true;
    
    public $relatedObjectClass = 'ShopmodxCategory';
    
    public function getContextMenuText() {
        return array(
            'text_create' => $this->xpdo->lexicon('shopmodx.resource_category_create'),
            'text_create_here' => $this->xpdo->lexicon('shopmodx.resource_category_create_here'),
        ); 
    }

    public function getResourceTypeName() {
        return $this->xpdo->lexicon('shopmodx.resource_category');
    }   
    
    public static function getControllerPath(xPDO &$modx) {
        return self::_getControllerPath($modx, 'resourcecategory');
    }
}
