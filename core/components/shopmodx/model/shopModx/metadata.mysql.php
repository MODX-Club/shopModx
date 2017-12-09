<?php


$xpdo_meta_map = array (
  'modResource' => 
  array (
    # 'ShopmodxResource',
    # 'ShopmodxResourceProductModel',
    # 'ShopmodxResourceProduct',
    # 'ShopmodxResourceCurrency',
    //'ShopmodxResourceWarehouse',
    //'ShopmodxResourceLegalForm',
    //'ShopmodxResourceClient',
  ),
);


$this->map['modResource']['aggregates']['ProductData'] = array(
    'class' => 'ShopmodxProductData',
    'local' => 'id',
    'foreign' => 'id',
    'cardinality' => 'one',
    'owner' => 'local',
);

$this->map['modResource']['composites']['Product'] = array(
    'class' => 'ShopmodxProduct',
    'local' => 'id',
    'foreign' => 'resource_id',
    'cardinality' => 'one',
    'owner' => 'local',
);


$this->map['modUser']['composites']['Orders'] = array(
    'class' => 'ShopmodxOrder',
    'local' => 'id',
    'foreign' => 'contractor',
    'cardinality' => 'many',
    'owner' => 'foreign',
);


$custom_fields = array(
    "modResource"   => array(
        
        "fields"    => array(
            "price"  => array(
                "defaultValue"  => 0,
                "metaData"  => array (
                    'default' => 0,
                    'dbtype' => 'decimal',
                    'precision' => '10,2',
                    'attributes' => 'unsigned',
                    'phptype' => 'float',
                    'null' => false,
                    'index' => 'index',
                ),
            ),
            "price_old"  => array(
                "defaultValue"  => null,
                "metaData"  => array (
                    'default' => null,
                    'dbtype' => 'decimal',
                    'precision' => '10,2',
                    'attributes' => 'unsigned',
                    'phptype' => 'float',
                    'null' => true,
                ),
            ),
            "article"  => array(
                "defaultValue"  => NULL,
                "metaData"  => array (
                    'dbtype' => 'varchar',
                    'precision' => '36',
                    'phptype' => 'string',
                    'null' => true,
                    'index' => 'article',
                ),
            ),
            "currency"  => array(
                "defaultValue"  => NULL,
                "metaData"  => array (
                    'dbtype' => 'int',
                    'precision' => '10',
                    'attributes' => 'unsigned',
                    'phptype' => 'integer',
                    'null' => true,
                    'index' => 'currency',
                ),
            ),
        ),
        
        "indexes"   => array(
            'price' => 
            array (
              'alias' => 'price',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' => 
              array (
                'price' => 
                array (
                  'length' => '',
                  'collation' => 'A',
                  'null' => false,
                ),
              ),
            ),
            'article' => 
            array (
              'alias' => 'article',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' => 
              array (
                'article' => 
                array (
                  'length' => '',
                  'collation' => 'A',
                  'null' => false,
                ),
              ),
            ),
            'currency' => 
            array (
              'alias' => 'currency',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' => 
              array (
                'currency' => 
                array (
                  'length' => '',
                  'collation' => 'A',
                  'null' => false,
                ),
              ),
            ),
        ),
    ),
);

foreach($custom_fields as $class => $class_data){
    foreach($class_data['fields'] as $field => $data){
        $this->map[$class]['fields'][$field] = $data['defaultValue'];
        $this->map[$class]['fieldMeta'][$field] = $data['metaData'];
    }
    
    if(!empty($class_data['indexes'])){
        foreach($class_data['indexes'] as $index => $data){
            $this->map[$class]['indexes'][$index] = $data;
        }
    }
}
