<?php
$pkgName = 'shopModx';
$pkgNameLower = strtolower($pkgName);

if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            
            $modx =& $object->xpdo;
            $modelPath = $modx->getOption("{$pkgNameLower}.core_path",null,$modx->getOption('core_path')."components/{$pkgNameLower}/").'model/';
            
            $modx->addPackage($pkgName, $modelPath, array(
                'serviceName' => $pkgNameLower,
                'serviceClass' => "services.shopmodxService",
            ));
            
            $manager = $modx->getManager();
            $modx->setLogLevel(modX::LOG_LEVEL_ERROR);
            
            // adding xPDO objects fields
            $manager->addField('modResource', 'price');
            $manager->addField('modResource', 'price_old');
            $manager->addField('modResource', 'article');
            $manager->addField('modResource', 'currency');
            
            $manager->addIndex('modResource', 'price');
            $manager->addIndex('modResource', 'article');
            $manager->addIndex('modResource', 'currency');
            
            
            // adding xpdo objects
            $manager->createObjectContainer('ShopmodxOrder');
            $manager->createObjectContainer('ShopmodxOrderProduct');
            $manager->createObjectContainer('ShopmodxOrderStatus');
            $manager->createObjectContainer('ShopmodxPayment');
            $manager->createObjectContainer('ShopmodxPaysystem');
            
            
            // adding statuses
            $statuses = array(
                array(
                    "id"        => 1,
                    "status"    => "Наполняется",
                    "color"     => "",
                    "rank"      => 0,
                    "comment"   => "Неоформленный заказ еще на стадии наполнения корзины"
                ),
                array(
                    "id"        => 2,
                    "status"    => "Новый",
                    "color"     => "#FFCC99",
                    "rank"      => 0,
                    "comment"   => "Пользователь оформил заказ"
                ),
                array(
                    "id"        => 3,
                    "status"    => "Принят",
                    "color"     => "#FF99CC",
                    "rank"      => 0,
                    "comment"   => "Заказ принят менеджером в работу"
                ),
                array(
                    "id"        => 4,
                    "status"    => "Доставка",
                    "color"     => "#CCFFFF",
                    "rank"      => 0,
                    "comment"   => "Заказ в процессе доставки"
                ),
                array(
                    "id"        => 5,
                    "status"    => "Доставлен",
                    "color"     => "#CCFFCC",
                    "rank"      => 0,
                    "comment"   => "Заказ доставлен"
                ),
                array(
                    "id"        => 6,
                    "status"    => "Выполнен",
                    "color"     => "#00FF00",
                    "rank"      => 0,
                    "comment"   => "Заказ выполнен"
                ),
                array(
                    "id"        => 7,
                    "status"    => "Отменен",
                    "color"     => "#FF0000",
                    "rank"      => 0,
                    "comment"   => "Заказ отменен"
                ),
                array(
                    "id"        => 8,
                    "status"    => "Оплачен",
                    "color"     => "#99CC00",
                    "rank"      => 0,
                    "comment"   => "Заказ оплачен"
                ),
            );
            
            foreach($statuses as $status){
                if(!$modx->getCount('ShopmodxOrderStatus', $status['id'])){
                    $o = $modx->newObject('ShopmodxOrderStatus', $status);
                    $o->set('id', $status['id']);
                    $o->save();
                }
            }
            
            
            $modx->setLogLevel(modX::LOG_LEVEL_INFO);
        
            break;
            
    }
}
return true;
