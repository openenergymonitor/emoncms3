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
	{ name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] },
	{ name: 'e3widgets', items : [ 'e3dial' ] }
	];
		
	config.extraPlugins = 'e3Save,e3Preview,e3dial';
	config.fillEmptyBlocks = false;
	config.contentsCss = path+'Views/theme/dark/style.css';
	
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
	
	// insert dial
	CKEDITOR.plugins.add('e3dial', {
		init : function(editor) {
			var pluginName = 'e3dial';
			
			editor.addCommand(pluginName, {
				// insert dial button pressed
				exec : function(editor) {
					editor.insertHtml("<div class='dial' feed='power' max='500' scale='1' units='V'></div>");
				},
				canUndo : true
			});

			editor.ui.addButton('e3dial', {
				label : 'Dial',
				command : pluginName,
				className : 'cke_button_preview'
			});
		}
	});	
	
};
