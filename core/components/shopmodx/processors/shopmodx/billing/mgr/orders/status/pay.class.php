<?php

/*
    Заказ оплачен
*/

require_once dirname(__FILE__). '/update.class.php';

class modShopmodxBillingMgrOrdersStatusPayProcessor extends modShopmodxBillingMgrOrdersStatusUpdateProcessor{
    
    protected $contractor_message_tpl = "message/order_payed/contractor.tpl";
    protected $manager_message_tpl = "message/order_payed/manager.tpl";
    
    /*
        Заголовки писем
    */
    protected $contractor_message_subject = 'Ваша оплата получена';
    protected $manager_message_subject = 'Получена оплата заказа';
    
    
    public function initialize(){
        
        $this->setProperty('new_status', 8);
        
        if(
            $this->modx->context->key != 'mgr'
            AND empty($this->modx->smarty)
        ){
            $this->modx->invokeEvent('OnHandleRequest');
        }
        
        return parent::initialize();
    }
    
    
    
    public function afterSave(){
        
        // Отправляем уведомления
        $this->sendNotification();
        
        return parent::afterSave();
    }
    
    
    protected function sendNotification(){ 
        // Набиваем данные в шаблонизатор 
        $this->modx->smarty->assign('order', $this->object->toArray());
        $this->modx->smarty->assign('properties', $this->getProperties());
        
        // Получаем детали заказ
        if(
            $response = $this->modx->runProcessor('shopmodx/orders/products/getdata',
                array(
                    "order_id"  => $this->object->get('id'),
                ), array(
                    'processors_path' => MODX_CORE_PATH .'components/shopmodx/processors/',
                )
            )
            AND !$response->isError()
            AND $data = $response->getResponse()
        ){
            # print_r($data);
            $this->modx->smarty->assign('order_data', $data);
            unset($data);
        }
        
        
        // Получаем профиль заказчика
        if(
            $contractor = $this->object->getOne('Contractor')
            AND $profile = $contractor->getOne('Profile')
        ){
            $this->modx->smarty->assign('Contractor', $contractor->toArray());
            $this->modx->smarty->assign('ContractorProfile', $profile->toArray());
            
        }
        
        // Отправляем письмо контрагенту
        $this->sendContractorEmail();
        
        // Отправляем письма менеджерам
        $this->sendManagersEmail();
        
        return;
    }
    
    // Отправляем письмо контрагенту
    protected function sendContractorEmail(){
        if(
            $message = $this->getMessage($this->contractor_message_tpl)
            AND $contractor = $this->object->getOne('Contractor')
        ){
            $contractor->sendEmail($message, array(
                'subject'   => $this->contractor_message_subject,
            ));
        }
        return;
    }
    
    // Отправляем письма менеджерам
    protected function sendManagersEmail(){
        if($message = $this->getMessage($this->manager_message_tpl)){
            /*
             * Получаем менеджеров, кому надо отправить уведомления, 
             * и пользователя, подавшего заявку
             */
            $q = $this->modx->newQuery('modUser');
            $q->innerJoin('modUserProfile', 'Profile');
            $q->innerJoin('modUserGroupMember', 'UserGroupMembers');
            $q->where(array(
                'Profile.email:!=' => '',
                'UserGroupMembers.user_group'   => $this->modx->getOption('shop.managers_notify_group', null, 1),
            )); 
            
            if($users = $this->modx->getCollection('modUser', $q)){
                foreach($users as $user){
                    $user->sendEmail($message, array(
                        'subject'   => $this->manager_message_subject,
                    ));
                }    
            }
        }
        return;
    }
    
    
    // Получаем текст письма для уведомления контрагента
    protected function getMessage($tpl){
        return $this->modx->smarty->fetch($tpl);
    }
     
    
}

return 'modShopmodxBillingMgrOrdersStatusPayProcessor';