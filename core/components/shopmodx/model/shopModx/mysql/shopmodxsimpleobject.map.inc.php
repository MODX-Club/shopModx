<?php
$xpdo_meta_map['ShopmodxSimpleObject']= array (
  'package' => 'Shopmodx',
  'version' => '1.1',
  'table' => 'shopmodx_simple_object',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'resource_id' => NULL,
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
