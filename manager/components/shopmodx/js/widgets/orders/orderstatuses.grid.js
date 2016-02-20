/*
    Управление статусами заказов
*/ 
Shopmodx.grid.OrderStatusesGrid = function(config){
    config = config || {};
    
    
    Ext.applyIf(config, {
        title: 'Управление статусами'
        ,header: true
        ,url: Shopmodx.config.connectors_url + 'orderstatus.php'
        ,paging: true
        ,remoteSort: true
        ,autosave: true
        ,pageSize:  10
        ,save_callback: this.save_callback
        ,fields: [
            'id'
            ,'menu'
            ,'status'
            ,'color'
            ,'comment'
        ]
        ,cls: 'orders-grid'
    });
    
    
    Shopmodx.grid.OrderStatusesGrid.superclass.constructor.call(this, config);
};

Ext.extend(Shopmodx.grid.OrderStatusesGrid, MODx.grid.Grid,{
    
    
    // Прогоняем полученные данные по записям
    
    
    save_callback: function(response){
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
    
    ,_loadColumnModel: function(){
        this.cm = new Ext.grid.ColumnModel({
            grid: this
            ,defaults: {
                width: 120,
                sortable: true
            },
            columns: [
                {
                    header: 'ID'
                    , dataIndex: 'id'
                    , width: 50
                    ,hidden: true
                },{
                    header: 'Статус'
                    ,dataIndex: 'status'
                },{
                    header: 'Комментарий'
                    ,dataIndex: 'comment'
                    ,editable: true
                },{
                    header: 'Цвет'
                    ,dataIndex: 'color'
                    ,renderer: function(value, cell, record){
                        if(value !== ''){
                            cell.style += "background:"+ value +";";
                        }
                        return value;
                    }
                    ,editable: true
                }
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
        //console.log(column);

        
        
        var fieldName = this.getDataIndex(colIndex);
        console.log(fieldName);
        
        switch(fieldName){
            case 'color':
                
                var w;
                var palette = new Ext.ColorPalette({
                    value: record.get('color').replace('#', '')
                    ,listeners: {
                        select: {
                            fn: function(pallete, color){
                                // console.log(pallete);
                                // console.log(color);
                                record.set('color', '#'+color);
                                w.close();
                                // console.log(this);
                                this.grid.saveRecord({
                                    record: record
                                });
                            }
                            ,scope: this
                        }
                    }
                });
                w = new Ext.Window({
                    items: [
                        palette
                    ]
                    ,title: 'Выбор цвета'
                });
                
                w.show();
                
                return;
        }
        
        if(!o){
            o = MODx.load({
                xtype: xtype
            });
        } 
        
        return new Ext.grid.GridEditor(o);
    }
    
    ,ClearColor: function(){
        // var recordData = this.menu.record;
        
        // console.log(this.menu.record);
        // // console.log();
        // window.ww = this;
        // console.log(this);
        
        var record = this.getSelectionModel().getSelected();
        
        if(record){
            record.set('color', '');
            this.saveRecord({
                record: record
            });
        }
        // // console.log(recordData);
        // // MODx.msg.confirm({
        // //     text: 'Удалить заявку?'
        // //     ,url: Shopmodx.config.connector_url + 'orders.php'
        // //     ,params:{
        // //         action: 'remove'
        // //         ,order_id: recordData.id
        // //     }
        // //     ,listeners:{
        // //         success: {
        // //             'fn': function(response, form){
        // //                 console.log(this);
        // //                 this.refresh();
        // //             }
        // //             ,scope: this
        // //         }
        // //     }
        // // });
        // record.set('color', '#'+color);
        // this.grid.saveRecord({
        //     record: record
        // });
    }
});
Ext.reg('shopmodx-grid-orderstatusesgrid',Shopmodx.grid.OrderStatusesGrid);

