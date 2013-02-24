<?php
$xpdo_meta_map['ShopmodxResourceVendor']= array (
  'package' => 'Shopmodx',
  'version' => '1.1',
  'extends' => 'ShopmodxResource',
  'fields' => 
  array (
      'class_key' => 'ShopmodxResourceVendor',
  ),
  'fieldMeta' => 
  array (),
  'composites' => array(
    'Vendor' => 
        array (
            'class' => 'ShopmodxVendor',
            'local' => 'id',
            'foreign' => 'resource_id',
            'cardinality' => 'one',
            'owner' => 'local',
        ),
  ),
);
