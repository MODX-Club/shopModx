<?php
$xpdo_meta_map['ShopmodxOrder']= array (
  'package' => 'shopModx',
  'version' => '1.1',
  'table' => 'shopmodx_orders',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array ( 
    'number_history' => 1,
    'status_id' => 1,
    'contractor' => NULL,
    'createdby' => NULL,
    'editedby' => NULL,
    'createdon' => 'CURRENT_TIMESTAMP',
    'editedon' => NULL,
    'manager' => NULL,
    'address' => NULL,
    'comments' => NULL,
    'discount' => 0,
  ),
  'fieldMeta' => 
  array ( 
    'number_history' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 1,
    ),
    'status_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 1,
      'index' => 'index',
    ),
    'contractor' => 
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
      'precision' => '11',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
      'index' => 'index',
    ),
    'editedby' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
      'index' => 'index',
    ),
    'createdon' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 'CURRENT_TIMESTAMP',
      'index' => 'index',
    ),
    'editedon' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => true,
    ),
    'manager' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
      'index' => 'index',
    ),
    'address' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
      'null' => true,
    ),
    'comments' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
      'null' => true,
    ),
    'discount' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '3',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
  ),
  'indexes' => 
  array ( 
    'status_id' => 
    array (
      'alias' => 'status_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'status_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'contractor' => 
    array (
      'alias' => 'contractor',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'contractor' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
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
    'editedby' => 
    array (
      'alias' => 'editedby',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'editedby' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
    'createdon' => 
    array (
      'alias' => 'createdon',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'createdon' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'manager' => 
    array (
      'alias' => 'manager',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'manager' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
  ),
  'aggregates' => array(
        'Status' => 
        array (
          'class' => 'ShopmodxOrderStatus',
          'local' => 'status_id',
          'foreign' => 'id',
          'cardinality' => 'one',
          'owner' => 'foreign',
        ),
        'Contractor' => 
        array (
          'class' => 'modUser',
          'local' => 'contractor',
          'foreign' => 'id',
          'cardinality' => 'one',
          'owner' => 'foreign',
        ),
        'Manager' => 
        array (
          'class' => 'modUser',
          'local' => 'manager',
          'foreign' => 'id',
          'cardinality' => 'one',
          'owner' => 'foreign',
        ),
    ),
    'composites' => array(
        'OrderProducts' => 
        array (
          'class' => 'ShopmodxOrderProduct',
          'local' => 'id',
          'foreign' => 'order_id',
          'cardinality' => 'many',
          'owner' => 'local',
        ),
    ),
);
