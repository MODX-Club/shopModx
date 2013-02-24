<?php
$xpdo_meta_map['ShopmodxResourceProducer']= array (
  'package' => 'Shopmodx',
  'version' => '1.1',
  'extends' => 'ShopmodxResource',
  'fields' => 
  array (
      'class_key' => 'ShopmodxResourceProducer',
  ),
  'fieldMeta' => 
  array (),
  'composites' => array(
    'Producer' => 
        array (
            'class' => 'ShopmodxProducer',
            'local' => 'id',
            'foreign' => 'resource_id',
            'cardinality' => 'one',
            'owner' => 'local',
        ),
  ),
);
