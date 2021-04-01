```
+ http://maru-consulting.com/typo3conf/Documentation/typo3cms.extensions.core/default/pdf/manual.core-8.7.pdf
+ https://www.mittwald.de/fileadmin/pdf/dokus/Extbase_Fluid_Dokumentation.pdf

https://docs.typo3.org/m/typo3/book-extbasefluid/master/en-us/9-CrosscuttingConcerns/2-validating-domain-objects.html#validating-in-the-domain-model-with-an-own-validator-class
https://various.at/news/typo3-9-custom-content-elements/
https://various.at/news/typo3-data-processor
https://various.at/news/typo3-tipps-und-tricks-psr-15-mideelware-am-beispiel-mailchimp-webhook
https://various.at/news/grideditor-fuer-inhaltselemente
https://various.at/news/typo3-indexed-search

https://api.typo3.org/9.5/class_t_y_p_o3_1_1_c_m_s_1_1_extbase_1_1_mvc_1_1_controller_1_1_action_controller.html
https://www.koller-webprogramming.ch/tipps-tricks/typo3-inhaltselemente/rte-konfig-standartkonfig/
https://www.koller-webprogramming.ch/tipps-tricks/typo3-inhaltselemente/rte-konfig-standartkonfig-kurz/
https://www.koller-webprogramming.ch/tipps-tricks/typo3-inhaltselemente/rte-konfig-textstyle-und-blockstyle/
https://www.koller-webprogramming.ch/tipps-tricks/typo3-inhaltselemente/rte-konfig-blockformat/
https://www.koller-webprogramming.ch/tipps-tricks/typo3-extension-pibase/arbeiten-mit-sessions/

https://extensions.typo3.org/extension/div2007
https://extensions.typo3.org/extension/migration_core
Поковыряй https://nextras.org/orm/docs/4.0/ (рекурсивные функции очень интересно удаление рекурсивно)

## Любой объект можно переделать!
config {
    tx_extbase {
        objects {
            TYPO3\CMS\Extbase\Mvc\View\NotFoundView.className = TYPO3\CMS\FrontendEditing\Mvc\View\NotFoundView
        }
    }
```

## Задокументироват

```
<?php
namespace Litovchenko\Projiv\Controller\PagesElements\Elements;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ElementSubPagesController extends ActionController 
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'FrontendContentElement',
		'name' 			=> 'Дочерние страницы (красивый список)',
		'description' 	=> '',
		'dataFields' 	=> [
            'attr_field1' => [
                'type' => 'Input',
                'name' => 'Field Input 1',
				'items' => [ // Только для Enum, Switcher
					'S' => '(37-38)',
					'L' => 'L (41-42)',
					'XL' => 'XL (43-44)',
					'XXL' => 'XXL (45-46)',
					'XXXL' => '(47-48)'
				]
            ],
            'attr_field2' => [
                'type' => 'Input',
                'name' => 'Field Input 2'
            ],
            'attr_field3' => [
                'type' => 'Input',
                'name' => 'Field Input 3'
            ]
		]
	];
	
    public function indexAction()
    {
    }

}

```

## FAL DRAG ZONE
```
https://www.dropzonejs.com/
page.includeJSFooter.tx_vablog_dropzone = EXT:va_blog/Resources/Public/JavaScript/dropzone.js
<div class="dropzone" data-url="{f:uri.action(action: 'imageupload', controller: 'News', pluginName: 'vablog', pageType:'{settings.ajax.upload}')}"></div>


public function imageuploadAction() {
        try {
            $imageUploader = new \ImageUploader();
            $uniqueFilename = $this->generateUniqueFilename();
            $imageUploader->setPath($this->settings['newsImageUploadFolderFileadmin']);
            $imageUploader->upload($_FILES['file'], $uniqueFilename);
            $ext = \Chilischarf\ChiliNews\Utility\ImageUtility::getExtension($_FILES['file']["name"]);
        }
        catch (\Exception $e) {
            return json_encode(['success' => FALSE]);
        }
        return json_encode(
            [
                'success' => TRUE,
                'filename' => $uniqueFilename . '.' . $ext,
                'path' => $this->settings['newsImageUploadFolderFileadmin']
            ]
        );
    }

var initDropzone = function() {
    if(jQuery('.dropzone').length) {
        Dropzone.autoDiscover = false;
 
        jQuery('.dropzone').each(function () {
            var $this = jQuery(this);
 
            $this.dropzone({
                url: $this.attr('data-url'),
                acceptedFiles: 'image/*',
                dictDefaultMessage: 'Bilder hochladen',
                dictFallbackMessage: 'Ihr Browser wird leider nicht unterstützt',
                dictFileTooBig: 'Die Datei ist zu groß',
                dictInvalidFileType: 'Es werden nur Bilder unterstützt',
                dictResponseError: 'Es werden leider ein Fehler aufgetreten. Code: {{statusCode}}',
                createImageThumbnails: false,
                previewTemplate: '........',
                success: function (file, responseText) {
                    var response = JSON.parse(responseText);
 
                    if (response['success'] == true) {
                        .............
                    }
                }
            });
        });
    }
};
```
