<?php

require_once dirname(dirname(__FILE__)). '/getlist.class.php';

class modShopmodxBillingMgrOrdersGetlistProcessor extends modShopmodxBillingMgrGetlistProcessor{
    public $classKey = 'ShopmodxOrder';
    public $defaultSortField = 'id';
    
    public function initialize(){
        $this->setDefaultProperties(array(
            "sort"  => "id",
            "dir"   => "desc",
        ));
        return parent::initialize();
    }
    
    
    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);
        
        $alias = $c->getAlias();
        
        $c->innerJoin('ShopmodxOrderStatus', 'Status');
        $c->leftJoin('ShopmodxPayment', 'Payment', "Payment.order_id = {$alias}.id");
        $c->leftJoin('ShopmodxPaysystem', 'Paysystem', "Payment.paysystem_id = Paysystem.id");
        
        $order_products_table = $this->modx->getTableName('ShopmodxOrderProduct');
        
        $c->select(array(
            "`{$this->classKey}`.id as order_id", 
            "Status.status as status_str", 
            "(select sum(op.price * op.quantity) from {$order_products_table} op where op.order_id = {$this->classKey}.id) as sum",
            "Payment.id as pay_id",
            "Payment.paysys_invoice_id",
            "Payment.date as pay_date",
            "Payment.sum as pay_sum",
            "Paysystem.name as paysystem_name",
        )); 
        
        
        /*$c->prepare();
        print $c->toSQL();
        exit;*/
         
        return $c;
    }
}

return 'modShopmodxBillingMgrOrdersGetlistProcessor';