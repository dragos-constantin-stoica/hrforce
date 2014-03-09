// vim: sw=4:ts=4:nu:nospell:fdc=4
/**
 * Ext.ux.tree.RemoteTreePanel Extension Example Application
 *
 * @author    Ing. Jozef Sakáloš
 * @copyright (c) 2008, by Ing. Jozef Sakáloš
 * @date      5. April 2008
 * @version   $Id: remotetree.js 134 2009-02-25 10:32:15Z jozo $
 *
 * @license remotetree.js is licensed under the terms of the Open Source
 * LGPL 3.0 license. Commercial use is permitted to the extent that the 
 * code/component(s) do NOT become part of another Open Source or Commercially
 * licensed development library or toolkit without explicit permission.
 * 
 * License details: http://www.gnu.org/licenses/lgpl.html
 */
 
/*global Ext, Example, WebPage */
 
Ext.ns('Example');
Ext.BLANK_IMAGE_URL = '../../ext/resources/images/default/s.gif';
Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
Example.version = '1.0';
 
Example.tree = new Ext.ux.tree.RemoteTreePanel({
	 id:'remotetree'
	,autoScroll:true
	,rootVisible:false
	,root:{
		 nodeType:'async'
		,id:'root'
		,text:'Root'
		,expanded:true
		,uiProvider:false
	}
	,loader: {
		 url:'process-request.php'
		,preloadChildren:true
		,baseParams:{
			 cmd:'getTree'
			,treeTable:'tree'
			,treeID:1
		}
	}	
});
Example.tree.filter = new Ext.ux.tree.TreeFilterX(Example.tree);

// application main entry point
Ext.onReady(function() {
    Ext.QuickTips.init();

	var win = new Ext.Window({
		 id:'gswin'
        ,title:Ext.get('page-title').dom.innerHTML
		,iconCls:'icon-expand'
		,width:300
		,height:400
		,x:400
		,y:220
		,plain:true
		,layout:'fit'
		,closable:false
		,border:false
		,maximizable:true
		,items:Example.tree
		,tools:[{
			 id:'refresh'
			,handler:function() {
				win.items.item(0).actions.reloadTree.execute();
			}
		}]
		,plugins:[new Ext.ux.menu.IconMenu()]
	});

	win.show();
 
}); // eo function onReady
 
// eof
