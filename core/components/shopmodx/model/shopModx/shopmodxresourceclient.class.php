<?php

/*
 * class Client
 */

$this->loadClass('ShopmodxResource');
require_once dirname(dirname(dirname(__FILE__))).'/processors/mgr/resourceclient/create.class.php';
require_once dirname(dirname(dirname(__FILE__))).'/processors/mgr/resourceclient/update.class.php';


class ShopmodxResourceClient extends ShopmodxResource{
    public $showInContextMenu = true;
    public $allowChildrenResources = true;
    
    public $relatedObjectClass = 'ShopmodxClient';
    
    public function getContextMenuText() {
        return array(
            'text_create' => $this->xpdo->lexicon('shopmodx.resource_client_create'),
            'text_create_here' => $this->xpdo->lexicon('shopmodx.resource_client_create_here'),
        ); 
    }

    public function getResourceTypeName() {
        return $this->xpdo->lexicon('shopmodx.resource_client');
    }   
    
    public static function getControllerPath(xPDO &$modx) {
        return self::_getControllerPath($modx, 'resourceclient');
    }
}
