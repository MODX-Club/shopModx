<?php
$xpdo_meta_map['ShopmodxResourceClient']= array (
  'package' => 'Shopmodx',
  'version' => '1.1',
  'extends' => 'ShopmodxResource',
  'fields' => 
  array (
      'class_key' => 'ShopmodxResourceClient',
  ),
  'fieldMeta' => 
  array (),
  'composites' => array(
    'Client' => 
        array (
            'class' => 'ShopmodxClient',
            'local' => 'id',
            'foreign' => 'resource_id',
            'cardinality' => 'one',
            'owner' => 'local',
        ),
  ),
);
