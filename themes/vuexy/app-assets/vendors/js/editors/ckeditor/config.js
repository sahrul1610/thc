	/**
 * @license Copyright (c) 2003-2020, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	config.skin = 'office2013';
	config.enterMode = CKEDITOR.ENTER_BR;
	config.extraPlugins = 'youtube,base64image,codesnippet';
	config.toolbar = [
		['CodeSnippet', 'Youtube', 'base64image', 'ImageButton', 'ExportPdf', 'Print', 'Preview', 'Templates', 'PasteFromWord'],
		['Bold', 'Italic', 'Underline'],
		['NumberedList', 'BulletedList', 'Outdent', 'Indent', 'BlockQuote', 'CreateDiv', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'Html5video','Link', 'Table'],
		['Styles', 'Format', 'Font', 'FontSize'],
		[ 'TextColor', 'BGColor']
	];
	config.codeSnippet_theme= 'monokai_sublime';
	config.allowedContent = true;

	config.pasteFromWordRemoveFontStyles = true;
	config.height = '300px';
};
