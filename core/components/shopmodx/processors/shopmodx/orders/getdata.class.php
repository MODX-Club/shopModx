<?php

require_once dirname(dirname(__FILE__)). '/getdata.class.php';

class modShopmodxOrdersGetdataProcessor extends modShopmodxGetdataProcessor{
    
    public $classKey = 'ShopmodxOrder';
    public $defaultSortField = 'id';
    
    
    public function checkPermissions(){
        
        return $this->modx->user->id && parent::checkPermissions();
    }
    
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            "sort"  => "{$this->classKey}.id",
            "dir"   => "desc",
            "include_canceled_products"  => false,      // Включая отмененные
            "show_deleted"  => false,
        ));
        
        
        if(!$this->modx->hasPermission('view_all_orders')){
            $this->setProperties(array(
                "contractor"    => $this->modx->user->id,
            ));
        }

        // return json_encode(print_r($this->modx->user->toArray(), 1));
        // return json_encode(print_r($this->properties, 1));
        
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
        
        $c->leftJoin('modUser', 'Contractor');
        $c->leftJoin('modUserProfile', 'ContractorProfile', 'Contractor.id=ContractorProfile.internalKey');
        $c->leftJoin('modUser', 'Manager');
        $c->leftJoin('modUserProfile', 'ManagerProfile', 'Manager.id=ManagerProfile.internalKey');
        $c->select(array(
            "ContractorProfile.fullname  as contractor_fullname",
            "ContractorProfile.email as contractor_email",
            "if(ContractorProfile.mobilephone != '', ContractorProfile.mobilephone, ContractorProfile.phone) as contractor_phone",
            "ManagerProfile.fullname as manager_fullname",
        ));
        
        # $order_products_table = $this->modx->getTableName('ShopmodxOrderProduct');
        
        
        if($status = (int)$this->getProperty('status')){
            $where['status_id'] = $status;
        }
        
        if($contractor = (int)$this->getProperty('contractor')){
            $where['contractor'] = $contractor;
        }
        
        
        if($date_from = $this->getProperty('date_from')){
            $date = date('Y-m-d', strtotime($this->getProperty('date_till')));
            $where[] = "date_format({$alias}.createdon, '%Y-%m-%d') >= '{$date}'";
        }
        
        if($date_from = $this->getProperty('date_till')){
            $date = date('Y-m-d', strtotime($this->getProperty('date_till')));
            $where[] = "date_format({$alias}.createdon, '%Y-%m-%d') <= '{$date}'";
        }
        
        
        if(!$this->getProperty('include_canceled_products')){
            $where['OrderProducts.quantity:>'] = 0;
        }
        
        if($where){
            $c->where($where);
        }
        
        
        if($search = $this->getProperty('search')){
            $word = $this->modx->quote("%{$search}%");
            
            $q = $this->modx->newQuery('ShopmodxOrderProduct');
            $q->innerJoin('modResource', 'ResourceProduct', "ResourceProduct.id = ShopmodxOrderProduct.product_id");
            
            $q_alias = $q->getAlias();
            
            $q->select(array(
                "{$q_alias}.order_id",
            ));
            
            $order_id = (int)$search;
            
            $q->where(array(
                "order_id = {$alias}.id 
                AND (order_id = {$order_id}
                    OR ResourceProduct.pagetitle LIKE {$word} 
                    OR ResourceProduct.longtitle LIKE {$word}
                    OR ResourceProduct.content LIKE {$word}
                )",
            ));
            
            $q->prepare();
            $sql = $q->toSQL();
            
            # print $sql;
            
            $conditions = [];
            
            $conditions[] = new xPDOQueryCondition(array(
                'sql' => "ContractorProfile.address LIKE {$word}",
            ));
            
            if($phone = preg_replace('/[^\+0-9\-\(\)]/', '', $search)){
                $phone = $this->modx->quote("%{$phone}%");
                
                $conditions[] = new xPDOQueryCondition(array(
                    'sql' => "REPLACE(ContractorProfile.phone, ' ', '') LIKE {$phone}",
                    'conjunction'   => $conditions ? "OR" : "AND",
                ));
            }
            
            $conditions[] = new xPDOQueryCondition(array(
                'sql' => "EXISTS ({$sql})",
                'conjunction'   => $conditions ? "OR" : "AND",
            ));
            
            $c->query['where'][] = $conditions;
            
            
            # $c->prepare();
            # print $c->toSQL();
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
    
    
    public function afterIteration(array $list){
        
        $list = parent::afterIteration($list);
        
        foreach($list as & $l){
            $l['original_sum'] = $l['sum'];
            
            if(!empty($l['discount'])){
                $l['sum'] = round($l['sum'] * ((100 - $l['discount'])/100));
            }
        }
        
        return $list;
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