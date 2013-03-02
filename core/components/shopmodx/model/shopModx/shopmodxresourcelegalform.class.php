<?php

/*
 * class LegalForm
 */

$this->loadClass('ShopmodxResource');


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
}
