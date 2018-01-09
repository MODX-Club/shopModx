<?php
$xpdo_meta_map['ShopmodxOrderProduct']= array (
  'package' => 'shopModx',
  'version' => '1.1',
  'table' => 'shopmodx_order_products',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'order_id' => NULL,
    'product_id' => NULL,
    'quantity' => NULL,
    'price' => 0,
    'currency_id' => NULL,
  ),
  'fieldMeta' => 
  array (
    'order_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'index',
    ),
    'product_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
    ),
    'quantity' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
    ),
    'price' => 
    array (
      'dbtype' => 'double',
      'attributes' => 'unsigned',
      'phptype' => 'float',
      'null' => false,
      'default' => 0,
    ),
    'currency_id' => 
    array (
      'default' => null,
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'index',
    ),
  ),
  'indexes' => 
  array (
    'order_id' => 
    array (
      'alias' => 'order_id',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'order_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'product_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'currency_id' => 
    array (
      'alias' => 'currency_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'currency_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
    'aggregates' => array(
        'Order' => 
        array (
          'class' => 'ShopmodxOrder',
          'local' => 'order_id',
          'foreign' => 'id',
          'cardinality' => 'one',
          'owner' => 'foreign',
        ),
        'Product' => 
        array (
          'class' => 'modResource',
          'local' => 'product_id',
          'foreign' => 'id',
          'cardinality' => 'one',
          'owner' => 'foreign',
        ),
    ),
);
