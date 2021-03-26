### AJAX PATH
```
var link = '/?eIdAjax=1&eIdAjaxPath=projiv|FeedBackFormController|indexAction';
```

### HOOKS (для залипания)
```
$GLOBALS['TYPO3_CONF_VARS' ]['SC_OPTIONS']['tslib/class.tslib_fe.php']['isOutputting'][] = 'tx_cachecontrolheader_controller->processDirective';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['usePageCache']
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['insertPageIncache']
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['get_cache_timeout']
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['determineId-PreProcessing']
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['determineId-PostProc'] 
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['pageLoadedFromCache']

/* Collect menu tags when rendering */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/tslib/class.tslib_menu.php']['filterMenuPages'][] =  \Qbus\Autoflush\Hooks\Frontend\RegisterMenuTags::class;


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


	
