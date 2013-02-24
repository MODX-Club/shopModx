<?php
$xpdo_meta_map['ShopmodxResourceLegalForm']= array (
  'package' => 'Shopmodx',
  'version' => '1.1',
  'extends' => 'ShopmodxResource',
  'fields' => 
  array (
      'class_key' => 'ShopmodxResourceLegalForm',
  ),
  'fieldMeta' => 
  array (),
  'composites' => array(
    'LegalForm' => 
        array (
            'class' => 'ShopmodxLegalForm',
            'local' => 'id',
            'foreign' => 'resource_id',
            'cardinality' => 'one',
            'owner' => 'local',
        ),
  ),
);
