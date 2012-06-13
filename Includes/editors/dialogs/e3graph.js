CKEDITOR.dialog.add( 'e3graph', function( editor ) {

	return {
		title : 'Insert graph dialog',
		minWidth : 400,
		minHeight : 200,
		onOk : function() {
			feed = this.getContentElement('tab1','feed').getValue();
			idd = this.getContentElement('tab1','idd').getValue();
			
			editor.insertHtml("<div class='graph' feed='"+feed+"' id='"+idd+"'></div>");    
		},
		contents : [
			{
			id : 'tab1',
			label : '',
			title : '',
			elements :[
				{  id : 'feed', type : 'text', label : 'Feed', labelLayout: 'horizontal' },
				{  id : 'idd', type : 'text', label : 'id', labelLayout: 'horizontal' }
			]
			}
		]
	};
} );
