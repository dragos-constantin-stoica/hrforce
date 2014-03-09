/*!
 * HRForce 
 * Login functionality
 */
// set Ext defaults
Ext.BLANK_IMAGE_URL = 'ext/resources/images/default/s.gif';

Ext.namespace('hrforce', 'hrforce.login');

if(window.location.href.indexOf('administrator')!=-1) 
	hrforce.adminlogin=true;
else 
	hrforce.adminlogin=false;


// Create a standard HttpProxy instance.
		var proxy = new Ext.data.HttpProxy({
			url: 'include/app.php/companies',
	                method:'GET'
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


var companylist = new Ext.data.Store({
  proxy: proxy,
  reader: reader
});

companylist.load();
	
hrforce.login.formurl = 'login.php';
hrforce.login.redirecturl = 'hrforce.php';
hrforce.login.formitems = [{
			xtype: 'box',
			autoEl: { 
				tag: 'div',
				html: '<div id="hrforce-login-logo"></div>'
			}
		},{
			fieldLabel: 'Username',
			name: 'loginUsername',
			allowBlank: false,
			plugins: [Ext.ux.FieldLabeler]
		},{
			fieldLabel: 'Password',
			name: 'loginPassword',
			inputType: 'password',
			allowBlank: false,
			plugins: [Ext.ux.FieldLabeler]
		},{
			xtype: 'combo',			
			plugins: [Ext.ux.FieldLabeler],
			name: 'company',
			
			fieldLabel: 'Company',
			hiddenName: 'companyID',
			store: companylist,
			valueField: 'ID',
			displayField: 'COMPANYNAME',
			triggerAction: 'all',
			emptyText:'Select company',
			selectOnFocus: true,
			allowBlank: false,
			editable: false     
		}];

if(hrforce.adminlogin) {
	hrforce.login.formurl = '../login.php';
	hrforce.login.redirecturl = '../hrforce.php';
	hrforce.login.formitems = [{
				xtype: 'box',
				autoEl: { 
					tag: 'div',
					html: '<div id="hrforce-login-logo"></div>'
				}
			},{
				fieldLabel: 'Username',
				name: 'loginUsername',
				allowBlank: false,
				plugins: [Ext.ux.FieldLabeler]
			},{
				fieldLabel: 'Password',
				name: 'loginPassword',
				inputType: 'password',
				allowBlank: false,
				plugins: [Ext.ux.FieldLabeler]
			}];
}

	
hrforce.login.fire = function(){
	hrforce.login.form.getForm().submit({
		method:'POST',
		waitTitle:'Connecting',
		waitMsg:'Sending data...',

		success: function(){			
			window.location = hrforce.login.redirecturl;                                        
		},

		failure:function(form, action){
			if(action.failureType == 'server'){
				obj = Ext.util.JSON.decode(action.response.responseText);
				Ext.Msg.alert('Login Failed!', obj.errors.reason);
			}else{
				Ext.Msg.alert('Warning!', 'Authentication server is unreachable : ' + action.response.responseText);
			}
			hrforce.login.form.getForm().reset();
		}
	});	
};

hrforce.login.form = new Ext.FormPanel({
	labelWidth: 65,
	url: hrforce.login.formurl,
	frame: true,
	title: 'Welcome to HR Force - Please Login',	
	monitorValid: true,
	layout: {
		type: 'vbox',
		align: 'stretch'
	},
	defaults: {
		xtype: 'textfield'
    },
	keys: [{
		key: Ext.EventObject.ENTER,
		fn: hrforce.login.fire
	}],
	items: hrforce.login.formitems,
	buttons:[{
			text: 'Login',
			formBind: true,			
			handler: hrforce.login.fire
		}]
});

hrforce.login.window = new Ext.Window({
	layout:'fit',
	width: 370,
	height: 260,
	closable: false,
	resizable: false,
	plain: true,
	border: false,
	items: hrforce.login.form
});
			
hrforce.base = function() {
    // do NOT access DOM from here; elements don't exist yet
	
    // private variables
 
    // private functions
 
    // public space
    return {
		
        // public methods
        init: function() {		
			
			Ext.QuickTips.init();
			hrforce.login.window.show();
		
        }
    };
}();

Ext.onReady(hrforce.base.init, hrforce.base);			