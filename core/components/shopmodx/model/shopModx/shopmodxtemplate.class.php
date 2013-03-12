<?php
/*
 * For maximum useful use modxSmarty
 */

$this->loadClass('modTemplate');
class ShopmodxTemplate extends modTemplate{
    protected $templater = null;

    public function process($properties = null, $content = null) {
        $this->_process($properties, $content);
        if (!$this->_processed) {
            return parent::process($properties, $content);
        }
        return $this->_output;
    }
     
    protected function _process($properties = null, $content = null) {
        if(!$this->isStatic() || !$this->getTemplater()){return;}
        $this->parseTemplate();
        return ;
    }
     
    protected function & getTemplater(){
        $this->templater = & $this->xpdo->smarty;
        return $this->templater;
    }
     
    protected function parseTemplate(){
        if(!$controller = $this->getSourceFile()){return ;}
        $modx = & $this->xpdo;
        $templater = & $this->getTemplater();
        $resource = & $this->xpdo->resource;
        $this->_output = require_once $controller;
        $this->_processed = true;
        return ;
    }
 }
?>
