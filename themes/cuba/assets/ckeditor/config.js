/**
 * @license Copyright (c) 2003-2020, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	config.skin = 'office2013';
	config.enterMode = CKEDITOR.ENTER_BR;
	config.extraPlugins = 'justify,youtube,html5video';
	config.allowedContent = true;

	config.pasteFromWordRemoveFontStyles = true;
	config.height = '300px';
};
