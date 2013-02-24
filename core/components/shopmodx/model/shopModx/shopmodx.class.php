<?php
class Shopmodx{
    
    public $modx = null;
    
    function __construct(modX &$modx) {
        $this->modx= & $modx;
    }
}