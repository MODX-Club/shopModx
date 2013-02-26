<?php
$xpdo_meta_map['ShopmodxLegalForm']= array (
  'package' => 'shopModx',
  'version' => '1.1',
  'extends' => 'ShopmodxSimpleObject',
  'fields' => 
  array (
    'class_key' => 'ShopmodxLegalForm',
  ),
  'fieldMeta' => 
  array (),
  'indexes' => 
  array (),
  'aggregates' => array(
    'Clients' => 
        array (
            'class' => 'ShopmodxClient',
            'local' => 'id',
            'foreign' => 'sm_legal_form',
            'cardinality' => 'many',
            'owner' => 'local', 
        ),
  ),
);
