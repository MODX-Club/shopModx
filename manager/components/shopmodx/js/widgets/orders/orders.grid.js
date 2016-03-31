/*
    Управление заказами
*/

// Add the additional 'advanced' VTypes
Ext.apply(Ext.form.VTypes, {
    daterange : function(val, field) {
        var date = field.parseDate(val);

        if(!date){
            return false;
        }
        if (field.startDateField) {
            var start = Ext.getCmp(field.startDateField);
            if (!start.maxValue || (date.getTime() != start.maxValue.getTime())) {
                start.setMaxValue(date);
                start.validate();
            }
        }
        else if (field.endDateField) {
            var end = Ext.getCmp(field.endDateField);
            if (!end.minValue || (date.getTime() != end.minValue.getTime())) {
                end.setMinValue(date);
                end.validate();
            }
        }
        /*
         * Always return true since we're only using this vtype to set the
         * min/max allowed values (these are tested for after the vtype test)
         */
        return true;
    }
});



Shopmodx.grid.OrdersGrid = function(config){
    config = config || {};
    
    this._loadExpander();

    
    this._tbar = {
        types: {}
    };

    this._tbar.statuses = new Shopmodx.combo.OrderStatus({
    // this._tbar.statuses = new MODx.combo.Context({
        text: 'Статус'
        ,fieldLabel: 'Статус'
        ,hidden_name: 'status'
        ,show_empty_text: true
        ,value: 2
        ,listeners:{
            select: {
                fn: function(combo){
                    var value = combo.getValue();
                    var store = this.getStore();
                    store.setBaseParam(combo.hidden_name, value);
                    this.getBottomToolbar().changePage(0);
                }
                ,scope: this
            }
        }
    });
    
    this._tbar.clients = new MODx.combo.User({
    // this._tbar.statuses = new MODx.combo.Context({
        text: 'Клиент'
        ,fieldLabel: 'Клиент'
        ,hidden_name: 'contractor'
        ,show_empty_text: true
        // ,displayField: 'fullname'
        // ,fields: ['id','fullname']
        ,url: Shopmodx.config.connectors_url + 'customers.php'
        ,baseParams: {
            action: 'getlist'
            ,show_empty_text: true
        }
        // ,value: 2
        ,enableKeyEvents: true
        ,listeners:{
            select: {
                fn: function(combo){
                    var value = combo.getValue();
                    var store = this.getStore();
                    store.setBaseParam(combo.hidden_name, value);
                    this.getBottomToolbar().changePage(0);
                    
                    // console.log(combo);
                    // console.log(this);
                }
                ,scope: this
            }
            ,keydown: {
                fn: function(field, e){
                    
                    if((e.getCharCode() == 13) && !field.isExpanded()){
                        return field.fireEvent('select', field, e);
                    }
                }
                ,scope: this
            }
        }
    });
    
    this._tbar.search = new Ext.form.TextField({
        fieldLabel: 'Поиск'
        ,name: 'search'
        ,enableKeyEvents: true
        ,listeners:{
            change: {
                fn: function(field){
                    var value = field.getValue();
                    var store = this.getStore();
                    store.setBaseParam(field.name, value);
                    this.getBottomToolbar().changePage(0);
                    
                    // console.log(field);
                    // console.log(this);
                }
                ,scope: this
            }
            ,keydown: {
                fn: function(field, e){
                    
                    if((e.getCharCode() == 13)){
                        return field.fireEvent('change', field, e);
                    }
                }
                ,scope: this
            }
        }
    });
    
    this._tbar.dr = new Ext.FormPanel({
        labelWidth: 125,
        frame: true,
        title: 'Даты заказов',
        bodyStyle:'padding:5px 5px 0',
        // width: 350,
        defaults: {
            width: 175
            ,maxValue: new Date()
            ,format: 'Y-m-d'
            ,enableKeyEvents: true
            ,listeners:{
                select: {
                    fn: function(field){
                        var value = field.getValue();
                        var store = this.getStore();
                        store.setBaseParam(field.hidden_name, value);
                        this.getBottomToolbar().changePage(0);
                        
                        console.log(field);
                        console.log(this);
                    }
                    ,scope: this
                }
                ,keydown: {
                    fn: function(field, e){
                        
                        if((e.getCharCode() == 13)){
                            return field.fireEvent('select', field, e);
                        }
                    }
                    ,scope: this
                }
            }
        },
        defaultType: 'datefield',
        items: [{
            fieldLabel: 'Start Date',
            name: 'startdt',
            hidden_name: 'date_from',
            id: 'startdt',
            vtype: 'daterange',
            endDateField: 'enddt' // id of the end date field
        },{
            fieldLabel: 'End Date',
            name: 'enddt',
            hidden_name: 'date_till',
            id: 'enddt',
            vtype: 'daterange',
            startDateField: 'startdt' // id of the start date field
        }]
    });
    
    Ext.applyIf(config, {
        title: 'Управление заказами'
        ,header: true
        ,url: Shopmodx.config.connectors_url + 'orders.php'
        ,paging: true
        ,remoteSort: true
        ,autosave: true
        ,pageSize:  10
        ,save_callback: this.save_callback
        ,baseParams:{ 
            action: config.action || 'getList'
            ,status: config.status || this._tbar.statuses.getValue()
        }
        ,fields: [
            'id'
            ,'order_id'
            ,'menu'
            ,'status_color'
            ,'order_num'
            ,'status_id'
            ,'status_str'
            ,'createdon'
            ,'editedon'
            ,'contractor'
            ,'contractor_fullname'
            ,'contractor_email'
            ,'contractor_phone'
            ,'contractor_address'
            ,'manager_fullname'
            ,'manager'
            ,'ip'
            ,'address'
            ,'comments'
            ,'sum'
            ,'original_sum'
            ,'paysystem_name'
            ,'pay_id'
            ,'paysys_invoice_id'
            ,'pay_date'
            ,'pay_sum' 
            ,'discount' 
        ]
        ,tbar: {
            layout:'column',
            xtype: 'panel',
            defaults:{
                bodyCfg: {
                    cls: ''  // Default class not applied if Custom element specified
                }
            }
            
            ,items: [{
                defaults:{
                    style: "padding: 0 0 5px;"
                }
                ,items: [
                    {
                        xtype: 'panel'
                        ,layout: 'auto'
                        ,defaults:{
                            style: "margin: 2px 5px 2px 2px;"
                            
                        }
                        ,items:[
                            {
                                xtype: 'panel'
                                ,layout: 'form'
                                ,defaults:{
                                    // style: {
                                    //     margin: "5px;"
                                    // }
                                    width: 200
                                    
                                }
                                ,items: [
                                    this._tbar.statuses
                                    ,this._tbar.clients
                                    ,this._tbar.search
                                ]
                            }
                        ]
                    }
                ]
                },{
                    defaults:{
                        xtype: 'panel'
                        ,layout: 'table'
                        // ,style:'padding: 5px'
                        // ,defaults:{
                        //     // style: "padding: 0 5px;"
                        // }
                    }
                    ,items: [
                        this._tbar.dr
                    ]
                }]
        }
        ,cls: 'orders-grid'
        ,plugins: this.expander
    });
    
    Shopmodx.grid.OrdersGrid.superclass.constructor.call(this, config);
    
    
    this.store.on('load', function(){
            // console.log(this);
            // console.log('sdfds');
            var expander = this.expander;
            
            // console.log(expander.state);
            
            if(expander.state){
                
                // for(var i in expander.state){
                    // if(this.expander.state){
                        // this.expander.expandRow();
                        
                        this.store.data.items.forEach(function(row, i){
                            // console.log(row);
                            // console.log(i);
                            if(expander.state[row.get('id')]){
                                expander.expandRow(i);
                            }
                        });
                        
                    // }
                // }
                
            }
        
    }, this);
    
    
};

Ext.extend(Shopmodx.grid.OrdersGrid, MODx.grid.Grid,{
    
    
    // Прогоняем полученные данные по записям
    
    
    saveRecord: function(e) {
        // e.record.data.menu = null;
        var p = this.config.saveParams || {};
        Ext.apply(e.record.data,p);
        var d = Ext.util.JSON.encode(e.record.data);
        var url = this.config.saveUrl || (this.config.url || this.config.connector);
        MODx.Ajax.request({
            url: url
            ,params: {
                action: this.config.save_action || 'updateFromGrid'
                ,data: d
            }
            ,listeners: {
                success: {
                    fn: function(r) {
                        if (this.config.save_callback) {
                            Ext.callback(this.config.save_callback,this.config.scope || this,[r]);
                        }
                        e.record.commit();
                        if (!this.config.preventSaveRefresh) {
                            this.refresh();
                        }
                        this.fireEvent('afterAutoSave',r);
                    }
                    ,scope: this
                }
                ,failure: {
                    fn: function(r) {
                        e.record.reject();
                        this.fireEvent('afterAutoSave', r);
                    }
                    ,scope: this
                }
            }
        });
    }
    
    ,save_callback: function(response){
        var object = response.object;
        var record  = this.getStore().getById(object.id);
        if(record){
            for(var i in object){
                record.set(i, object[i]);
            }
            record.commit();
        }
        return;
    } 
    
    ,save_callback_depricated: function(response){
        for(var x in response.object){
            var object = response.object[x];
            var record  = this.getStore().getById(object.id);
            if(record){
                for(var i in object){
                    record.set(i, object[i]);
                }
                record.commit();
            }
        }
        return;
    } 
    
    ,_loadExpander: function(){
        this.expander = new Ext.ux.grid.RowExpander({
            tpl : new Ext.Template(
                '<div id="order-grid-{id}"></div>'
            )
            ,listeners:{
                render: function(){
                    console.log(this);
                },
                expand: function(exp, record, body, rowIndex){
                    var id = record.get('id');
                    
                    new Shopmodx.grid.OrderDataGrid({
                        renderTo: Ext.get('order-grid-' + id)
                        ,order_id: id
                        ,mainGrid: this
                    });
                    
                },
                scope: this
            }
            ,sortable:  false
            ,expandOnDblClick: false
        });
        this.expander.OrdersGrids = {};
        return;
    }
    
    ,_loadColumnModel: function(){
        this.cm = new Ext.grid.ColumnModel({
            grid: this
            ,defaults: {
                width: 120,
                sortable: true
            },
            columns: [
                this.expander
                ,{header: 'ID', dataIndex: 'id', width: 50}
                ,{header: 'Дата создания', dataIndex: 'createdon'}
                ,{header: 'Дата изменения', dataIndex: 'editedon', hidden: true}
                ,{
                    header: 'Статус'
                    ,dataIndex: 'status_id'
                    ,renderer: function(value, cell, record, rowIndex){
                        var status_color = record.get('status_color');
                        var status_str = record.get('status_str');
                        if(status_color != ''){
                            // status_str = '<div style="background:'+ status_color +';">'+status_str+'</div>';
                            cell.style += "background:"+ status_color +";";
                            // console.log(this.view.getRow(rowIndex));
                            // console.log(rowIndex);
                            // console.log(cell);
                            // window.ww=this;
                        }
                        return status_str;
                    }
                    ,editable: true
                    ,scope: this
                }
                ,{
                    header: 'Скидка %'
                    ,dataIndex: 'discount'
                    ,renderer: function(value, cell, record){
                        
                        if(value > 0){
                            cell.style += "background:#FF9900;";
                        }
                        else{
                            value = '';
                        }

                        return value;
                    }
                    ,scope: this
                    ,editable: true
                    ,width: 80
                }
                ,{
                    header: 'Сумма'
                    ,dataIndex: 'sum'
                    ,renderer: function(value, cell, record){
                        
                        var original_sum = record.get('original_sum');
                        if(original_sum != value){
                            value = value+ ' (<s>'+ original_sum +'</s>)';
                            cell.style += "background:#FF9900;";
                        }
                        
                        if(value){
                            value = value + ' руб.';
                        }
                        // Иначе просто возвращаем пусто
                        else{
                            return value;
                        }
                        
                        return this.hiderRenderer(value, cell, record);
                    }
                    ,scope: this
                    ,editable: false
                    ,width: 160
                }
                ,{
                    header: 'Оплата'
                    ,dataIndex: 'pay_id'
                    ,renderer: function(value, cell, record){
                        /*
                        ,'paysystem_name'
                        ,'pay_id'
                        ,'paysys_invoice_id'
                        ,'pay_date'
                        ,'pay_sum'
                        */
                        
                        /*
                            Если имеется ID оплаты, то подставляем ЧП-данные
                        */
                        
                        if(value){
                            value = record.get('pay_sum') + ' руб. ('+ record.get('paysystem_name') +')';
                            value = '<span style="color:green;">'+value+'</span>';
                        }
                        // Иначе просто возвращаем пусто
                        else{
                            return value;
                        }
                        
                        return this.hiderRenderer(value, cell, record);
                    }
                    ,scope: this
                    ,editable: false
                }
                ,{
                    header: 'Дата оплаты'
                    ,dataIndex: 'pay_date'
                    ,renderer: this.hiderRenderer
                    ,editable: false
                    ,width: 180
                    ,hidden: true
                }                
                ,{
                    header: 'Номер счета'
                    ,dataIndex: 'paysys_invoice_id'
                    ,renderer: this.hiderRenderer
                    ,editable: false
                    ,hidden: true
                }                
                ,{
                    header: 'ФИО'
                    ,dataIndex: 'contractor_fullname'
                    ,renderer: this.hiderRenderer
                    ,editable: true
                }                
                ,{
                    header: 'Телефон'
                    ,dataIndex: 'contractor_phone'
                    ,renderer: this.hiderRenderer
                    ,editable: true
                }
                ,{
                    header: 'Емейл'
                    ,dataIndex: 'contractor_email'
                    ,renderer: this.hiderRenderer
                    ,editable: true
                }
                ,{
                    header: 'Адрес'
                    ,dataIndex: 'address'
                    ,renderer: this.hiderRenderer
                    ,editable: true
                }
                ,{
                    header: 'Комментарии'
                    ,dataIndex: 'comments'
                    ,renderer: this.hiderRenderer
                    ,editable: true
                }
                ,{
                    header: 'Менеджер'
                    ,dataIndex: 'manager_fullname'
                }
                /*,{
                    header: 'Внутренний номер'
                    ,dataIndex: 'order_num'
                    ,hidden: true
                }*/
            ]
            ,getCellEditor: this.getCellEditor
        });
        return;
    }
    
    ,getCellEditor: function(colIndex, rowIndex) {
        var xtype = 'textfield';
        var record = this.grid.store.getAt(rowIndex);
        var column = this.getColumnAt(colIndex);
        var o;
        
        if(this.grid.__stopEditing){
            
            this.grid.__stopEditing = false;
            return;
        }
        
        // console.log(this);
        
        // return;
        /*
            Это важно проверять, так как иначе при редактировании развернутого грида
            фокус переходит на внешний грид и редактируются другие ячейки
        */
        // if(this.grid.expander.state[record.get('id')]){
        //     
        //     // console.log(this);
        //     return;
        // }
        // console.log(this);
        
        if(!Shopmodx.hasPermission('edit_all_orders') && MODx.user.id != record.get('manager')){
            return;
        }
        
        
        var fieldName = this.getDataIndex(colIndex);
        //console.log(fieldName);
        
        switch(fieldName){
            // Проверяем право редактирования данных клиента
            case 'contractor_email':
            case 'contractor_phone':
            case 'contractor_fullname':
                if(!Shopmodx.hasPermission('edit_clients_data')){
                    return;
                }
                break;
            case 'status_id':
                // Если заявка новая, не позволяем менять статус
                if(record.get('status_id') == 1){
                    return;
                }
                
                o = MODx.load({
                    xtype: 'shopmodx-combo-orderstatus'
                    ,value: record.get('status_id')
                });
                
                console.log(o);
                
                break;
            case 'address':
            case 'comments':
                this.grid.showEditWindow(column, record);
                return;
        }
        
        if(!o){
            o = MODx.load({
                xtype: xtype
            });
        } 
        
        return new Ext.grid.GridEditor(o);
    }
    
    ,showEditWindow: function(column, record){
        new MODx.Window({
            title: column.header
            ,width: 540
            ,url: this.url
            ,action: 'update'
            ,fields:[
                {
                    xtype: 'hidden'
                    ,name: 'order_id'
                    ,value: record.get('order_id')
                }
                ,{
                    xtype: 'htmleditor'
                    ,hiddenName: column.dataIndex
                    ,name: column.dataIndex
                    ,value: record.get(column.dataIndex)
                }
            ]
            ,scope: this
            ,success: function(){
                this.refresh();
            }
        })
            .show();
    }
    
    ,hiderRenderer: function(value, cell, record){
        if(!value){
            value = 'Не указано';
        }
        else if(!Shopmodx.hasPermission('view_all_orders') && MODx.user.id != record.get('manager')){
            value = '********';
        }
        return value;
    }
    
    // Принимаем заказ
    ,takeOrder: function(a,b){
        var recordData = this.menu.record;
        MODx.msg.confirm({
            text: 'Принять заявку?'
            ,url: Shopmodx.config.connectors_url + 'orders.php?action=update/takeorder'
            ,params : {
                'order_id': recordData.id
            }
            ,listeners:{
                success: {'fn': this.onUpdateSuccess, scope: this}
            }
        });
    }
    
    
    // Печать заказа
    ,printOrder: function(a,b){
        var recordData = this.menu.record;
        
        window.open( MODx.config.manager_url + '?a=controllers/mgr/orders/print/&namespace=basket&order_id=' + recordData.id, 'order_print', "menubar=yes,location=no,resizable=yes,scrollbars=yes,status=yes");
    }
    
    // Меняем статус заказа
    ,updateOrderStatus: function(a,b){
        var recordData = this.menu.record;
        var win = new MODx.Window({
            url: Shopmodx.config.connectors_url + 'orders.php'
            ,action: 'update/updatestatus'
            
            ,bodyStyle: 'padding: 20px 0 0 10px;'
            ,width: 300
            ,fields:[{
                xtype: 'shopmodx-combo-orderstatus'
                ,value: recordData.status_id
                ,fieldLabel: 'Статус'
                ,hiddenName: 'new_status'
            },{
                xtype: 'hidden',
                name: 'order_id',
                value: recordData.id
            }]
            ,listeners:{
                success: {'fn': function(response, form){
                    if(!this.onUpdateSuccess(response.a.result)){
                        return;
                    }
                    win.close();
                }, scope: this}
            }
        });
        win.show();
        return;
    }
    
    // Удаление заявок
    ,deleteOrder: function(a,b){
        var recordData = this.menu.record;
        console.log(recordData);
        MODx.msg.confirm({
            text: 'Удалить заявку?'
            ,url: Shopmodx.config.connectors_url + 'orders.php'
            ,params:{
                action: 'remove'
                ,order_id: recordData.id
            }
            ,listeners:{
                success: {
                    'fn': function(response, form){
                        console.log(this);
                        this.refresh();
                    }
                    ,scope: this
                }
            }
        });
        return;
    }
    
    ,onUpdateSuccess: function(response){
        if(!response.success){
            MODx.msg.alert(response.message || 'Ошибка выполнения запроса');
            return false;
        }
        
        this.refresh();
        return;
    }
    
    ,FilterByContractor: function(a,b){
        
        var recordData = this.menu.record;
        
        this._tbar.clients
            .setValue(recordData.contractor)
            .fireEvent('select', this._tbar.clients)
                ;
        
        return;
    }
    
    ,ShowContractorInfo: function(a,b){
        
        var recordData = this.menu.record;
        
        // this._tbar.clients
        //     .setValue(recordData.contractor)
        //     .fireEvent('select', this._tbar.clients)
        //         ;
        
        var w = new MODx.Window({
            title: recordData.contractor_fullname || recordData.contractor_email
            ,buttons: false
            ,width: 500
            ,modal: true
            ,items: [{
                layout: 'form'
                ,defaults:{
                    xtype: 'displayfield'
                    // ,width: 250
                    ,editable: false
                }
                ,items: [{
                    fieldLabel: 'ФИО'
                    ,value: recordData.contractor_fullname
                },{
                    fieldLabel: 'Емейл'
                    ,value: recordData.contractor_email
                },{
                    fieldLabel: 'Телефон'
                    ,value: recordData.contractor_phone
                },{
                    fieldLabel: 'Адрес'
                    ,value: recordData.contractor_address
                }]
            }]
        });
        
        w.show();
        
        return;
    }
    
    ,addProduct: function(menu, e){
        
        // console.log(a);
        // console.log(b);
        // console.log(c);
        var sm = this.getSelectionModel();
        
        var recordData = this.menu.record;
        
        // var record = this.getStore().getAt(rowIndex);
        var record = sm.getSelected();
        console.log(this);
        console.log(record);
        
        var expander = this.expander;
        
        window.ww = this;
        // ww.expander.expandRow(2)
        
        
        
        console.log(record);
        
        var ProductsGrid = new Shopmodx.grid.ProductsGrid();
        
        var w = new Ext.Window({
            title: "Добавление товара"
            ,maximized: true
            ,items: [
                ProductsGrid
            ]
        });
        
        w.on('hide', function(){this.close();}, w);
        
        ProductsGrid.on('rowdblclick', function(grid, rowIndex, e){
        
            console.log(this);
            
            var product_record = grid.getStore().getAt(rowIndex);
            
            if(!product_record){
                return;
            }
            
                // 
                // console.log(product_record);
            if(product_record.get('object_type') == 'product'){
                // console.log(this);
                // 
                // console.log(record);
                
                MODx.Ajax.request({
                    url: this.store.url
                    ,params: {
                        action: 'products/add'
                        ,order_id: record.get('id')
                        ,resource_id: product_record.get('id')
                    }
                  /*,error: function(){
                    console.log('error');
                  }*/
                    ,listeners: {
                        success: {
                            fn: function(resp){
                                // console.log(resp);
                                // console.log('success');
                                w.close();
                                // 
                                // if(!expander.state[record.get('id')]){
                                //     expander.expandRow(sm.last);
                                // }
                                
                                // if(expander.state[record.get('id')]){
                                //     expander.collapseRow(sm.last);
                                // }
                                
                                this.store.reload();
                            }
                            ,scope: this
                        }
                        ,failure: {
                            fn: function(resp){
                                // console.log(resp);
                                // console.log('failure');
                                MODx.msg.alert(resp.message || 'Ошибка выполнения запроса');
                            }
                            ,scope: this
                        }
                    }
                    });
                
                
                
            }
        }, this);
        
        w.show();
    }
});
Ext.reg('shopmodx-grid-ordersgrid',Shopmodx.grid.OrdersGrid);


/*
Shopmodx.grid.OrderDataGrid
Данные заказа (товары)
*/

Shopmodx.grid.OrderDataGrid = function(config){
    config = config || {}; 
    
    Ext.applyIf(config, {
        url: Shopmodx.config.connectors_url + 'orderdata.php'
        ,border: false
        ,paging: true
        ,remoteSort: false
        ,autosave: true
        ,fields: [
            'id'     // ID документа товара
            ,'object_id'     // ID документа товара
            ,'pagetitle'
            ,'quantity'
            ,'price'
            ,'order_price'
            ,"order_currency"
            ,"order_currency_code"
            ,'discount'
            ,'uri'
            ,'model_url'
            ,'image'
            ,'imageDefault'
            ,'menu'
        ]
        ,baseParams:{ 
            action: config.action || 'getList'
            ,order_id: config.order_id || null
        }
    });
    
    Shopmodx.grid.OrderDataGrid.superclass.constructor.call(this, config);
    
    this.on('afterAutoSave', function(){
        
        // console.log('afterAutoSave');
        console.log(this);
        this.mainGrid.store.reload();
    }, this);
};

Ext.extend(Shopmodx.grid.OrderDataGrid, MODx.grid.Grid,{
     
    
    
    _loadColumnModel: function(){
        // console.log(this);
        // console.log(this.store);
        this.cm = new Ext.grid.ColumnModel({
            grid: this
            ,
            defaults: {
                width: 120,
                sortable: false
            },
            columns: [
                {header: 'ID товара', dataIndex: 'object_id', width: 50}
                ,{header: 'Изображение', dataIndex: 'image', renderer: function(value, column, record){
                    /*console.log(column);
                    console.log(record);
                    console.log(value);*/
                    if(!value){
                        value = record.get('imageDefault');
                    }
                    return '<img style="width:50px" src="' +value+'"/>';
                }}
                ,{header: 'Товар', dataIndex: 'pagetitle', renderer: function(value, column, record){
                    return '<a href="'+MODx.config.site_url+record.get('uri')+'" target="_blank">'+value+'</a>';
                    // return '<a href="'+record.get('model_url')+'" target="_blank">'+value+'</a>';
                }}
                //,{header: 'Исполнение', dataIndex: 'design'}
                ,{
                    header: 'Количество', 
                    dataIndex: 'quantity'
                    ,renderer: function(value, column, record){
                        // console.log(value);
                        if(value == '0'){
                            value = '<span style="background:red;color:#fff;padding:2px;">Отменен</span>';
                            // column.style += "background:red;color:#fff";
                        }
                        return value;
                    }
                    ,editable: true
                }
                ,{
                    header: 'Цена'
                    ,dataIndex: 'price'
                    ,editable: true
                }
                ,{
                    header: 'Сумма'
                    ,dataIndex: 'sum'
                    ,renderer: function(value, column, record){
                        return record.get('price') * record.get('quantity');
                    }
                }
                ,{
                    header: 'Валюта'
                    ,dataIndex: 'order_currency'
                    ,renderer: function(value, column, record){
                        return record.get('order_currency_code');
                    }
                }
            ]
            ,getCellEditor: this.getCellEditor
        });
        
        // alert(this.getCellEditor);
        // console.log(this);
        return;
    }
    
    ,getCellEditor: function(colIndex, rowIndex) {
        // return;
        
        var xtype = 'textfield';
        var record = this.grid.store.getAt(rowIndex);
        var column = this.getColumnAt(colIndex);
        var o;
        
        this.grid.mainGrid.__stopEditing = true;
        
        // console.log(this);
        // return;
        
        var fieldName = this.getDataIndex(colIndex);
        
        if(!o){
            o = MODx.load({
                xtype: xtype
            });
        } 
        
        return new Ext.grid.GridEditor(o);
    }
    
        // Удаление заявок
    ,deleteProduct: function(a,b){
        var recordData = this.menu.record;
        // console.log(recordData);
        
        var record = this.getSelectionModel().getSelected();
        
        var grid = this;
        
        if(record){
            // record.set('color', '');
            // this.saveRecord({
            //     record: record
            // });
            // Ext.Msg.confirm('Подтверждение', 'Удалить товар из заказа?', function(status){
            //     console.log(status);
            //     if(status == 'yes'){
            //         record.set('quantity', 0);
            //         grid.saveRecord({
            //             record: record
            //         });
            //     }
            // });
            
                    // // console.log(recordData);
            MODx.msg.confirm({
                text: 'Удалить товар из заказа?'
                ,url: Shopmodx.config.connectors_url + 'orderdata.php'
                ,params:{
                    action: 'remove'
                    ,id: recordData.id
                }
                ,listeners:{
                    success: {
                        'fn': function(response, form){
                            console.log(this);
                            // this.refresh();
                            
                            this.mainGrid.store.reload();
                        }
                        ,scope: this
                    }
                }
            });
        }
        
        return;
    }
});
Ext.reg('shopmodx-grid-orderdatagrid',Shopmodx.grid.OrderDataGrid);



/*
    Таблица товаров
*/

Shopmodx.grid.ProductsGrid = function(config){
    config = config || {}; 
    
    Ext.applyIf(config, {
        baseParams:{
            action: 'getList'
            ,listType: config.listType || 'products'
            ,parent: config.parent || 0
            ,context_key: config.context_key || 'web'
            ,sort: 'id'
        }
        ,tbar: false
        ,fields: [
            'id'
            ,'type'
            ,'menu'
            ,'pagetitle'
            ,'longtitle'
            ,'description'
            ,'menuindex'
            ,'parent'
            ,'class_key'
            ,'context_key'
            ,'isfolder'
            ,'alias'
            ,'uri'
            ,'published'
            ,'deleted'
            ,'hidemenu'
            
            // Тип объекта
            ,'object_type'
            
            // Parent
            ,'uplevel_id'
            ,'parent_title'
            ,{
                name: 'sm_price'
                /*,type: 'float'
                ,allowBlank: true
                ,defaultValue: null*/
            }
            ,'sm_currency'
            ,'currency_title'
            ,{
                name: 'sm_trade_price'
            }
            
        ]
    });
    
    Shopmodx.grid.ProductsGrid.superclass.constructor.call(this, config);
    
    // this.on('rowdblclick', function(grid, rowIndex, e){
    //     
    //     var record = this.getStore().getAt(rowIndex);
    //     
    //     if(!record){
    //         return;
    //     }
    //     
    //     if(record.get('object_type') == 'product'){
    //         console.log(this);
    //         
    //         console.log(record);
    //         
    //     }
    //     
    //     
    //     
    //     
    // }, this);
    
    // console.log(this);
    // console.log(this.store);
    // this.store.setBaseParam('sort', 'id');
    
};

Ext.extend(Shopmodx.grid.ProductsGrid, Shopmodx.grid.OrdersGrid,{
    
    getColumnModel: function(){
        
        return new Ext.grid.ColumnModel({
            grid: this
            ,defaults:{
                sortable: true
            }
            ,columns: [
                {
                    header: "ID"
                    ,dataIndex: 'id'
                    ,width: 40
                },{
                    header: 'Тип'
                    ,width: 50
                    ,dataIndex: 'object_type'
                    ,renderer: function(value){
                        switch(value){
                            case 'document':
                                value = 'Документ';
                                break;
                            case 'model':
                                value = 'Модель товара';
                                break;
                            case 'product':
                                value = 'Товар';
                                break;
                            default:;
                        }
                        return value;
                    }
                    ,sortable: false
                    ,hidden: true
                },{
                    header: 'Название'
                    ,dataIndex: 'pagetitle'
                    ,scope: this
                    ,renderer: function(value, column, record){
                        
                        var output;
                        var classes = '';
                        
                        if(record.get('deleted') == '1'){
                            classes += ' deleted';
                        }
                        
                        if(record.get('published')  == '0'){
                            classes += ' unpublished';
                        }
                        
                        if(record.get('hidemenu')){
                            classes += ' hidemenu';
                        }
                        
                        var text = '<span>'+ value +'</span>';
                        
                        if(record.get('isfolder')){
                            output = '<a href="javascript:;" onclick="Ext.getCmp(\'' + this.id+ '\').loadNodes('+ record.get('id') +', '+ record.get('parent') +');">'+text+'</a>';
                        }
                        else output = text;
                        
                        return '<div class="'+ classes +'">'+ output +'</div>';
                    }
                    ,editable: false
                }
                ,{
                    header: 'Опубликованный'
                    ,dataIndex: 'published'
                    ,editable: true
                    ,renderer: function(value, column, record){
                        if(value == 'true' || value == '1'){
                            value = '<span style="color:green;">Да</span>';
                        }
                        else{
                            value = '<span style="color:red;">Нет</span>';
                        }
                        return value;
                    }
                    ,hidden: true
                }
                ,{
                    header: 'Скрытый'
                    ,dataIndex: 'hidemenu'
                    ,editable: true
                    ,renderer: function(value, column, record){
                        if(value == 'true' || value == '1'){
                            value = '<span style="color:red;">Да</span>';
                        }
                        else{
                            value = '<span style="color:green;">Нет</span>';
                        }
                        return value;
                    }
                    ,hidden: true
                }
                ,{
                    header: 'Расширенный заголовок'
                    ,dataIndex: 'longtitle'
                    ,editable: true
                    ,hidden: true
                }
                ,{
                    header: 'Описание'
                    ,dataIndex: 'description'
                    ,editable: true
                    ,hidden: true
                }
                ,{
                    header: 'Псевдоним'
                    ,dataIndex: 'alias'
                    ,editable: true
                    ,hidden: true
                }
                ,{
                    header: 'Цена'
                    ,dataIndex: 'sm_price'
                    ,renderer: function(value, cell, record){
                        return value;
                    }
                    ,editable: true
                    ,xtype: 'numbercolumn'
                }
                ,{
                    header: 'Валюта'
                    ,dataIndex: 'sm_currency'
                    ,editable: true
                    ,renderer: function(value, cell, record){
                        /*
                            Надо изменить механизм получения названия валют
                            на выборку из готового массива всех валют.
                        */
                        var currency;
                        if(currency = record.get('currency_title')){
                            value = currency;
                        }
                        return value;
                    }
                    // ,editor: {
                    //     xtype: 'shopmodx-combo-currencies'
                    // }
                }
                /*,{
                    header: 'Оптовая цена'
                    ,dataIndex: 'sm_trade_price'
                    ,renderer: function(value, cell, record){
                        return value;
                    }
                    ,editable: true
                    ,xtype: 'numbercolumn'
                }*/
            ]
            ,getCellEditor: this.getCellEditor
        });
    }
    
    ,getCellEditor: function(colIndex, rowIndex) {
        var record = this.grid.store.getAt(rowIndex);
        
        return new Ext.grid.GridEditor(o);
    }
    
});
Ext.reg('shopmodx-grid-productsgrid',Shopmodx.grid.ProductsGrid);



