/*
 Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function(config) {
	
	config.toolbar = 'Emoncms3_Toolbar';

	config.toolbar_Emoncms3_Toolbar = [
	{ name: 'document', items : [ 'Source','-','NewPage','e3Save','DocProps','e3Preview','Print','-','Templates' ] },
	{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
	{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
	{ name: 'forms', items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 
        'HiddenField' ] },
	'/',
	{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
	{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
	'-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
	{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
	{ name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
	'/',
	{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
	{ name: 'colors', items : [ 'TextColor','BGColor' ] },
	{ name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] },'/',
	//{ name: 'e3ui', items : [ 'e3menu' ] },
	{ name: 'e3widgets', items : [ 'e3wc','e3dial','e3graph' ] }
	];
		
	config.extraPlugins = 'stylesheetparser,e3Save,e3Preview,e3wc,e3dial,e3graph';
	config.fillEmptyBlocks = false;
	
	config.contentsCss = path+'Views/theme/common/visualdesign_style.css';
	config.stylesSet = [];
				
	config.width = '100%';
	config.height = '420px';
	config.filebrowserBrowseUrl = path + 'Includes/editors/simogeo-Filemanager/index.html'; 
	config.templates_files = [ path+'Includes/editors/templates/e3templates.js' ];
	
	// Save button
	CKEDITOR.plugins.add('e3Save', {
		init : function(editor) {
			var pluginName = 'e3Save';
			
			editor.addCommand(pluginName, {
				// Save button pressed
				exec : function(editor) {
					// Fire an event
					CKEDITOR.fire('savePressed', editor);
				},
				canUndo : true
			});

			editor.ui.addButton('e3Save', {
				label : 'Save',
				command : pluginName,
				className : 'cke_button_save'
			});
		}
	});
	
	// Preview button
	CKEDITOR.plugins.add('e3Preview', {
		init : function(editor) {
			var pluginName = 'e3Preview';
			
			editor.addCommand(pluginName, {
				// Save button pressed
				exec : function(editor) {
					// Fire an event
					CKEDITOR.fire('previewPressed', editor);
				},
				canUndo : true
			});

			editor.ui.addButton('e3Preview', {
				label : 'Preview',
				command : pluginName,
				className : 'cke_button_preview'
			});
		}
	});
	
	// insert widget container
	CKEDITOR.plugins.add('e3wc', {
		init : function(editor) {
			var pluginName = 'e3wc';
			
			editor.addCommand(pluginName, {
				// insert dial button pressed
				exec : function(editor) {
					editor.insertHtml("<div class='widget-container-v'></div>");
				},
				canUndo : true
			});

			editor.ui.addButton('e3wc', {
				label : 'Insert widget container',
				command : pluginName,
				icon: path+'Includes/editors/images/e3wc.png'
			});
		}
	});	
	
	// insert dial
	CKEDITOR.plugins.add('e3dial', {
		init : function(editor) {
			var pluginName = 'e3dial';
			
			// dial dialog
			CKEDITOR.dialog.add(pluginName, path + 'Includes/editors/dialogs/e3dial.js');
				
			editor.addCommand(pluginName, new CKEDITOR.dialogCommand(pluginName ) );

			editor.ui.addButton('e3dial', {
				label : 'Insert dial',
				command : pluginName,
				icon: path+'Includes/editors/images/e3dial.png'
			});
		}
	});	

	// insert graph
	CKEDITOR.plugins.add('e3graph', {
		init : function(editor) {
			var pluginName = 'e3graph';
			
			// dial dialog
			CKEDITOR.dialog.add(pluginName, path + 'Includes/editors/dialogs/e3graph.js');
				
			editor.addCommand(pluginName, new CKEDITOR.dialogCommand(pluginName ) );

			editor.ui.addButton('e3graph', {
				label : 'Insert graph',
				command : pluginName,
				icon: path+'Includes/editors/images/e3graph.png'
			});
		}
	});	
	
			// insert graph
	CKEDITOR.plugins.add('e3menu', {
		init : function(editor) {
			var pluginName = 'e3menu';
			
			// dial dialog
			CKEDITOR.dialog.add(pluginName, path + 'Includes/editors/dialogs/e3menu.js');
				
			editor.addCommand(pluginName, new CKEDITOR.dialogCommand(pluginName ) );

			editor.ui.addButton('e3menu', {
				label : 'Insert dashboards menu',
				command : pluginName,
				icon: path+'Includes/editors/images/e3menu.png'
			});
		}
	});	
	
};
