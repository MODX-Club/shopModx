<?php

$this->loadClass('modResource');
require_once dirname(dirname(dirname(__FILE__))).'/processors/mgr/resource/create.class.php';
require_once dirname(dirname(dirname(__FILE__))).'/processors/mgr/resource/update.class.php';


class ShopmodxResource extends modResource{
    public $showInContextMenu = true;
    public $allowChildrenResources = true;
    public $relatedObjectClass = null;               
    
    function __construct(xPDO &$xpdo) {
        $xpdo->lexicon->load('shopmodx:resource');
        return parent::__construct($xpdo);
    }
    
    public static function load(xPDO & $xpdo, $className, $criteria, $cacheFlag= true){
        if (!is_object($criteria)) {
            $criteria= $xpdo->getCriteria($className, $criteria, $cacheFlag);
        }
        $xpdo->addDerivativeCriteria($className, $criteria);
        return parent::load($xpdo, $className, $criteria, $cacheFlag);
    }
    
    public static function loadCollection(xPDO & $xpdo, $className, $criteria= null, $cacheFlag= true){
        if (!is_object($criteria)) {
            $criteria= $xpdo->getCriteria($className, $criteria, $cacheFlag);
        }
        $xpdo->addDerivativeCriteria($className, $criteria);
        return parent::loadCollection($xpdo, $className, $criteria, $cacheFlag);
    }
    
    public function getContextMenuText() {
        return array(
            'text_create' => $this->xpdo->lexicon('shopmodx.resource_create'),
            'text_create_here' => $this->xpdo->lexicon('shopmodx.resource_create_here'),
        ); 
    }

    public function getResourceTypeName() {
        return $this->xpdo->lexicon('shopmodx.resource');
    }     
    
    
    /*
     * Add reference object
     */
    public function addObject($data = array()){
        if(!$this->relatedObjectClass){
            return false;
        }
        return $this->addOne($this->xpdo->newObject($this->relatedObjectClass, $data));
    }
    
    /*
     * Get reference object
     */
    public function & getObject($criteria= null, $cacheFlag= true){
        $obj = null;
        if(!$this->relatedObjectClass) return $obj;
        
        $aliases = $this->_getAliases($this->relatedObjectClass, 1);
        if (empty($aliases)) {
            return $obj;
        }
        $alias = reset($aliases);
        return $this->getOne($alias, $criteria= null, $cacheFlag= true);
    }
    
    public static function getControllerPath(xPDO &$modx) {
        return self::_getControllerPath($modx, 'resource');
    }
    
    public static function _getControllerPath(xPDO &$modx, $path){
        
        if(!$_path = $modx->getOption('shopmodx.core_path',null)){
            $_path = $modx->getOption('core_path').'components/shopmodx/';
        }
        $_path .= "controllers/default/{$path}/";
        return $_path;
    }
}