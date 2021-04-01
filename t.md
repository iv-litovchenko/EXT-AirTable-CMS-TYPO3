```
1) Можно ли сделать Дата процессор - это и будут defaultAssign... https://docs.typo3.org/typo3cms/extensions/content_rendering_core/2.0.0/AddingYourOwnContentElements/Index.html

2) <core:iconidentifier="my-icon-identifier" /> (переделать авторегистрацию иконок)
$iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
$iconRegistry->registerFileExtension('log', 'icon-identiifer-for-log-files');
4) Пагинация
5) getFileByHash () для загрузки файлов (что бы файл не пропадал!)
6) Wizard для страниц...

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
