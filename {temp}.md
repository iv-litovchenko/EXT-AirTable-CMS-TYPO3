


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

## Router
```
На 1 действие может быть несколько маршрутов
Возможно ли сделать staticPage - что бы писать просто <f: router="namePageSatic"> - по существу это уникальная страница в дереве страниц
UrlConveter | получается имя роутера это pageid.controller.action .<f: page="ID" controller="tx_controller_ext..." action=""> а urlConveter просто преобразует в красивый ЧПУ (как со страницами, что бы сделать ссылку мы ищем ID страницы, а не маршрут) - Ищем Контроллер и действие (продумать как это визуально отображать на панелях для отладки!)
Нужно ли делать хелпер <f:router?

Сделать PageesLimits
Значения по умолчанию в функции действия ($id, $tag) как в Yii2
CacheTags (залипание, затапливание - cHash) - нужно всегда отдавать 404-ошибку если результатов нет, т.к. cacheHash создается на каждый адрес!




```
