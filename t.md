```
1) Можно ли сделать Дата процессор - это и будут defaultAssign... https://docs.typo3.org/typo3cms/extensions/content_rendering_core/2.0.0/AddingYourOwnContentElements/Index.html
1) 
// Заменить xClass?
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typolinkProcessing']['typolinkModifyParameterForPageLinks'][] = \Your\Namespace\Hooks\MyBeautifulHook::class;
public functionmodifyPageLinkConfiguration(array $linkConfiguration, array $linkDetails,array $pageRow)  {
   $linkConfiguration['additionalParams'] .= $pageRow['myAdditionalParamsField'];
   return $linkConfiguration;
}
2) Иконка редактирования с ворот? с vorot

3) <core:iconidentifier="my-icon-identifier" /> (переделать авторегистрацию иконок)
$iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);$iconRegistry->registerFileExtension('log', 'icon-identiifer-for-log-files');

4) Em_Conf (посмотреть как это описывается в WW)?
5) Пагинация
6) Убрать кнопку удалить (сделать вкл., выкл контента на Ajax)
7) getFileByHash () для загрузки файлов (что бы файл не пропадал!)
7.2 - Переименовать функцию add -> addToIndex()
9) Wizard
10) 91 страница
```

```
А)
ClassLoader::getAliasesForClassName()

Б)
Посмотри что делает переменная $moduleName?
/***The name of the module
**@var string*/
protected $moduleName = 'file_list';

/***Constructor*/



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
