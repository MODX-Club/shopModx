/*
    Грид группового редактора
*/
Shopmodx.grid.GroupEdit = function(config){
    config = config || {};
    
    this._tbar = {
        types: {}
    };
    
    
    Ext.applyIf(config, {
        url: Shopmodx.config.connectors_url + 'groupedit.php'
        ,title: 'Список'
        ,listType: 'documents'
        ,paging: true
        ,pageSize: 10
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
        ,remoteSort: true
        ,autosave: true
        ,columns: this.getColumnModel()
        ,context_key: 'web'
    });
    
    
    this._tbar.context_key = new MODx.combo.Context({
        text: 'Контекст'
        ,hidden_name: 'context_key'
        ,value: config.context_key
        ,listeners:{
            select: {
                fn: function(combo){
                    var context_key = combo.getValue();
                    var store = this.getStore();
                    store.setBaseParam('context_key', context_key);
                    this.getBottomToolbar().changePage(0);
                }
                ,scope: this
            }
        }
    });
    
    this._tbar.products_only = new Ext.form.Checkbox({
        label: 'Только товары'
        ,hidden_name: 'products_only'
        ,value: 1
        // ,listeners:{
        //     select: {
        //         fn: function(combo){
        //             var context_key = combo.getValue();
        //             var store = this.getStore();
        //             store.setBaseParam('context_key', context_key);
        //             this.getBottomToolbar().changePage(0);
        //         }
        //         ,scope: this
        //     }
        // }
    });
    
    this._tbar.types.documents = new Ext.Toolbar.Button({
        text: 'Документы'
        ,value: 'documents'
        ,disabled: config.listType == 'documents' ? true : false
        ,handler: this.changeListType
        ,scope: this
    });
    
    this._tbar.types.products = new Ext.Toolbar.Button({
        text: 'Товары'
        ,value: 'products'
        ,disabled: config.listType == 'products' ? true : false
        ,handler: this.changeListType
        ,scope: this
    });
    
    this._tbar.types.models = new Ext.Toolbar.Button({
        text: 'Модели'
        ,value: 'models'
        ,disabled: config.listType == 'models' ? true : false
        ,handler: this.changeListType
        ,scope: this
    });
    
    this._tbar.upLevelButton = new Ext.Toolbar.Button({
        text: 'На уровень выше'
        ,handler: this.toUpLevel
        ,iconCls: 'group_edit_toplevel'
        ,scope: this
        ,hidden: true
    });
    
    
    this._tbar.createArticle = new Ext.Toolbar.Button({
        text: 'Создать статью'
        ,handler: this.createArticle
        ,hidden: true
        ,scope: this
    });
    
    
    Ext.applyIf(config, {
        tbar:[
            {
                xtype:'label'
                ,text: 'Контекст'
            }
            ,'-'
            ,this._tbar.context_key
            // ,this._tbar.products_only
            ,this._tbar.types.documents
            ,this._tbar.types.products
            //,this._tbar.types.models
            ,this._tbar.upLevelButton
            ,'-'
            ,this._tbar.createArticle
        ]
        ,baseParams:{
            action: 'getList'
            ,listType: config.listType
            ,parent: 0
            ,context_key: config.context_key
        }
    });
     
    
    Shopmodx.grid.GroupEdit.superclass.constructor.call(this, config);
    
    this.getBottomToolbar().on('change', this.onChangeBottomToolbar , this);
    
};

Ext.extend(Shopmodx.grid.GroupEdit, MODx.grid.Grid, {
    
    
    onChangeBottomToolbar: function(PagingToolbar, pageData){
        var record = PagingToolbar.store.getAt(0);
        if(!record){
            return;
        }
        
        /*console.log(this);
        console.log(record);*/
        
        var uplevel_id = record.get('uplevel_id');
        var parent = record.get('parent');
        var parent_title = record.get('parent_title');
        
        // Если родитель - Статьи, то активируем кнопку создания статей
        if(parent == 148){
            this._tbar.createArticle.show();
        }
        else{
            this._tbar.createArticle.hide();
        }
        
        
        // Кнопка Вверх
        if(!uplevel_id){
            this._tbar.upLevelButton.hide();
            return;
        }
        
        
        this.showUpLevelButton(uplevel_id, parent_title);
        
    }
    
    
    ,getColumnModel: function(){
        
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
                    ,editable: true
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
                }
                ,{
                    header: 'Расширенный заголовок'
                    ,dataIndex: 'longtitle'
                    ,editable: true
                }
                ,{
                    header: 'Описание'
                    ,dataIndex: 'description'
                    ,editable: true
                }
                ,{
                    header: 'Псевдоним'
                    ,dataIndex: 'alias'
                    ,editable: true
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
        //console.log(record);
        //console.log(this);
        
        
        
        
        var fieldName = this.getDataIndex(colIndex);
        
        //console.log(fieldName);
        
        switch(fieldName){
            // Редактор цены
            case 'sm_price':
            case 'sm_trade_price':
                return this.grid.getPriceCellEditor(record); 
                
            case 'published':
            case 'hidemenu':
                return this.grid.getBooleanCellEditor(record); 
                
            case 'sm_currency':
                return this.grid.getPriceCurrencyCellEditor(record);
                
                return new Ext.grid.GridEditor(o);
                // break;
        }
        
        var o = MODx.load({
            xtype: 'textfield'
            /*,listeners:{
                change: {
                    fn:function(field){
                        console.log(field);
                    }
                }
            }*/
        });
        
        return new Ext.grid.GridEditor(o);
    }
    
    /*
        Редактор колонки цены.
        Только если объект - товар, тогда только редактируем цену
    */
    ,getPriceCellEditor: function(record){
        var object_type = record.get('object_type');
        //console.log(object_type);
        
        
        if(object_type == 'product'){
            var o = MODx.load({
                xtype: 'numberfield'
                ,align: 'left'
            });
            
            return new Ext.grid.GridEditor(o);
        }
    }
    
    ,getBooleanCellEditor: function(record){
        var object_type = record.get('object_type');
        //console.log(object_type);
        
         var o = MODx.load({
            xtype: 'combo-boolean'
        });
        
        return new Ext.grid.GridEditor(o);
    }
    
    ,getPriceCurrencyCellEditor: function(record){
        var object_type = record.get('object_type');  
        
        if(object_type == 'product'){
            var o = MODx.load({
                xtype: 'shopmodx-combo-currencies'
                ,align: 'left'
                ,value: record.get('sm_currency')
            });
            
            return new Ext.grid.GridEditor(o);
        }
    }
    
    ,changeListType: function(button, e){
        var store = this.getStore();
        var type = button.value;
        
        /*
            Надо будет продумать с базовыми параметрами для различных типов вывода
        */
        //this._tbar[type].baseParams = store.baseParams;
        
        store.setBaseParam('listType', button.value);
        store.setBaseParam('parent', 0);
        
        
        for(var i in this._tbar.types){
            if(this._tbar.types[i].value == button.value){
                this._tbar.types[i].setDisabled(true);
            }
            else{
                this._tbar.types[i].setDisabled(false);
            }
        }
        
        this.getBottomToolbar().changePage(0);
        //console.log(this);
    }
    
    ,loadNodes: function(id, parent){
        this.showUpLevelButton(parent, "Вверх");
        
        this.getStore().setBaseParam('parent', id);
        this.getBottomToolbar().changePage(0);
    }
    
    ,showUpLevelButton: function(parent, title){
        this._tbar.upLevelButton.parent_id = parent;
        this._tbar.upLevelButton.setText(title);
        this._tbar.upLevelButton.show();
    }
    
    ,changeModelPrices: function(item, e){
        var record = this.menu.record;
        console.log(record);
        var win = new MODx.Window({
            width: 800
            ,buttons: [{
                    text: _('cancel')
                    // ,scope: this
                    ,handler: function() { win.close(); }
            }] 
            ,items: new MODx.grid.Grid({
                url: shopModxGroupEdit.config.connectors_url + 'model.php'
                ,height: 400
                ,autoHeight: false
                ,border: false
                ,autosave: true
                ,save_action: 'grouped/prices/update'
                ,fields: ['id', 'pagetitle', 'sm_price', 'sm_trade_price', 'parent' ]
                ,columns: new Ext.grid.ColumnModel({
                    defaults:{
                        sortable: true
                    }
                    ,columns: [
                    {
                        header: 'ID'
                        ,dataIndex: 'id'
                        ,width: 20
                    }
                    ,{
                        header: 'Название'
                        ,dataIndex: 'pagetitle'
                    }
                    ,{
                        header: 'Цена'
                        ,width: 30
                        ,dataIndex: 'sm_price'
                        ,editor: {
                            xtype: 'textfield'
                            /*,listeners:{
                                change: function( field, newValue, oldValue){
                                    //field.gridEditor.record.set('old_price', oldValue);
                                    console.log(this)
                                    console.log(field.gridEditor.record)
                                }
                            }*/
                        }
                    }
                    ,{
                        header: 'Оптовая цена'
                        ,width: 30
                        ,dataIndex: 'sm_trade_price'
                        ,editor: {
                            xtype: 'textfield'
                        }
                    }
                ]})
                ,paging: false
                ,pageSize: 0
                ,baseParams:{
                    action: 'grouped/prices/getlist'
                    ,model_id: record.id
                }
            })
        });
        
        win.show();
    }
    
    ,changeModelImage: function(item, e){
        var record = this.menu.record;
        // console.log(record);
        
        var config = {
            source: MODx.config.default_media_source
        }
        
        this.EditorGrid = new MODx.grid.Grid({
                url: shopModxGroupEdit.config.connectors_url + 'model.php'
                ,height: 500
                ,autoHeight: false
                ,border: false
                // ,autosave: true
                ,save_action: 'grouped/images/update'
                ,fields: [ 'parent', 'pagetitle', 'color', 'design', 'image', 'relativeUrl', 'dirname', 'source_path' , 'fullRelativeUrl']
                ,columns: new Ext.grid.ColumnModel({
                    defaults:{
                        sortable: true
                    }
                    ,columns: [
                        {
                            header: 'Картинка'
                            ,dataIndex: 'relativeUrl' 
                            ,renderer: function(value, cell, record){ 
                                if(!value){
                                    value = 'нет фото';
                                }
                                else{
                                    value = '<img width="70" src="'+ record.get('source_path') + value+'" />';
                                    //value =  record.get('source_path') + value ;
                                }
                                return value;
                            }
                            ,width: 70
                            ,editor: {
                                    xtype: 'modx-combo-browser'
                                    // ,rootId: 'products/'
                                    ,anchor: '100%'
                                    ,rootVisible: true
                                    ,browserEl: 'tvbrowser'+config.tv
                                    ,name: 'tvbrowser'+config.tv
                                    ,id: 'tvbrowser'+config.tv
                                    ,value: config.relativeValue
                                    ,hideFiles: false
                                    ,source: config.source || 1
                                    ,allowedFileTypes: config.allowedFileTypes || ''
                                    ,openTo: config.openTo || ''
                                    ,hideSourceCombo: true
                                    ,listeners: {
                                        beforerender : {
                                            fn: function( Editor, boundEl, value ){
                                                console.log(Editor);
                                                console.log('Editor');
                                                //Editor.config.rootId = this.EditorGrid.getSelectionModel().getSelected().get('dirname') || '/';
                                                Editor.config.rootId = Editor.gridEditor.record.get('dirname') || '/';
                                                console.log(Editor.config.rootId);
                                            }
                                            ,scope: this
                                        }
                                        ,'select': {fn:function(data ) {
                                            this.fireEvent('change', this, data.relativeUrl);
                                        }}
                                        ,'change': {fn:function(combobox, newValue) {
                                            var record = combobox.gridEditor.record;
                                            record.set('relativeUrl',  newValue);
                                            this.EditorGrid.saveRecord({
                                                record: record
                                            });
                                            return;
                                        },scope:this}
                                    }
                                }
                        }
                        ,{
                            header: 'Название'
                            ,dataIndex: 'pagetitle'
                            ,width: 40
                        }
                        ,{
                            header: 'Цвет'
                            ,dataIndex: 'color' 
                            ,width: 20
                        }
                        ,{
                            header: 'Исполнение'
                            ,dataIndex: 'design' 
                            ,width: 30
                        }
                    ]
                })
                ,paging: false
                ,pageSize: 0
                ,baseParams:{
                    action: 'grouped/images/getlist'
                    ,model_id: record.id
                }
            });
        
        var win = new MODx.Window({
            width: 1000
            ,buttons: [{
                    text: _('cancel')
                    // ,scope: this
                    ,handler: function() { win.close(); }
            }] 
            ,items: [this.EditorGrid]
        });
        
        
        win.show();
    }
    
    // Редактируем документ
    ,editResource: function(item, e){
        var record = this.menu.record;
        
        window.open('/manager/?a='+ MODx.action['resource/update'] +'&id='+ record.id, '_blank');
    }
    
    // Открываем документ для просмотра
    ,showResource: function(item, e){
        var record = this.menu.record;
        
        window.open('/'+ record.uri, '_blank');
    }
    
    // Поднимаемся на уровень выше
    ,toUpLevel: function(button, e){
        this.loadNodes(button.parent_id);
    }
    
    // Создание статьи
    ,createArticle: function(a,b,c){
        console.log(a);
        console.log(b);
        console.log(c);
        console.log(this);
        var parent = this.baseParams.parent;
        console.log(parent);
        var context_key = this._tbar.context_key.getValue();
        window.open('/manager/?a='+ MODx.action['resource/create'] + '&context_key='+ context_key +'&template=15&parent='+ parent, '_blank');
    }
    
});
Ext.reg('shopmodx-grid-groupedit', Shopmodx.grid.GroupEdit);
