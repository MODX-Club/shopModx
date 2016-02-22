<?php

/*
Создаем новый заказ с указанием товаров
*/

# require_once MODX_CORE_PATH . 'components/billing/processors/mgr/orders/update.class.php';
require_once dirname(dirname(__FILE__)) . '/billing/mgr/orders/update.class.php';

class modShopmodxOrdersUpdateProcessor extends modShopmodxBillingMgrOrdersUpdateProcessor{}

return 'modShopmodxOrdersUpdateProcessor';