<?php

/*
 * class Currency
 */

$this->loadClass('ShopmodxResource');

class ShopmodxResourceCurrency extends ShopmodxResource{
    public $showInContextMenu = true;
    public $allowChildrenResources = true;
    
    public $relatedObjectClass = 'ShopmodxCurrency';
    
    public function getContextMenuText() {
        return array(
            'text_create' => $this->xpdo->lexicon('shopmodx.resource_currency_create'),
            'text_create_here' => $this->xpdo->lexicon('shopmodx.resource_currency_create_here'),
        ); 
    }

    public function getResourceTypeName() {
        return $this->xpdo->lexicon('shopmodx.resource_currency');
    }
}
