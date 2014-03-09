Ext.onReady(function() {
	
    hrforce.companystructure = new Ext.tree.TreePanel({
        height: 300,
		useArrows: false,		
        autoScroll: true,
        animate: true,
        enableDD: false,		
        containerScroll: false,
        border: false,        
        dataUrl: 'hrforcedummy.php',
        root: {
            nodeType: 'async',			
            text: 'Structure',            
            id: 'task'
        }

    });

    hrforce.companystructure.getRootNode().expand();
	
	// example piechart
	var store = new Ext.data.JsonStore({
        fields: ['season', 'total'],
        data: [{
            season: 'Summer',
            total: 150
        },{
            season: 'Fall',
            total: 245
        },{
            season: 'Winter',
            total: 117
        },{
            season: 'Spring',
            total: 184
        }]
    });
	
	 hrforce.piechart = new Ext.Panel({
        width: 300,
        height: 200,
        items: [{
            store: store,
            xtype: 'piechart',
            dataField: 'total',
            categoryField: 'season',
            //extra styles get applied to the chart defaults
            extraStyle:
            {
                legend:
                {
                    display: 'bottom',
                    padding: 5,
                    font:
                    {
                        family: 'Tahoma',
                        size: 13
                    }
                }
            }
        }]
    });


	
	hrforce.maintabs.add({
		title: 'Dashboard',
		autoScroll: true,
		xtype:'portal',
		region:'center',
		margins:'35 5 5 0',
		items:[{
			columnWidth:.33,
			style:'padding:10px',
			items:[{
				title: 'Company structure',
				layout:'fit',
				tools: hrforce.widgettools,
				items: hrforce.companystructure
			}]
		},{
			columnWidth:.33,
			style:'padding:10px',
			items: [{
				title: 'Example Piechart',
				layout:'fit',
				tools: hrforce.widgettools,
				items: hrforce.piechart
			}]
		},{
			columnWidth:.33,
			style:'padding:10px'                
		}]
	}).show();
	
});