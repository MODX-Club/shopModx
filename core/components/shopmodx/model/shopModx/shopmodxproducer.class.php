<?php

/*
 * class Category
 */
$this->loadClass('shopModxResource');

class shopModxProducer extends shopModxResource{
    public $showInContextMenu = true;
    public $allowChildrenResources = true;
    
    public function getContextMenuText() {
        return array(
            'text_create' => $this->xpdo->lexicon('shopmodx.resource_producer_create'),
            'text_create_here' => $this->xpdo->lexicon('shopmodx.resource_producer_create_here'),
        ); 
    }

    public function getResourceTypeName() {
        return $this->xpdo->lexicon('shopmodx.resource_producer');
    }    
}
