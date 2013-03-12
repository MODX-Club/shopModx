<?php
$xpdo_meta_map['ShopmodxResource']= array (
  'package' => 'Shopmodx',
  'version' => '1.1',
  'extends' => 'modResource',
  'fields' => 
  array (
      'class_key' => 'ShopmodxResource',
  ),
  'fieldMeta' => 
  array (),
  'aggregates' => 
  array (  
    'Template' => 
    array (
      'class' => 'ShopmodxTemplate',
      'local' => 'template',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ), 
  ),
);
