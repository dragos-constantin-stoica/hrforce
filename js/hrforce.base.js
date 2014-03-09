/*!
 * HRForce 
 * Base-JS functionality
 */
// set Ext defaults
Ext.BLANK_IMAGE_URL = 'ext/resources/images/default/s.gif';
Ext.chart.Chart.CHART_URL = 'ext/resources/charts.swf';

// create JSLoader
Ext.ux.JSLoader = function(options) {
  
statusBar = Ext.getCmp('hrforce-statusbar');
	statusBar.showBusy();
	  
	Ext.ux.JSLoader.scripts[++Ext.ux.JSLoader.index] = {
		url: options.url,
		success: true,
		options: options,
		onLoad: options.onLoad || Ext.emptyFn,
		onError: options.onError || Ext.ux.JSLoader.stdError
		};

	Ext.Ajax.request({
		url: options.url,
		scriptIndex: Ext.ux.JSLoader.index,
		success: function(response, options) {
		  var script = 'Ext.ux.JSLoader.scripts[' + options.scriptIndex + ']';
		  window.setTimeout('try { ' + response.responseText + ' } catch(e) { '+script+'.success = false; '+script+'.onError('+script+'.options, e); }; if ('+script+'.success) '+script+'.onLoad('+script+'.options);', 0);
		  
		   statusBar.clearStatus({useDefaults:true});
		},
		failure: function(response, options) {
		  var script = Ext.ux.JSLoader.scripts[options.scriptIndex];
		  script.success = false;
		  script.onError(script.options, response.status);
		  
		   statusBar.clearStatus({useDefaults:true});
		}
	});
  
};

Ext.ux.JSLoader.index = 0;
Ext.ux.JSLoader.scripts = [];

Ext.ux.JSLoader.stdError = function(options, e) {
  window.alert('Error loading script:\n\n' + options.url + '\n\n(status: ' + e + ')');
};

// create namespace
Ext.namespace('hrforce');

hrforce.about = {
	fire: function() {
		Ext.Msg.show({
			title:'About',
			msg: '<b>HR Force</b> is based on:\n\
										<ul>\n\
											<li><a href="http://www.extjs.com">Ext JS Library 3.1.0</a></li>\n\
											<li><a href="http://www.pinvoke.com">Fugue Icons 2.5.3</a></li>\n\
											<li><a href="http://www.php.net">PHP</a></li>\n\
											<li><a href="http://www.mysql.com">mySQL</a></li>\n\
											<li><a href="http://httpd.apache.org">Apache Web Server</a></li>\n\
										</ul>\n\
									   <br/><hr/><br/>\n\
										<b><u>Contributors</u></b>:\n\
										<ul>\n\
											<li>Marius Toma</li>\n\
											<li>C&#259;t&#259;lin Fr&#259;&#355;il&#259;</li>\n\
											<li>Drago&#351; Stoica</li>\n\
											<li>Paul Vlasin</li>\n\
											<li>Cristian Colceriu</li>\n\
										</ul>',
			buttons: Ext.Msg.OK,
			width: 200
		});
	}
};

// create the menubar
hrforce.menubar = {
		region: 'north',
		tbar:
		[{
			text: 'Default User menu',
			icon: './images/fugue-icons/users.png',			
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

hrforce.statusbar = new Ext.Panel({
		region: 'south',
		margins: '5 0 0 0',
		border: false,
        bbar: new Ext.ux.StatusBar({
            id: 'hrforce-statusbar',
			border: false,           
            defaultText: 'Ready',
            items: [                
                'HRForce 0.1'
            ]
        })
    });
	
hrforce.widgettools = [{
        id:'close',
        handler: function(e, target, panel){
            //closecontrol
			panel.ownerCt.remove(panel, true);
        }
    }];
	
// create main tabs
hrforce.maintabs = new Ext.TabPanel({
		region: 'center',
		deferredRender: false,
		activeTab: 0, // first tab initially active
		enableTabScroll:true		
	});

// create application
hrforce.app = function() {
    // do NOT access DOM from here; elements don't exist yet
 
    // private variables
 
    // private functions
 
    // public space
    return {
		
        // public methods
        init: function() {		
		
			// init quicktips
			Ext.QuickTips.init();
			var qtip = Ext.QuickTips.getQuickTip();
			qtip.interceptTitles = true;
			
			// set general Border Layout for the page
			var viewport = new Ext.Viewport({
				layout: 'border',
				items: [ 
				hrforce.menubar
				, {
					region: 'east',					
					title: 'East',
					collapsible: true,
					split: true,
					width: 225,
					minSize: 175,
					maxSize: 400,
					margins: '0 5 0 0',					
					layout: 'fit',
					
					xtype:'portal',
					items:[{
						columnWidth:.30,
						style:'padding: 10px'						
					}]					
				}, {
					region: 'west',                
					title: 'West',
					split: true,
					width: 225,
					minSize: 175,
					maxSize: 400,
					collapsible: true,
					margins: '0 0 0 5',
					layout: 'fit',
					
					xtype:'portal',
					items:[{
						columnWidth:.30,
						style:'padding: 10px'						
					}]
				},
				hrforce.maintabs,
				hrforce.statusbar
				]
			});
			
        }
    };
}(); // end of app

Ext.onReady(hrforce.app.init, hrforce.app);

// preloader
Ext.onReady(function() {
	setTimeout(function(){
		Ext.get('loading').remove();
		Ext.get('loading-mask').fadeOut({remove:true});
	}, 250);
});