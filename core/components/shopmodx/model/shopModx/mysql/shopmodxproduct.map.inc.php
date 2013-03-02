<?php
$xpdo_meta_map['ShopmodxProduct']= array (
  'package' => 'shopModx',
  'version' => '1.1',
  'extends' => 'ShopmodxSimpleObject',
  'table' => 'shopmodx_products',
  'fields' => 
  array (
    'class_key' => 'ShopmodxProduct',
    'sm_price' => NULL,
    'sm_currency' => NULL,
    'sm_article' => NULL,
  ),
  'fieldMeta' => 
  array (
    'sm_price' => 
    array (
      'dbtype' => 'double',
      'attributes' => 'unsigned',
      'phptype' => 'float',
      'null' => true,
      'index' => 'index',
    ),
    'sm_currency' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
    ),
    'sm_article' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => true,
      'index' => 'index',
    ),
  ),
  'indexes' => 
  array (
    'sm_price' => 
    array (
      'alias' => 'sm_price',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'sm_price' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
    'sm_currency' => 
    array (
      'alias' => 'sm_currency',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'sm_currency' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
    'sm_article' => 
    array (
      'alias' => 'sm_article',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'sm_article' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
  ),
  'aggregates' => array(
    'Currency' => 
        array (
            'class' => 'ShopmodxResourceCurrency',
            'local' => 'sm_currency',
            'foreign' => 'id',
            'cardinality' => 'one',
            'owner' => 'foreign', 
        ),
  ),
);
