<?php

if($this->context->key == 'mgr'){
    require_once dirname(dirname(dirname(__FILE__))).'/processors/mgr/resource/create.class.php';
    require_once dirname(dirname(dirname(__FILE__))).'/processors/mgr/resource/update.class.php';
}

class ShopmodxResource extends modResource{
    public $relatedObjectClass = null;               
    
    function __construct(xPDO &$xpdo) {
        $xpdo->lexicon->load('shopmodx:resource');
        return parent::__construct($xpdo);
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
        return self::_getControllerPath('resource');
    }
    
    public static function _getControllerPath(xPDO &$modx, $path){
        if(!$_path = $modx->getOption('shopmodx.core_path',null)){
            $_path = $modx->getOption('core_path').'components/shopmodx/';
        }
        $_path .= "controllers/default/{$path}/";
        return $_path;
    }
}