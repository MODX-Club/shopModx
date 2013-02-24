<?php
$xpdo_meta_map['ShopmodxResourceCategory']= array (
  'package' => 'Shopmodx',
  'version' => '1.1',
  'extends' => 'ShopmodxResource',
  'fields' => 
  array (
      'class_key'   => 'ShopmodxResourceCategory',
  ),
  'fieldMeta' => 
  array (),
  'composites' => array(
    'Category' => 
        array (
            'class' => 'ShopmodxCategory',
            'local' => 'id',
            'foreign' => 'resource_id',
            'cardinality' => 'one',
            'owner' => 'local',
        ),
  ),
);
