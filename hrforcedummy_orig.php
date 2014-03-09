<?php
// JSON PENTRU TREEGRID PE COMPANY STRUCTURE
echo "[{    
	text: 'Organizatie',	
    children:[{        
		text:'Departament', 
        user:'Tommy Maintz',        
        children:[{
            text:'Kitchen supplies',            
            user:'Tommy Maintz',
            leaf:true            
        },{
            text:'Groceries',            
            user:'Tommy Maintz',
            leaf:true
        },{
            text:'Cleaning supplies',            
            user:'Tommy Maintz',
            leaf:true
        },{
            text: 'Office supplies',            
            user: 'Tommy Maintz',
            leaf: true
        }]
	}]
	},
	{    
	text: 'Alta Organizatie',
    children:[{        
		text:'Alt Departament', 
        user:'Tommy Maintz',        
        children:[{
            text:'Kitchen supplies',            
            user:'Tommy Maintz',
            leaf:true            
        },{
            text:'Groceries',            
            user:'Tommy Maintz',
            leaf:true
        },{
            text:'Cleaning supplies',            
            user:'Tommy Maintz',
            leaf:true
        }]
    }]
}]";
?>