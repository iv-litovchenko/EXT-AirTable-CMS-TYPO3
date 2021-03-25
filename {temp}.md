


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


https://typo3.sascha-ende.de/docs/development/extensions-general/generate-a-link-with-an-extbase-method/
https://github.com/brannow/br-toolkit/blob/master/Docs/Structure/configuration.md
https://github.com/brannow/br-toolkit/blob/master/Docs/requestRoutingMiddleware.md
https://github.com/brannow/br-toolkit/blob/master/Docs/Structure/route.md
https://github.com/brannow/br-toolkit/blob/master/Docs/configurationHandler.md#getextensionconfiguration

Меню бредкрамб (создание)
Взаимодействие между плагинами
Что за маршрут (проверка)
Содержит дополнение "Это и есть PageContent"?



<pre>
Избавиться от cHash

http://iv-litovchenko.ru/
?tx_projiv_pagedefaultcontroller[action]=travelView
&tx_projiv_pagedefaultcontroller[controller]=Pages\PageDefault
&tx_projiv_pagedefaultcontroller[id]=1
&cHash=a1c54489288cb59ddf7fa962476f8f3f


http://iv-litovchenko.ru/
?tx_projiv_pagedefaultcontroller[action]=travels
&cHash=a3d7f1d8e5b26c2ad00b2a1fda9b42ad

https://www.debugcn.com/en/article/36138527.html
'FE' => [
    'cacheHash' => [
        'excludedParameters' => [
            'tx_plugin_action[param1]',
            'tx_plugin_action[param2]',
            'tx_plugin_action[param3]',
            'param4',
        ],
    ],
]
</pre>
	

```
