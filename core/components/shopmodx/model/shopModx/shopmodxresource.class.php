<?php

class shopModxResource extends modResource{
    function __construct(xPDO &$xpdo) {
        $xpdo->lexicon->load('shopmodx:resource');
        return parent::__construct($xpdo);
    }
}