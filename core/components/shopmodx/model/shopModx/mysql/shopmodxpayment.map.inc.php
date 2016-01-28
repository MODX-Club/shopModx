<?php
$xpdo_meta_map['ShopmodxPayment']= array (
  'package' => 'shopModx',
  'version' => '1.1',
  'table' => 'shopmodx_payments',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'sum' => 0,
    'currency_id' => NULL,
    'order_id' => NULL,
    'paysystem_id' => NULL,
    'paysys_invoice_id' => NULL,
    'date' => 'CURRENT_TIMESTAMP',
    'owner' => NULL,
    'createdby' => NULL,
  ),
  'fieldMeta' => 
  array (
    'sum' => 
    array (
      'dbtype' => 'double',
      'attributes' => 'unsigned',
      'phptype' => 'float',
      'null' => false,
      'default' => 0,
    ),
    'currency_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
      'index' => 'index',
    ),
    'order_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
      'index' => 'index',
    ),
    'paysystem_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
      'index' => 'index',
    ),
    'paysys_invoice_id' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '64',
      'phptype' => 'string',
      'null' => true,
    ),
    'date' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 'CURRENT_TIMESTAMP',
      'index' => 'index',
    ),
    'owner' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
      'index' => 'index',
    ),
    'createdby' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
      'index' => 'index',
    ),
  ),
  'indexes' => 
  array (
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
          'null' => true,
        ),
      ),
    ),
    'paysystem_id' => 
    array (
      'alias' => 'paysystem_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'paysystem_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
    'date' => 
    array (
      'alias' => 'date',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'date' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'createdby' => 
    array (
      'alias' => 'createdby',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'createdby' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
    'order_id' => 
    array (
      'alias' => 'order_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'order_id' => 
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
          'class' => 'ShopmodxCurrency',
          'local' => 'currency_id',
          'foreign' => 'id',
          'cardinality' => 'one',
          'owner' => 'foreign',
        ),
        'Order' => 
        array (
          'class' => 'ShopmodxOrder',
          'local' => 'order_id',
          'foreign' => 'id',
          'cardinality' => 'one',
          'owner' => 'foreign',
        ),
        'Paysystem' => 
        array (
          'class' => 'ShopmodxPaysystem',
          'local' => 'paysystem_id',
          'foreign' => 'id',
          'cardinality' => 'one',
          'owner' => 'foreign',
        ),
        'Createdby' => 
        array (
          'class' => 'modUser',
          'local' => 'createdby',
          'foreign' => 'id',
          'cardinality' => 'one',
          'owner' => 'foreign',
        ),
        'Owner' => 
        array (
          'class' => 'modUser',
          'local' => 'owner',
          'foreign' => 'id',
          'cardinality' => 'one',
          'owner' => 'foreign',
        ),
    ),
);
