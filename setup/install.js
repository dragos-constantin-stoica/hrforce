/*!
 * Ext JS Library 3.1.0
 * Copyright(c) 2006-2009 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
Ext.BLANK_IMAGE_URL = '../ext/resources/images/default/s.gif';

Ext.onReady(function(){

Ext.QuickTips.init();

var bd = Ext.getBody();

    bd.createChild({tag: 'h2', html: 'System setup'});

    var DB_form = new Ext.FormPanel({
        bodyStyle:'padding:5px 5px 0',
        labelWidth:90,
        url:'install.php',
        frame:true,
        title:'HR Force Setup',
        defaultType:'textfield',
        width: 350,
        monitorValid:true,
        // Specific attributes for the text fields for username / password.
        // The "name" attribute defines the name of variables sent to the server.
        items:[{
            xtype:'fieldset',
            title: 'Database Setup',
            collapsible: false,
            autoHeight:true,
            width: 320,
            defaultType: 'textfield',

            items:[{
                fieldLabel:'Server host',
                name:'hostName',
                allowBlank:false
            },{
                fieldLabel:'User name',
                name:'userName',
                allowBlank:false
            },{
                fieldLabel:'Password',
                name:'userPassword',
                allowBlank:false
            },{
                fieldLabel:'Database',
                name:'databaseName',
                allowBlank:false
            }]
        },{
            xtype:'fieldset',
            title: 'Administrator Setup',
            collapsible: false,
            autoHeight:true,
                width: 320,
            defaultType: 'textfield',

            items:[{
                fieldLabel:'Admin name',
                name:'adminName',
                allowBlank:false
            },{
                fieldLabel:'Password',
                name:'adminPassword',
                allowBlank:false
            }]
        }
        ],

        // All the magic happens after the user clicks the button
        buttons:[{
            text:'Install',
            formBind: true,
            // Function that fires when user clicks the button
            handler:function(){
                DB_form.getForm().submit({
                    method:'POST',
                    waitTitle:'Connecting',
                    waitMsg:'Sending data...',

                    // Functions that fire (success or failure) when the server responds.
                    // The one that executes is determined by the
                    // response that comes from login.asp as seen below. The server would
                    // actually respond with valid JSON,
                    // something like: response.write "{ success: true}" or
                    // response.write "{ success: false, errors: { reason: 'Login failed. Try again.' }}"
                    // depending on the logic contained within your server script.
                    // If a success occurs, the user is notified with an alert messagebox,
                    // and when they click "OK", they are redirected to whatever page
                    // you define as redirect.

                    success:function(){
                        Ext.Msg.alert('Status','Setup Succesful!');
                        window.location = '../';
                    },

                    // Failure function, see comment above re: success and failure.
                    // You can see here, if login fails, it throws a messagebox
                    // at the user telling him / her as much.

                    failure:function(form, action){
                        if(action.failureType == 'server'){
                            obj = Ext.util.JSON.decode(action.response.responseText);
                            Ext.Msg.alert('Setup Failed!', obj.errors.reason);
                        }else{
                            Ext.Msg.alert('Warning!', 'Server is unreachable : ' + action.response.responseText);
                        }
                        DB_form.getForm().reset();
                    }
                });
            }
        }]
    });

   DB_form.render(document.body);
});