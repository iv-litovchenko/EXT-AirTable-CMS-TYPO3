'use strict';

(function () {

	CKEDITOR.dtd.$removeEmpty.em = 0;
	CKEDITOR.dtd.$removeEmpty.i = 0;

	CKEDITOR.plugins.add('myplugin', {
		icons: 'myplugin', // Note: Please put icon file at /Resources/Public/CkEditorPlugins/icons/mycustom.png
		init: function (editor) {
			
            editor.ui.addButton('MyPresetA',{
                label: 'My Button A',
                toolbar: 'insert',
                command: 'openExampleModal'
            });

            // Execute CKEditor Command
            editor.addCommand('openExampleModal',{
                exec: openModal
            });
			
            editor.ui.addButton('MyPreset',{
                label: 'My Button',
                toolbar: 'insert',
                command: 'insertTimestamp'
            });

			editor.addCommand( 'insertTimestamp', {
				exec: function (editor) {
					// var icon = editor.document.createElement('i');
					// icon.setAttribute('class', 'fa fa-envelope');
					// editor.insertElement(icon);
                    var now = new Date();
                    editor.insertHtml (
                        'The current date and time is: <em>' + now.toString() + '</em>'
                    )
				}
			});
		}
	});

})();

function openModal() {
    require([
        'TYPO3/CMS/Backend/Modal'
    ], function(Modal) {
        Modal.show(
            'Sample Plugin',
            'Hello TYPO3'
        );
    })
}

/*
externalPlugins:
  myplugin: {
    resource: "EXT:yourextension/Resources/Public/CkEditorPlugins/myplugin.js"
      route: "configsample_route"
  }
*/
function openModalAjax() {
    // Get AJAX URL
    var url = editor.config.myplugin.routeUrl;

    require([
        'TYPO3/CMS/Backend/Modal'
    ], function(Modal) {
        // Display modalbox
        Modal.cdvanced({
            type: Modal.types.iframe,
            title: 'Sample Plugin',
            content: url,
            size: Modal.sizes.large
        })
    })
}