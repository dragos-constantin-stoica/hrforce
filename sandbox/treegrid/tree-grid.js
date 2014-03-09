/*!
 * Ext JS Library 3.1.0
 * Copyright(c) 2006-2009 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
Ext.onReady(function() {
    Ext.QuickTips.init();

    var tree = new Ext.ux.tree.TreeGrid({
        title: 'Organisation Structure',
        width: 500,
        height: 300,
        renderTo: Ext.getBody(),
        enableDD: true,

        columns:[{
            header: 'Name',
            dataIndex: 'task',
            width: 230
        },{
            header: 'Type',
            width: 100,
            dataIndex: 'duration',
            align: 'center',
            sortType: 'asFloat'            
        },{
            header: 'Assigned To',
            width: 150,
            dataIndex: 'user'
        }],

        dataUrl: 'treegrid-data.json'
    });
});