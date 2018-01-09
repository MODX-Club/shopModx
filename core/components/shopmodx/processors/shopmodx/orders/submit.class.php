<?php

require_once dirname(__FILE__) . '/object.class.php';

class modShopmodxOrdersSubmitProcessor extends modShopmodxOrdersObjectProcessor{
    
    /*
        Если на сайте нет авторизации, то имеет смысл разрешить приобщение заказов
        к существуюим пользователям с поиском по емейлу.
        Риск того, что кто-то этим воспользуется - очень маленький, и это на уровне шалости.
        Но если этого не сделать, то пользователь раз оформивший уже заказ, не 
        сможет оформить новый на этот емейл, так как он будет занят  
    */
    protected $allowGetUserByEmail = true;
    
    /*
        Смарти-Шаблоны для писем
    */
    protected $contractor_message_tpl = "message/order_submitted/contractor.tpl";
    protected $manager_message_tpl = "message/order_submitted/manager.tpl";
    
    /*
        Заголовки писем
    */
    protected $contractor_message_subject = 'Данные вашего заказа';
    protected $manager_message_subject = 'Получен новый заказ';
    
    
    public function initialize() {
        
        if($params = (array)$this->getProperty("params")){
            $this->setProperties($params);
        }

        $this->setDefaultProperties(array(
            "new_object"   => false,        // Флаг, что это новый объект
            "save_object"   => true,       // Флаг, что объект надо сохранять
        ));
        
        $this->setProperties(array(
            "status_id"   => 2, 
        ));
        
        return parent::initialize();
    }
    
    
    
    public function beforeSet(){
        
        // Проверяем является ло заказ текущим заказом пользователя
        if(!in_array($this->object->status_id, array(1,7))){
            return 'Оформить можно только новый или отмененный заказ';
        }
        
        return parent::beforeSet();
    }
    
    
    public function beforeSave(){
        
        // Получаем контрагента
        $this->getContractor();
        
        # print_r($this->object->toArray());
        
        # foreach($this->object as $i => $v){
        #     print "<br />" . $i;
        # }
        
        # print_r($this->object->Contractor->toArray());
        # 
        # return 'Debug';
        
        return parent::beforeSave();
    }
    
    public function afterSave(){
        
        // Отправляем уведомления
        $this->sendNotification();
        
        return parent::afterSave();
    }
    
    
    protected function validateFields(){
        
        $fields = $this->getFields();
        
        foreach($fields as $field => $d){
            if($d['required'] && !$this->getProperty($field)){
                $error = !empty($d['error_message']) ? $d['error_message'] : 'Поле заполненно не корректно';
                $this->addFieldError($field, $error);
                continue;
            }
            switch ($field){
                case 'email':
                    if(!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/', $this->getProperty($field))){
                        $this->addFieldError($field, 'Укажите корректный емейл');
                    }
                    break;
                default: 
            }
        }
        
        return !$this->hasErrors();
    }
    
    
    protected function getFields(){
        $fields = array(
            'fullname' => array(
                'name'      => 'ФИО',
                'required'  => true,
                'error_message' => 'Укажите имя',      
            ),
            'email' => array(
                'name'  => 'Емейл',
                'required' => true,
                'error_message' => 'Укажите емейл',     
            ),
        );
        return $fields;
    }
    
    
    /*
        Получаем контрагента
    */
    protected function getContractor(){
        $contractor = null; 
        
        // Если у заказа нет текущего контрагента, то надо его создать
        if(!$contractor = $this->object->getOne('Contractor')){
            
            // Если пользователь авторизован, то устанавливаем его
            if($this->modx->user->isAuthenticated($this->modx->context->key)){
                $contractor =& $this->modx->user;
            }
            // Иначем создаем нового пользователя.
            else{
                $contractor = $this->createContractor();
            }
            
            // Если контрактор получен, добавляем его к заказу
            if($contractor){
                $this->object->addOne($contractor, 'Contractor');
            }
        }
        
        return $contractor;
    }    
    
    
    /*
        Создаем контрагента
    */
    protected function createContractor(){
        $contractor = null;
        
        

        if($email = mb_strtolower(trim($this->getProperty('email')))){
            $this->setProperty('email', $email);
        }
        
        if($fullname = trim($this->getProperty('fullname'))){
            $this->setProperty('fullname', $fullname);
        }
        
        // Выполнеяем проверку данных
        if($this->validateFields()){
            # return "Проверьте правильность заполнения формы";
            
            $email = $this->getProperty('email');
             
            
            // Выполняем поиск существующего пользователя по емейлу
            $c = $this->modx->newQuery('modUser');
            $c->innerJoin('modUserProfile', 'Profile');
            $c->where(array(
                'Profile.email:LIKE' => $email,
            ));
            
            if($user = $this->modx->getObject('modUser', $c)){
                if($this->allowGetUserByEmail){
                    return $user; 
                }
                else{
                    $this->addFieldError('email', "Данный емейл занят. Укажите другой емейл или авторизуйтесь. ");
                    return $contractor;
                }
            }
            
             
            /*
                Если пользователь не был найден по емейлу, то создаем нового
            */
            
            $this->setProperties(array(
                'password'  =>  mb_substr(md5(uniqid()),0, $this->modx->getOption('password_min_length')),
            ));
            
            // Создаем объект пользователя
            $contractor = $this->modx->newObject('modUser', array(
                'active' => 1,
                'username' => $email,
            ));
            
            // Создаем профиль пользователя
            $profile = $this->modx->newObject('modUserProfile', $this->getProperties());
            $contractor->addOne($profile);
            
            // Добавляем пользователя в соответствующие группы
            if($memberGroups = $this->getContractorGroups()){
                $contractor->addMany($memberGroups); 
            } 
        }
        
        return $contractor;
    }
    
    protected function getContractorGroups(){
        $groups = array();
        
        if($modUserGroup = $this->modx->getObject('modUserGroup', $this->modx->getOption('shopmodx.customers_group', null, 0))){
            $memberGroup = $this->modx->newObject('modUserGroupMember');
            $memberGroup->addOne($modUserGroup);
            $groups[] = $memberGroup;
        }
        
        return $groups;
    }
    
    
    
    /*
        Получаем группы, в которые надо добавить пользователя
    */
    protected function sendNotification(){ 
        // Набиваем данные в шаблонизатор
        $this->modx->smarty->assign('order', $this->object->toArray());
        $this->modx->smarty->assign('properties', $this->getProperties());
        
        // Получаем детали заказ
        if(
            $response = $this->modx->runProcessor('shopmodx/orders/products/getdata',
                array(
                    "order_id"  => $this->object->get('id'),
                    "format"    => "",
                ), array(
                    'processors_path' => dirname(dirname(dirname(__FILE__))).'/',
                )
            )
            AND !$response->isError()
            AND $data = $response->getResponse()
        ){
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
        
        # print_r($profile->toArray());
        # 
        # exit;
        
        // Отправляем письмо контрагенту
        $this->sendContractorEmail();
        
        // Отправляем письма менеджерам
        $this->sendManagersEmail();
        
        return;
    }
    
    // Отправляем письмо контрагенту
    protected function sendContractorEmail(){
        if($message = $this->getMessage($this->contractor_message_tpl)){
            $this->object->getOne('Contractor')->sendEmail($message, array(
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
    
    public function cleanup() {
        
        // Сбрассываем сессию
        unset($_SESSION['order_id']);
        
        return $this->success('Спасибо! Заказ успешно принят.',$this->object->toArray());
    }    
    
    
}

return 'modShopmodxOrdersSubmitProcessor';
