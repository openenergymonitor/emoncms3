CKEDITOR.dialog.add( 'e3dial', function( editor ) {

	return {
		title : 'Insert dial dialog',
		minWidth : 400,
		minHeight : 200,
		onOk : function() {
			feed = this.getContentElement('tab1','feed').getValue();
			max = this.getContentElement('tab1','max').getValue();
			scale = this.getContentElement('tab1','scale').getValue();
			units = this.getContentElement('tab1','units').getValue();
			ckeditor_widget_id ++;
			editor.insertHtml("<div class='dial' id='"+ckeditor_widget_id+"' feed='"+feed+"' max='"+max+"' scale='"+scale+"' units='"+units+"' style='width:180px; height:180px;'></div>");    
		},
		contents : [
			{
			id : 'tab1',
			label : '',
			title : '',
			elements :[
				{  id : 'feed', type : 'text', label : 'Feed', labelLayout: 'horizontal' },
				{  id : 'max', type : 'text', label : 'Max', labelLayout: 'horizontal' },
				{  id : 'scale', type : 'text', label : 'Scale', labelLayout: 'horizontal' },
				{  id : 'units', type : 'text', label : 'Units', labelLayout: 'horizontal' }
			]
			}
		]
	};
} );
