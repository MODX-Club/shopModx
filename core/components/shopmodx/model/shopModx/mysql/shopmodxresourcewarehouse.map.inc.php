<?php
$xpdo_meta_map['ShopmodxResourceWarehouse']= array (
  'package' => 'Shopmodx',
  'version' => '1.1',
  'extends' => 'ShopmodxResource',
  'fields' => 
  array (
      'class_key' => 'ShopmodxResourceWarehouse',
  ),
  'fieldMeta' => 
  array (),
  'composites' => array(
    'Warehouse' => 
        array (
            'class' => 'ShopmodxWarehouse',
            'local' => 'id',
            'foreign' => 'resource_id',
            'cardinality' => 'one',
            'owner' => 'local',
        ),
  ),
);
