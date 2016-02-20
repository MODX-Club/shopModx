<?php

require_once dirname(dirname(__FILE__)). '/getdata.class.php';

class modShopmodxOrdersGetdataProcessor extends modShopmodxGetdataProcessor{
    
    public $classKey = 'ShopmodxOrder';
    public $defaultSortField = 'id';
    
    public function initialize(){
        $this->setDefaultProperties(array(
            "sort"  => "{$this->classKey}.id",
            "dir"   => "desc",
            "include_canceled_products"  => false,      // Включая отмененные
            "show_deleted"  => false,
        ));
        return parent::initialize();
    }
    
    
    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);
        
        $alias = $c->getAlias();
        
        $where = array();
        
        $c->innerJoin('ShopmodxOrderStatus', 'Status');
        $c->leftJoin('ShopmodxOrderProduct', 'OrderProducts');
        $c->leftJoin('ShopmodxPayment', 'Payment', "Payment.order_id = {$alias}.id");
        $c->leftJoin('ShopmodxPaysystem', 'Paysystem', "Payment.paysystem_id = Paysystem.id");
        
        # $order_products_table = $this->modx->getTableName('ShopmodxOrderProduct');
        
        
        if($status = $this->getProperty('status')){
            $where['status_id'] = $status;
        }
        
        if(!$this->getProperty('include_canceled_products')){
            $where['OrderProducts.quantity:>'] = 0;
        }
        
        if($where){
            $c->where($where);
        }
        
        $c->groupby("{$alias}.id");
        
        /*$c->prepare();
        print $c->toSQL();
        exit;*/
         
        return $c;
    }
    
    protected function setSelection(xPDOQuery $c){
        
        $c = parent::setSelection($c);
        
        $alias = $c->getAlias();
        
        $c->select(array(
            "`{$this->classKey}`.id as order_id", 
            "Status.status as status_str", 
            # "(select sum(op.price * op.quantity) from {$order_products_table} op where op.order_id = {$this->classKey}.id) as sum",
            "count(*) as positions",
            "sum(OrderProducts.quantity) as total",
            "sum(OrderProducts.price * OrderProducts.quantity) as sum",
            "Payment.id as pay_id",
            "Payment.paysys_invoice_id",
            "Payment.date as pay_date",
            "Payment.sum as pay_sum",
            "Paysystem.name as paysystem_name",
        )); 
        
        return $c;
    }
}


# class modMgrOrdersGetlistProcessor extends modMgrGetlistProcessor{
#     public $classKey = 'Order';
#     public $defaultSortField = 'id';
#     
#     public function initialize(){
#         $this->setDefaultProperties(array(
#             "sort"  => "{$this->classKey}.id",
#             "dir"   => "desc",
#         ));
#         return parent::initialize();
#     }
#     
#     
#     public function prepareQueryBeforeCount(xPDOQuery $c){
#         $c = parent::prepareQueryBeforeCount($c);
#         
#         $alias = $c->getAlias();
#         
#         $c->innerJoin('OrderStatus', 'Status');
#         $c->leftJoin('Payment', 'Payment', "Payment.order_id = {$alias}.id");
#         $c->leftJoin('Paysystem', 'Paysystem', "Payment.paysystem_id = Paysystem.id");
#         
#         $order_products_table = $this->modx->getTableName('OrderProduct');
#         
#         $c->select(array(
#             "`{$this->classKey}`.id as order_id", 
#             "Status.status as status_str", 
#             "(select sum(op.price * op.quantity) from {$order_products_table} op where op.order_id = {$this->classKey}.id) as sum",
#             "Payment.id as pay_id",
#             "Payment.paysys_invoice_id",
#             "Payment.date as pay_date",
#             "Payment.sum as pay_sum",
#             "Paysystem.name as paysystem_name",
#         )); 
#         
#         
#         /*$c->prepare();
#         print $c->toSQL();
#         exit;*/
#          
#         return $c;
#     }
# }

return 'modShopmodxOrdersGetdataProcessor';