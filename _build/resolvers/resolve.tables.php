<?php

if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $object->xpdo;
            $modelPath = $modx->getOption('shopmodx.core_path',null,$modx->getOption('core_path').'components/shopmodx/').'model/';
            $modx->addPackage('shopModx',$modelPath);

            $manager = $modx->getManager();
            $modx->setLogLevel(modX::LOG_LEVEL_ERROR);
            $manager->createObjectContainer('ShopmodxSimpleObject');
            $manager->createObjectContainer('ShopmodxClient');
            $manager->createObjectContainer('ShopmodxProduct');
            $modx->setLogLevel(modX::LOG_LEVEL_INFO);
            break;
    }
}
return true;