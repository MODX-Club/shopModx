<?php
$xpdo_meta_map['ShopmodxResourceProduct']= array (
  'package' => 'Shopmodx',
  'version' => '1.1',
  'extends' => 'ShopmodxResource',
  'fields' => 
  array (
      'class_key' => 'ShopmodxResourceProduct',
  ),
  'fieldMeta' => 
  array (),
  'composites' => array(
    'Product' => 
        array (
            'class' => 'ShopmodxProduct',
            'local' => 'id',
            'foreign' => 'resource_id',
            'cardinality' => 'one',
            'owner' => 'local',
        ),
  ),
);
