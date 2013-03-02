<?php

$settings = array();

$setting = $modx->newObject('modSystemSetting');
$setting->fromArray(array(
    'key' => 'shopmodx.default_currency', 
    'value' => '',
    'xtype' => 'shopmodx-combo-currencies', 
    'namespace' => NAMESPACE_NAME,
    'area' => 'shop',
),'',true,true);
$settings[] = $setting;
unset($setting);

$setting = $modx->newObject('modSystemSetting');
$setting->fromArray(array(
    'key' => 'shopmodx.product_default_template', 
    'value' => '',
    'xtype' => 'modx-combo-template', 
    'namespace' => NAMESPACE_NAME,
    'area' => 'shop',
),'',true,true);
$settings[] = $setting;
unset($setting);

return $settings;