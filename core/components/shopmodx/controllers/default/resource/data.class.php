<?php

/*
 * ShopxResourceDataManagerController
 * ShopxResourceProductModelDataManagerController
 * ShopxResourceWarehouseDataManagerController
 * ShopxResourceLegalFormDataManagerController
 * ShopxResourceCurrencyDataManagerController
 */

require_once MODX_MANAGER_PATH. "controllers/default/resource/data.class.php";

class ShopxResourceDataManagerController extends ResourceDataManagerController{
    
}

class ShopxResourceProductModelDataManagerController  extends ShopxResourceDataManagerController{}
class ShopxResourceWarehouseDataManagerController  extends ShopxResourceDataManagerController{}
class ShopxResourceLegalFormDataManagerController  extends ShopxResourceDataManagerController{}
class ShopxResourceCurrencyDataManagerController  extends ShopxResourceDataManagerController{}

return 'ShopxResourceDataManagerController';