 'use strict';
(function($){
window.setrioBizcalCustomCSSinit = function(config){
	$(function(){
		if( $('#setrio-bizcal-custom-css').length ) {
			var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
			editorSettings.codemirror = _.extend(
				{},
				editorSettings.codemirror,
				{
					indentUnit: 4,
					tabSize: 4,
					mode: 'css',
				}
			);
			var editor = wp.codeEditor.initialize( $('#setrio-bizcal-custom-css'), editorSettings );
		}
	});
}
window.setrioBizcalCustomCSS2init = function(config){
	$(function(){
		if( $('.textarea-codemirror-json').length ) 
		{
			var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
			editorSettings.codemirror = _.extend(
				{},
				editorSettings.codemirror,
				{
					indentUnit: 4,
					tabSize: 4,
					mode: 'javascript',
					readOnly: true
				}
			);
			$('.textarea-codemirror-json').each(function(){
				var editor = wp.codeEditor.initialize( $(this), editorSettings );
			})
		}
	});
}
})(jQuery);