Ext.onReady(function() {
	// Create a standard HttpProxy instance.
	var proxy = new Ext.data.HttpProxy({
		url: 'include/app.php/hradmins'
	});

	// Typical JsonReader.  Notice additional meta-data params for defining the core attributes of your json-response
	var reader = new Ext.data.JsonReader({
		totalProperty: 'total',
		successProperty: 'success',
		idProperty: 'ID',
		root: 'data',
		messageProperty: 'message'  // <-- New "messageProperty" meta-data
	}, [
	{
		name: 'ID'
	},

	{
		name: 'COMPANY_ID',
		allowBlank: false
	},

	{
		name: 'USERNAME',
		allowBlank: false
	},

	{
		name: 'PASSWORD',
		allowBlank: false
	}
	]);

	// The new DataWriter component.
	var writer = new Ext.data.JsonWriter({
		encode: true   // <-- don't return encoded JSON -- causes Ext.Ajax#request to send data using jsonData config rather than HTTP params
	});

	// Typical Store collecting the Proxy, Reader and Writer together.
	var store = new Ext.data.Store({
		id: 'hradmin',
		restful: true,     // <-- This Store is RESTful
		proxy: proxy,
		reader: reader,
		writer: writer    // <-- plug a DataWriter into the store just as you would a Reader
	});

	// load the store immeditately
	store.load();


      	// Create a standard HttpProxy instance.
	var proxy_cbo = new Ext.data.HttpProxy({
		url: 'include/app.php/companies',
                method:'GET'
	});

	// Typical JsonReader.  Notice additional meta-data params for defining the core attributes of your json-response
	var reader_cbo = new Ext.data.JsonReader({
		totalProperty: 'total',
		successProperty: 'success',
		idProperty: 'ID',
		root: 'data',
		messageProperty: 'message'  // <-- New "messageProperty" meta-data
	}, [
	{
		name: 'ID'
	},
	{
		name: 'COMPANYNAME',
		allowBlank: false
	}
	]);


var companylist = new Ext.data.Store({
  proxy: proxy_cbo,
  reader:reader_cbo
});


//      var companylist = new Ext.data.Store({
//      proxy: new Ext.data.HttpProxy({url: 'hrforcedummycombo.php',method:'GET'}),
//      reader: new Ext.data.JsonReader({
//		  root: 'companies',
//		  fields: [ {name: 'company_id'},{name: 'company_name'}]
//		  })
//	});
    
	companylist.load();
	
	// Let's pretend we rendered our grid-columns with meta-data from our ORM framework.
	var userColumns = [
	{
		header: "ID",
		sortable: true,
		dataIndex: 'ID',
		readOnly: true,
		width: 100
	},

        {
		header: "Company",
		sortable: true,
		dataIndex: 'COMPANY_ID',
		editor: new Ext.form.ComboBox({                        
                        fieldLabel: 'Company',
                        hiddenName: 'hr_company',
                        store: companylist,
                        valueField: 'ID',
                        displayField: 'COMPANYNAME',
                        triggerAction: 'all',
                        emptyText:'Select company',
                        selectOnFocus: true,
			allowBlank: false,
                        editable: false                    
                    })

	},

	{
		header: "HR Admin Name",
		sortable: true,			
		dataIndex: 'USERNAME',
		editor: new Ext.form.TextField({})
	},

	{
		header: "Password",
		sortable: true,
		dataIndex: 'PASSWORD',
		editor: new Ext.form.TextField({})
	}
	];

	// use RowEditor for editing
	var editor = new Ext.ux.grid.RowEditor({
		saveText: 'Update'
	});

	// Create a typical GridPanel with RowEditor plugin
	var userGrid = new Ext.grid.GridPanel({
		region: 'center',			
		layout: 'fit',
		height: '300',
		border: false,
		autoScroll: true,       
		store: store,
		plugins: [editor],
		columns: userColumns,
		tbar: [{
			text: 'Add',
			icon: './images/fugue-icons/_overlay/user--plus.png',
			handler: onAdd
		}, '-', {
			text: 'Delete',
			icon: './images/fugue-icons/_overlay/user--minus.png',
			handler: onDelete
		}, '-'],
		bbar: new Ext.PagingToolbar(),
		viewConfig: {				
			forceFit: true
		}
	});		

	function onAdd(btn, ev) {
		var u = new userGrid.store.recordType({
			ID : '',
                        COMPANY_ID :'',
			USERNAME : '',
			PASSWORD : ''
		});
		editor.stopEditing();
		userGrid.store.insert(0, u);
		editor.startEditing(0);
	};

	function onDelete() {
		var rec = userGrid.getSelectionModel().getSelected();
		if (!rec) {
			return false;
		}
		userGrid.store.remove(rec);
	};
	
    hrforce.managehr.tab = hrforce.maintabs.add({
		title: 'Manage HR Admins',
		layout: 'fit',
		closable:true,
		items: userGrid,
		listeners: {
			close: function(){hrforce.managehr.tab=false;}
			}
		}).show();
	
});