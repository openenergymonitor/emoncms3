CKEDITOR.dialog.add( 'e3menu', function( editor ) {

	return {
		title : 'Insert dashboard menu dialog',
		minWidth : 400,
		minHeight : 200,
		onOk : function() {
			menuid = this.getContentElement('tab1','menuid').getValue();
							
			editor.insertHtml("<div class='e3menu'><div class='e3header'><ul id='e3top-menu'><li><a href=''>Dashboards menu</a></li></ul></div></div>");    
		},
		contents : [
			{
			id : 'tab1',
			label : '',
			title : '',
			elements :[
				{  id : 'menuid', type : 'text', label : 'Menu Id.', labelLayout: 'horizontal' }				
			]
			}
		]
	};
} );
