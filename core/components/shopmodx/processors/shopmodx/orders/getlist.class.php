<?php

require_once dirname(__FILE__) . '/getdata.class.php';

class modShopmodxOrdersGetlistProcessor extends modShopmodxOrdersGetdataProcessor{
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            "grid"  => true,
            "format"  => 'json',
        ));
        
        return parent::initialize();
    }

    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);
        
        //$c->leftJoin('modUser', 'ModifiedBy');
        //$c->leftJoin('modUserProfile', 'ModifiedByProfile', 'ModifiedBy.id=ModifiedByProfile.internalKey');
        
        
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
        
        # if($id = $this->getProperty('order_id')){
        #     $c->where(array(
        #         "`{$this->classKey}`.id" => $id,
        #     ));
        # }
        
        return $c;
    }
    
    
    public function afterIteration(array $list){
        
        $list = parent::afterIteration($list);
        
        
        foreach($list as & $l){
            
            $menu = array(
                # array(
                #     'text' => 'Добавить товар',
                #     'handler'   => 'this.addProduct',
                # ),
                
            );
            
            // Если статус Новый, то предлагаем принять в работу
            switch($l['status_id']){
                case '1':   // Новый
                    break;
                case '2':   // Оформленный пользователем
                case '8':   // Оплачен
                    # if(!$l['manager']){
                    #     $menu[] = array(
                    #         'text' => 'Принять заказ',
                    #         'handler'   => 'this.takeOrder',
                    #     );
                    # }
                    break;
                default:
                    # $menu[] = array(
                    #     'text' => 'Изменить статус',
                    #     'handler'   => 'this.updateOrderStatus',
                    # );
            }
            
            if($l['status_id'] > 1){
                
                # $menu[] = array(
                #     'text' => 'Печать заказа',
                #     'handler'   => 'this.printOrder',
                # );
            }
            
            if($this->modx->hasPermission('delete_order')){
                # $menu[] = array(
                #     'text' => 'Удалить заказ',
                #     'handler'   => 'this.deleteOrder',
                # );
            }
            
            if(!empty($l['contractor'])){
                $menu[] = array(
                    'text' => 'Данные клиента',
                    'handler'   => 'this.ShowContractorInfo',
                );
                $menu[] = array(
                    'text' => 'Фильтровать по клиенту',
                    'handler'   => 'this.FilterByContractor',
                );
            }
            
            $l['menu'] = $menu;
        }
        
        unset($l);
        
        return $list;
    }

}

return 'modShopmodxOrdersGetlistProcessor';
