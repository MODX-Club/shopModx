<?php
$xpdo_meta_map['ShopmodxLegalForm']= array (
  'package' => 'shopModx',
  'version' => '1.1',
  'table' => 'shopmodx_legal_forms',
  'extends' => 'ShopmodxSimpleObject',
  'fields' => 
  array (
    'sm_name' => '',
  ),
  'fieldMeta' => 
  array (
    'sm_name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
  ),
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
