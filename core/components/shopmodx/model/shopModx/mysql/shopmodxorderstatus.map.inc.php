<?php
$xpdo_meta_map['ShopmodxOrderStatus']= array (
  'package' => 'shopModx',
  'version' => '1.1',
  'table' => 'shopmodx_order_statuses',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'status' => NULL,
    'color' => '',
    'rank' => 0,
    'comment' => NULL,
  ),
  'fieldMeta' => 
  array (
    'status' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
    ),
    'color' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '7',
      'phptype' => 'string',
      'null' => false,
    ),
    'rank' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'comment' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
    ),
  ),
);
