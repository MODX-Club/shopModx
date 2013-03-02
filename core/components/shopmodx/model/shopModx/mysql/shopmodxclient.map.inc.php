<?php
$xpdo_meta_map['ShopmodxClient']= array (
  'package' => 'shopModx',
  'version' => '1.1',
  'table' => 'shopmodx_clients',
  'extends' => 'ShopmodxSimpleObject',
  'fields' => 
  array (
    'class_key' => 'ShopmodxClient',
    'sm_name' => '',
    'sm_fullname' => NULL,
    'sm_is_customer' => '0',
    'sm_is_supplier' => '0',
    'sm_legal_form' => NULL,
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
    'sm_fullname' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'index' => 'index',
    ),
    'sm_is_customer' => 
    array (
      'dbtype' => 'enum',
      'precision' => '\'0\',\'1\'',
      'phptype' => 'string',
      'null' => false,
      'default' => '0',
    ),
    'sm_is_supplier' => 
    array (
      'dbtype' => 'enum',
      'precision' => '\'0\',\'1\'',
      'phptype' => 'string',
      'null' => false,
      'default' => '0',
    ),
    'sm_legal_form' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => true,
    ),
  ),
  'indexes' => 
  array (
    'sm_fullname' => 
    array (
      'alias' => 'sm_fullname',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'sm_fullname' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
    'sm_legal_form' => 
    array (
      'alias' => 'sm_legal_form',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'sm_legal_form' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
  ),
  'aggregates' => array(
    'LegalForm' => 
        array (
            'class' => 'ShopmodxResourceLegalForm',
            'local' => 'sm_legal_form',
            'foreign' => 'id',
            'cardinality' => 'one',
            'owner' => 'foreign', 
        ),
  ),
);
