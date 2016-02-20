<?php

require_once dirname(dirname(__FILE__)). '/getlist.class.php';

class modMgrOrdersGridGetlistProcessor extends modMgrOrdersGetlistProcessor{
    
    public function initialize(){
        $this->setDefaultProperties(array(
            'json'  => true,
        ));
        return parent::initialize();
    }
    
    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);
        
        //$c->leftJoin('modUser', 'ModifiedBy');
        //$c->leftJoin('modUserProfile', 'ModifiedByProfile', 'ModifiedBy.id=ModifiedByProfile.internalKey');
        $c->leftJoin('modUser', 'Contractor');
        $c->leftJoin('modUserProfile', 'ContractorProfile', 'Contractor.id=ContractorProfile.internalKey');
        $c->leftJoin('modUser', 'Manager');
        $c->leftJoin('modUserProfile', 'ManagerProfile', 'Manager.id=ManagerProfile.internalKey');
        $c->select(array(
            "ContractorProfile.fullname  as contractor_fullname",
            "ContractorProfile.email as contractor_email",
            "ContractorProfile.address as contractor_address",
            "if(ContractorProfile.mobilephone != '', ContractorProfile.mobilephone, ContractorProfile.phone) as contractor_phone",
            "ManagerProfile.fullname as manager_fullname",
        ));
        
        $className = 'OrderStatus';
        $tableAlias = 'Status';
        
        # $c->leftJoin($className, $tableAlias);
        
        if($columns = $this->modx->getSelectColumns($className, $tableAlias, 'status_')){
            $columns = explode(',', $columns);
            $c->select($columns);
        }
        
        # exit;
        
        // Проверяем право видеть все заявки
        if(!$this->modx->hasPermission('view_all_orders')){
            $c->andCondition(array(
                array(
                    'status_id:in' => array(1,2,8),
                    'manager'   => null,
                ),
                'OR:manager:=' => $this->modx->user->id,
            ));
        }
        
        if($id = $this->getProperty('order_id')){
            $c->where(array(
                "`{$this->classKey}`.id" => $id,
            ));
        }
        
        return $c;
    }
    
    
    # public function setSelection(xPDOQuery & $c){
    #     $c = parent::setSelection($c);
    #     
    #     
    #     return $c;
    # }
    
    
    public function prepareRow($object){
        
        $row = parent::prepareRow($object);
        
        $menu = array(
            array(
                'text' => 'Добавить товар',
                'handler'   => 'this.addProduct',
            ),
            
        );
        
        // Если статус Новый, то предлагаем принять в работу
        switch($row['status_id']){
            case '1':   // Новый
                break;
            case '2':   // Оформленный пользователем
            case '8':   // Оплачен
                if(!$row['manager']){
                    $menu[] = array(
                        'text' => 'Принять заказ',
                        'handler'   => 'this.takeOrder',
                    );
                }
                break;
            default:
                $menu[] = array(
                    'text' => 'Изменить статус',
                    'handler'   => 'this.updateOrderStatus',
                );
        }
        
        if($row['status_id'] > 1){
            
            $menu[] = array(
                'text' => 'Печать заказа',
                'handler'   => 'this.printOrder',
            );
        }
        
        if($this->modx->hasPermission('delete_order')){
            $menu[] = array(
                'text' => 'Удалить заказ',
                'handler'   => 'this.deleteOrder',
            );
        }
        
        if(!empty($row['contractor'])){
            $menu[] = array(
                'text' => 'Данные клиента',
                'handler'   => 'this.ShowContractorInfo',
            );
            $menu[] = array(
                'text' => 'Фильтровать по клиенту',
                'handler'   => 'this.FilterByContractor',
            );
        }
        
        $row['menu'] = $menu;
        
        return $row;
    }
}

return 'modMgrOrdersGridGetlistProcessor';