hrforce.companystructure = {
	tab: false,
	fire: function() {
	
			if(!hrforce.companystructure.tab) {
				new Ext.ux.JSLoader({
					url: 'views/hradmin/js/hrforce.companystructure.js'				
				});
			} else {
				hrforce.companystructure.tab.show();
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
			text: 'HR Administration',
			icon: './images/fugue-icons/users.png',
			menu: {
			  items:[{				  
				text: 'Manage HR Admins',
				icon: './images/fugue-icons/user-business.png',
				handler: hrforce.managehr.fire
			  }]
			},
			scope: this
		},{
			text: 'Manage Company Structure',
			icon: './images/fugue-icons/node.png',
			handler: hrforce.companystructure.fire,			
			scope: this
		},{
			text: 'Manage Projects',
			icon: './images/fugue-icons/block.png',
			handler: hrforce.companystructure.fire,			
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