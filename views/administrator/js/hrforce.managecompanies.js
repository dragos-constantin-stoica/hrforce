Ext.onReady(function() {
	// Create a standard HttpProxy instance.
	var proxy = new Ext.data.HttpProxy({
		url: 'include/app.php/companies'
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
		name: 'COMPANYNAME',

		allowBlank: false
	}
	]);

	// The new DataWriter component.
	var writer = new Ext.data.JsonWriter({
		encode: true   // <-- don't return encoded JSON -- causes Ext.Ajax#request to send data using jsonData config rather than HTTP params
	});

	// Typical Store collecting the Proxy, Reader and Writer together.
	var store = new Ext.data.Store({
		id: 'company',
		restful: true,     // <-- This Store is RESTful
		proxy: proxy,
		reader: reader,
		writer: writer    // <-- plug a DataWriter into the store just as you would a Reader
	});

	// load the store immeditately
	store.load();

	// Let's pretend we rendered our grid-columns with meta-data from our ORM framework.
	var userColumns =  [
	{
		header: "ID",			
		sortable: true,
		dataIndex: 'ID',
		readOnly: true,
		width: 100
	},

	{

		header: "Company Name",

		sortable: true,			

		dataIndex: 'COMPANYNAME',

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
			COMPANYNAME: 'Default Company'
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
	
    hrforce.managecompanies.tab = hrforce.maintabs.add({
		title: 'Manage Companies',
		layout: 'fit',
		closable:true,
		items: userGrid,
		listeners: {
			close: function(){hrforce.managecompanies.tab=false;}
			}
		}).show();
	
});