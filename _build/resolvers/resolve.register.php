<?php


$pkgName = 'shopModx';

$pkgLowerName = strtolower($pkgName);

if ($object->xpdo) {
    $modx =& $object->xpdo;
    $modelPath = $modx->getOption($pkgLowerName.'.core_path',null,$modx->getOption('core_path').'components/'.$pkgLowerName.'/').'model/';

    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            if ($modx instanceof modX) {
                $modx->addExtensionPackage($pkgName, '[[++core_path]]components/'.$pkgLowerName.'/model/', array(
                    'serviceName' => 'shopModx',
                    'serviceClass' => $pkgName,
                ));
                $modx->log(xPDO::LOG_LEVEL_INFO, 'Adding ext package');
            } 
            break; 
            
        case xPDOTransport::ACTION_UNINSTALL:
            if ($modx instanceof modX) {
                $modx->removeExtensionPackage($pkgName);
            }
            break;
    }
}
return true;