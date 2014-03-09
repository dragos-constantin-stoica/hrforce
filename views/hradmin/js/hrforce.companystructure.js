Ext.onReady(function() {	

	var structuredgrid = new Ext.tree.TreePanel({			
			border: false,			
			useArrows: true,
			autoScroll: true,
			animate: true,
			enableDD: true,
			containerScroll: true,
			rootVisible: false,
			frame: false,
			root: {
				nodeType: 'async'
			},	
			// auto create TreeLoader
			dataUrl: 'hrforcedummy.php',
			listeners: {
				'dblclick': function(node){
					var myeditor = new Ext.form.TextField({
						allowBlank: false
					});
										
					var node_elem = Ext.get(node.getUI().getTextEl());					
					
					var current_text = node_elem.dom.innerHTML;
					var edit_control = '<input type="text"  class=" x-form-text x-form-field x-form-num-field" value="'+current_text+'" />';
					node_elem.dom.innerHTML = edit_control;
				}
			},			
			tbar: [{
				text: 'Add new branch',
				handler: function(){
					var msg = '', selNodes = structuredgrid.getChecked();
					Ext.each(selNodes, function(node){
						if(msg.length > 0){
							msg += ', ';
						}
						msg += node.text;
					});
					Ext.Msg.show({
						title: 'Completed Tasks', 
						msg: msg.length > 0 ? msg : 'None',
						icon: Ext.Msg.INFO,
						minWidth: 200,
						buttons: Ext.Msg.OK
					});
				}
			}]
		});

    structuredgrid.getRootNode().expand(true);
	
	/*
	var structuredgrid = new Ext.ux.tree.TreeGrid({
        layout: 'fit',		
		border: false,		
		enableDD: true,	
        columns:[{
            header: 'Position',				
			dataIndex: 'text',
			editor: new Ext.form.TextField({
				allowBlank: true
			})
        }, {
		    header: 'Assigned to',
			width: '100%',
			dataIndex: 'user'
		}],
        dataUrl: 'hrforcedummy.php'
    });
	*/
	
    hrforce.companystructure.tab = hrforce.maintabs.add({
		title: 'Manage Company Structure',
		layout: 'fit',
		closable:true,
		items: structuredgrid,
		listeners: {
			close: function(){hrforce.companystructure.tab=false;}
			}
		}).show();
	
});