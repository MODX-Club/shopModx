<?php

/*
 * class LegalForm
 */

$this->loadClass('ShopmodxResource');
if($this->context->key == 'mgr'){
    require_once dirname(dirname(dirname(__FILE__))).'/processors/mgr/resourcelegalform/create.class.php';
    require_once dirname(dirname(dirname(__FILE__))).'/processors/mgr/resourcelegalform/update.class.php';
}


class ShopmodxResourceLegalForm extends ShopmodxResource{
    public $showInContextMenu = true;
    public $allowChildrenResources = true;
    
    public $relatedObjectClass = 'ShopmodxLegalForm';
    
    public function getContextMenuText() {
        return array(
            'text_create' => $this->xpdo->lexicon('shopmodx.resource_legalform_create'),
            'text_create_here' => $this->xpdo->lexicon('shopmodx.resource_legalform_create_here'),
        ); 
    }

    public function getResourceTypeName() {
        return $this->xpdo->lexicon('shopmodx.resource_legalform');
    }   
    
    public static function getControllerPath(xPDO &$modx) {
        return self::_getControllerPath($modx, 'resourcelegalform');
    }
}
