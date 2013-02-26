<?php
$xpdo_meta_map['ShopmodxSimpleObject']= array (
  'package' => 'Shopmodx',
  'version' => '1.1',
  'table' => 'shopmodx_objects',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'resource_id' => NULL,
    'class_key' => 'ShopmodxObject',
  ),
  'fieldMeta' => 
  array (
    'resource_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
      'index' => 'unique',
    ),
    'class_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => 'ShopmodxObject',
      'index' => 'index',
    ),
  ),
  'indexes' => 
  array (
    'resource_id' => 
    array (
      'alias' => 'resource_id',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'resource_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
    'class_key' => 
    array (
      'alias' => 'class_key',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'class_key' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'aggregates' => array(
    'Resource' => 
        array (
            'class' => 'modResource',
            'local' => 'resource_id',
            'foreign' => 'id',
            'cardinality' => 'one',
            'owner' => 'foreign', 
        ),
  ),
);
