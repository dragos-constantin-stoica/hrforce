// manage admins tab
hrforce.manageadmins = {
	tab: false,
	fire: function() {	
			if(!hrforce.manageadmins.tab) {
				new Ext.ux.JSLoader({
					url: 'views/administrator/js/hrforce.manageadmins.js'				
				});
			} else {
				hrforce.manageadmins.tab.show();
			}		
		}
};

hrforce.managecompanies = {
	tab: false,
	fire: function() {
	
			if(!hrforce.managecompanies.tab) {				
				new Ext.ux.JSLoader({
					url: 'views/administrator/js/hrforce.managecompanies.js'				
				});			
			} else {
				hrforce.managecompanies.tab.show();
			}		
		}
};

hrforce.managehr = {
	tab: false,
	fire: function() {
	
			if(!hrforce.managehr.tab) {
				new Ext.ux.JSLoader({
					url: 'views/administrator/js/hrforce.managehr.js'				
				});					
			} else {
				hrforce.managehr.tab.show();
			}		
		}
};

// create the menubar
hrforce.menubar = {
		region: 'north',
		tbar:
		[{
			text: 'Manage People',
			icon: './images/fugue-icons/users.png',
			menu: {
			  items:[{
				text: 'Manage IT Admins',
				icon: './images/fugue-icons/user-worker-boss.png',
				handler: hrforce.manageadmins.fire
			  },{				  
				text: 'Manage HR Admins',
				icon: './images/fugue-icons/user-business.png',
				handler: hrforce.managehr.fire
			  }]
			},
			scope: this
		},{
			text: 'Manage Companies',
			icon: './images/fugue-icons/store.png',
			handler: hrforce.managecompanies.fire,			
			scope: this
		},'-',{
			text: 'Help',
			icon: './images/fugue-icons/lifebuoy.png',
			menu: {
				items:[{
					text: 'About',
					icon: './images/fugue-icons/information-frame.png',
					handler: hrforce.about.fire
				}]
			},
			scope: this
		}, '->', {
			text: 'Logout',
			icon: './images/fugue-icons/door-open-out.png',
			handler: function() {
				Ext.Ajax.request({
					url: 'logout.php',
					success: function(){
						var redirect = 'index.php';
						window.location = redirect;
					},
					failure: function(){
						Ext.Msg.alert('Status', 'Logout encountered a problem! Please contact system administrator', function(btn1, text){
							if (btn1 == 'ok'){
								var redirect = 'login.html';
								window.location = redirect;
							}
						});
					},
					headers: {
						'my-header': 'logout'
					},
					params: {
						user: 'test'
					}
				});
			},
			scope: this
		}],

		autoHeight: true,
		border: false,
		margins: '0 0 5 0'
	};
	
Ext.onReady(function() {
	// load dashboard
	new Ext.ux.JSLoader({
		url: 'views/administrator/js/hrforce.dashboard.js'			
	});
});