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
            $manager->createObjectContainer('ShopmodxCategory');
            $manager->createObjectContainer('ShopmodxClient');
            $manager->createObjectContainer('ShopmodxLegalForm');
            $manager->createObjectContainer('ShopmodxProducer');
            $manager->createObjectContainer('ShopmodxProduct');
            $manager->createObjectContainer('ShopmodxVendor');
            $manager->createObjectContainer('ShopmodxWarehouse');
            $modx->setLogLevel(modX::LOG_LEVEL_INFO);
            break;
    }
}
return true;