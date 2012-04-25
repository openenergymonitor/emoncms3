/*
 Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function(config) {
	
	config.toolbar = 'Emoncms3_Toolbar';

	config.toolbar_Emoncms3_Toolbar = [
	{ name: 'document', items : [ 'Source','-','NewPage','AjaxSave','DocProps','Emoncms3Preview','Print','-','Templates' ] },
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
	{ name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] }
	];
		
	config.extraPlugins = 'AjaxSave,Emoncms3Preview';
	config.fillEmptyBlocks = false;
	config.contentsCss = path+'Views/theme/dark/style.css';
	
	// Save button
	CKEDITOR.plugins.add('AjaxSave', {
		init : function(editor) {
			var pluginName = 'AjaxSave';
			
			editor.addCommand(pluginName, {
				// Save button pressed
				exec : function(editor) {
					// Fire an event
					CKEDITOR.fire('savePressed', editor);
				},
				canUndo : true
			});

			editor.ui.addButton('AjaxSave', {
				label : 'Save',
				command : pluginName,
				className : 'cke_button_save'
			});
		}
	});
	
	// Preview button
	CKEDITOR.plugins.add('Emoncms3Preview', {
		init : function(editor) {
			var pluginName = 'Emoncms3Preview';
			
			editor.addCommand(pluginName, {
				// Save button pressed
				exec : function(editor) {
					// Fire an event
					CKEDITOR.fire('previewPressed', editor);
				},
				canUndo : true
			});

			editor.ui.addButton('Emoncms3Preview', {
				label : 'Preview',
				command : pluginName,
				className : 'cke_button_preview'
			});
		}
	});
};
