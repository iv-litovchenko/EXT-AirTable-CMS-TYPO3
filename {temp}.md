### http://maru-consulting.com/typo3conf/Documentation/typo3cms.extensions.core/default/pdf/manual.core-8.7.pdf
### https://www.mittwald.de/fileadmin/pdf/dokus/Extbase_Fluid_Dokumentation.pdf
### https://www.koller-webprogramming.ch/tipps-tricks/typo3-extension-pibase/arbeiten-mit-sessions/
### https://various.at/news/typo3-9-custom-content-elements/
### https://various.at/news/dropzonejs-mit-typo3
### https://various.at/news/image-upload-mit-typo3
### https://various.at/news/typo3-data-processor
### https://various.at/news/typo3-tipps-und-tricks-psr-15-mideelware-am-beispiel-mailchimp-webhook
### https://various.at/news/grideditor-fuer-inhaltselemente
### https://various.at/news/typo3-indexed-search
### https://docs.typo3.org/m/typo3/book-extbasefluid/master/en-us/9-CrosscuttingConcerns/2-validating-domain-objects.html#validating-in-the-domain-model-with-an-own-validator-class

### https://api.typo3.org/9.5/class_t_y_p_o3_1_1_c_m_s_1_1_extbase_1_1_mvc_1_1_controller_1_1_action_controller.html
### https://www.koller-webprogramming.ch/tipps-tricks/typo3-inhaltselemente/rte-konfig-standartkonfig/
### https://www.koller-webprogramming.ch/tipps-tricks/typo3-inhaltselemente/rte-konfig-standartkonfig-kurz/
### https://www.koller-webprogramming.ch/tipps-tricks/typo3-inhaltselemente/rte-konfig-textstyle-und-blockstyle/
### https://www.koller-webprogramming.ch/tipps-tricks/typo3-inhaltselemente/rte-konfig-blockformat/

### https://extensions.typo3.org/extension/div2007
### https://extensions.typo3.org/extension/migration_core

## Todo
```
Hash CacheTags (залипание, затапливание cHash - сделать панель другого цвета когда есть дин. аргументы) - нужно всегда отдавать ошибку, если нет результатов, т.к. cacheHash создаться на каждый адрес - также продумать для USER_INT
RTE (linkHandler)
Та проблема с бублями Pages/Default.php, Widgets/Defaul.php, PagesElements/Elements/Default.php
Содержит дополнение это и есть PageIdContent?
Редактор меню и текущей страницы...
Сетки, контейнеры с настройками (PageSettings, TtContentSettings), Как в бутстрап...
* Дочерние элементы (как в N)
* cols-аннотация что писать когда контейнер?
* При редактировании придется обновлять всю сетку
* Bug - New Top когда есть контент - смотри "333" "444"
* Оставляем ли Gridelements?
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


