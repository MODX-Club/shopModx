<?php


$xpdo_meta_map = array (
  'modResource' => 
  array (
    'ShopmodxResource',
    'ShopmodxResourceProductModel',
    'ShopmodxResourceProduct',
    'ShopmodxResourceCurrency',
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
                    'dbtype' => 'decimal',
                    'precision' => '10,2',
                    'attributes' => 'unsigned',
                    'phptype' => 'integer',
                    'null' => false,
                    'index' => 'index',
                ),
            ),
            # "image"  => array(
            #     "defaultValue"  => NULL,
            #     "metaData"  => array (
            #       'dbtype' => 'varchar',
            #       'precision' => '512',
            #       'phptype' => 'string',
            #       'null' => false,
            #     ),
            # ),
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
